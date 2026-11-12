<?php

namespace App\Policies;

use App\Models\JobListing;
use App\Models\User;

class JobListingPolicy
{
    /**
     * Employers may only manage listings that belong to their own company.
     */
    public function update(User $user, JobListing $jobListing): bool
    {
        return $user->isEmployer() && $user->company?->id === $jobListing->company_id;
    }

    public function apply(User $user, JobListing $jobListing): bool
    {
        return $user->isJobSeeker() && $jobListing->isOpen();
    }
}
