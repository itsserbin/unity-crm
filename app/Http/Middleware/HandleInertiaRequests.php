<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    final public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    final public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'user' => [
                'roles' => $request->user()
                    ? $request->user()->roles->pluck('name')
                    : [],
                'permissions' => $request->user()
                    ? $request->user()->getPermissionsViaRoles()->pluck('name')
                    : []
            ],
            'subscription' => tenant()->user->subscription->select(['start_date', 'end_date'])->first(),
            'features' => tenant()->user->subscription->plan->features
        ]);
    }
}
