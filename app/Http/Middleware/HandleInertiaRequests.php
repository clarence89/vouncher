<?php

namespace App\Http\Middleware;

use Hamcrest\Arrays\IsArray;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
    if ($request->user()) {
        $permissions = $request->user()->permissions()->get();
        $roles = $request->user()->roles()->get();
        $role_permission = $request->user()->roles()->with('permissions')->get();
        $rolespe = collect();
        foreach ($role_permission as  $role) {
            foreach ($role->permissions as  $permissionse) {
                $rolespe->push($permissionse);
            }
        }
        foreach ($rolespe as $value) {
            if ($permissions->contains('name',$value->name)) {

            }else{
                $permissions->push($value);
            }
        }
        $request->user()->permissions = $permissions;
        $request->user()->roles = $roles;

    }

        return array_merge(parent::share($request), [


        ]);
    }
}
