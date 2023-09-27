<?php

namespace App\Console\Commands;

use App\Jobs\ProfitStatistics;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProfitStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:profit-statistics-command';

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
                ProfitStatistics::dispatch($dateNow);
            });
        }
    }
}
