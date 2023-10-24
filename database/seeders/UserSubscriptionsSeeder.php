<?php

namespace Database\Seeders;

use App\Models\TariffPlan;
use App\Models\User;
use App\Repositories\TariffPlansRepository;
use Illuminate\Database\Seeder;

class UserSubscriptionsSeeder extends Seeder
{
    private TariffPlansRepository $tariffPlansRepository;

    public function __construct()
    {
        $this->tariffPlansRepository = app(TariffPlansRepository::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        $plan = $this->tariffPlansRepository->getModelByColumn('name', 'Початковий');

        foreach (User::with('subscription')->get() as $user) {
            if (!$user->subscription) {
                $user->subscription()->create([
                    'plan_id' => $plan->id,
                    'start_date' => now(),
                    'end_date' => now()->addDays($plan->duration_days)
                ]);
            }
        }
    }
}
