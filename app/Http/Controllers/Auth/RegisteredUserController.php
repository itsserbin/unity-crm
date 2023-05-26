<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Seeders\StatusesSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    final public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    final public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:' . User::class,
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Tenant::where('id', $request->domain)->first()) {
            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $tenant = Tenant::create([
                'id' => $request->domain,
                'data' => [
                    'user_id' => $user->id
                ]
            ]);

            $tenant->domains()->create([
                'domain' => $request->domain . '.' . env('APP_DOMAIN')
            ]);

            $tenant->run(function () use ($request) {
                $user = User::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                Artisan::call('db:seed');
                Artisan::call('storage:link');
                Auth::login($user);
            });


            Auth::login($user);
            event(new Registered($user));
            return Inertia::location('http://' . $request->domain . '.' . env('APP_DOMAIN'));
        }
    }
}
