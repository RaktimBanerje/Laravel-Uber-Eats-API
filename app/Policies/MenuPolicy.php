<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Facade\FlareClient\Http\Response;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create($user)
    {
        $model = explode("\\", get_class($user));
        $instanceOf =  end($model);

        if($instanceOf == "Restaurant") {
            return $user["is_active"] == 1;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, Menu $menu)
    {
        $model = explode("\\", get_class($user));
        $instanceOf =  end($model);

        if($instanceOf == "Restaurant") {
            return $user["id"] == $menu["restaurant_id"] && $user["is_active"] == 1 && $menu["is_active"] == 1;
        }

        if($instanceOf == "User") {
            return in_array($user["role"], ["ADMIN", "MANAGER", "REVIEWER"]);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete($user, Menu $menu)
    {
        $model = explode("\\", get_class($user));
        $instanceOf =  end($model);

        if($instanceOf == "Restaurant") {
            return $user["id"] == $menu["restaurant_id"];
        }

        if($instanceOf == "User") {
            return in_array($user["role"], ["ADMIN", "MANAGER", "REVIEWER"]);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Menu $menu)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Menu $menu)
    {
        //
    }

    public function approve($user, Menu $menu)
    {
        return in_array($user["role"], ["ADMIN", "MANAGER", "REVIEWER"]);
    }
}
