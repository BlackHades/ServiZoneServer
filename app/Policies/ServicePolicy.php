<?php

namespace App\Policies;

use App\Service;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the service.
     *
     * @param  \App\User $user
     * @param  \App\Service $service
     * @return mixed
     */
    public function view(User $user, Service $service)
    {
        //
    }

    /**
     * Determine whether the user can create services.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the service.
     *
     * @param  \App\User $user
     * @param  \App\Service $service
     * @return mixed
     */
    public function update(User $user, Service $service)
    {
        return $user->id === $service->user_id;

    }

    /**
     * Determine whether the user can delete the service.
     *
     * @param  \App\User $user
     * @param  \App\Service $service
     * @return mixed
     */
    public function delete(User $user, Service $service)
    {
        return $user->id === $service->user_id;
    }


    public function createReview(User $user, Service $service)
    {
        return $user->id !== $service->user_id;
    }
}
