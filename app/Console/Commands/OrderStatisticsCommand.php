<?php

namespace App\Console\Commands;

use App\Jobs\OrderStatistics;
use App\Models\Tenant;
use App\Repositories\CRM\OrdersRepository;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Statistics\OrderStatisticsRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OrderStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:order-statistics-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    final public function handle(): void
    {
        $tenants = Tenant::all();
        $dateNow = Carbon::now()->toDateString();

        foreach ($tenants as $tenant) {
            $tenant->run(function () use ($dateNow) {
                OrderStatistics::dispatch($dateNow);
            });
        }

    }
}
