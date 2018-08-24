<?php

namespace App\Policies;

use App\Review;
use App\Service;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the review.
     *
     * @param  \App\User $user
     * @param  \App\Review $review
     * @return mixed
     */
    public function view(User $user, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can create reviews for the service.
     *
     * @param  \App\User $user
     * @param Service $service
     * @return mixed
     */
    public function create(User $user, Service $service)
    {
        return $user->id == $service->user_id;
    }

    /**
     * Determine whether the user can update the review.
     *
     * @param  \App\User $user
     * @param  \App\Review $review
     * @return mixed
     */
    public function update(User $user, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can delete the review.
     *
     * @param  \App\User $user
     * @param  \App\Review $review
     * @return mixed
     */
    public function delete(User $user, Review $review)
    {
        //
    }
}
