<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\JobCategory;
use App\Enums\JobStatus;
use App\Enums\WorkSystem;
use Database\Factories\JobListingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'company_id', 'title', 'category', 'location', 'employment_type', 'work_system',
    'salary_min', 'salary_max', 'description', 'responsibilities', 'qualifications',
    'deadline', 'status', 'published_at',
])]
class JobListing extends Model
{
    /** @use HasFactory<JobListingFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => JobCategory::class,
            'employment_type' => EmploymentType::class,
            'work_system' => WorkSystem::class,
            'status' => JobStatus::class,
            'salary_min' => 'integer',
            'salary_max' => 'integer',
            'deadline' => 'date',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Company, $this>
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasMany<Application, $this>
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Listings that are open and whose deadline has not passed.
     *
     * @param  Builder<JobListing>  $query
     */
    public function scopeOpen(Builder $query): void
    {
        $query->where('status', JobStatus::Active)->whereDate('deadline', '>=', today());
    }

    /**
     * Apply the public keyword search.
     *
     * @param  Builder<JobListing>  $query
     * @param  array<string, mixed>  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query
            ->when($filters['q'] ?? null, function (Builder $query, string $keyword): void {
                $query->where(function (Builder $query) use ($keyword): void {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('location', 'like', "%{$keyword}%")
                        ->orWhereHas('company', fn (Builder $query) => $query->where('name', 'like', "%{$keyword}%"));
                });
            });
    }

    public function isOpen(): bool
    {
        return $this->status === JobStatus::Active && $this->deadline->endOfDay()->isFuture();
    }

    public function salaryRange(): string
    {
        if (! $this->salary_min && ! $this->salary_max) {
            return 'Gaji dirahasiakan';
        }

        $format = fn (int $amount): string => 'Rp '.number_format($amount, 0, ',', '.');

        if ($this->salary_min && $this->salary_max) {
            return $format($this->salary_min).' - '.$format($this->salary_max);
        }

        return $this->salary_min ? 'Mulai '.$format($this->salary_min) : 'Hingga '.$format($this->salary_max);
    }
}
