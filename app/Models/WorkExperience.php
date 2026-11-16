<?php

namespace App\Models;

use App\Enums\EmploymentType;
use Database\Factories\WorkExperienceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'company_name', 'position', 'employment_type', 'start_date', 'end_date', 'description'])]
class WorkExperience extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function period(): string
    {
        $end = $this->end_date?->translatedFormat('M Y') ?? 'Sekarang';

        return $this->start_date->translatedFormat('M Y').' - '.$end;
    }
}
