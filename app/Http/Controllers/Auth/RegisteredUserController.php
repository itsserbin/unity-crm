<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\TariffPlan;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\AccountsSeeder;
use Database\Seeders\MovementCategoriesSeeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\SourcesSeeder;
use Database\Seeders\StatusesSeeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

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
     * @throws \Exception
     */
    final public function store(RegisterRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $password = Hash::make($request->password);
        $phone = preg_replace('/[^0-9]/', '', $request->phone);
        $domain = $request->domain . '.' . env('APP_DOMAIN');

        DB::beginTransaction();
        try {
            $user = $this->createUser($request, $password, $phone);
            DB::commit();

            $this->createTenant($request, $user, $password, $phone, $domain);

            Auth::login($user);
            event(new Registered($user));
            return Inertia::location(
                env('APP_PROTOCOL') . $domain
                . '?email=' . Crypt::encryptString($user->email)
                . '&phone=' . Crypt::encryptString($user->phone)
                . '&password=' . Crypt::encryptString($user->password)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function createUser(RegisterRequest $request, string $password, string $phone): User
    {
        $user = new User();
        $user->name = $request->name;
        $user->phone = $phone;
        $user->email = $request->email;
        $user->password = $password;
        $user->save();

        $plan = TariffPlan::where('name', 'Початковий')->first();
        if ($plan) {
            $user->subscription()->create([
                'plan_id' => $plan->id,
                'start_date' => Carbon::now()->toDateTimeString(),
                'end_date' => Carbon::now()->addDays($plan->duration_days)->toDateTimeString(),
            ]);
        }

        return $user;
    }

    private function createTenant(
        RegisterRequest $request,
        User $user,
        string $password,
        string $phone,
        string $domain
    ): void
    {
        $tenant = new Tenant();
        $tenant->id = $request->domain;
        $tenant->name = $request->tanant_name;
        $tenant->user_id = $user->id;
        $tenant->save();

        $tenant->domains()->create([
            'domain' => $domain
        ]);

        $tenant->run(function () use ($request, $password, $phone) {
            $user = User::create([
                'name' => $request->name,
                'phone' => $phone,
                'email' => $request->email,
                'password' => $password,
            ]);
            (new StatusesSeeder())->run();
            (new MovementCategoriesSeeder())->run();
            (new AccountsSeeder())->run();
            (new SourcesSeeder())->run();
            (new RolesSeeder())->run();

            $user->syncRoles(['admin']);
        });
    }
}
