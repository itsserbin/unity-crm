<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Repositories\Options\SourcesRepository;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Statistics\ProfitStatisticsRepository;
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

    private mixed $profitStatisticsRepository;
    private mixed $statusesRepository;
    private mixed $sourcesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->profitStatisticsRepository = app(ProfitStatisticsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
        $this->sourcesRepository = app(SourcesRepository::class);
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
                $sources = $this->sourcesRepository->getAll();
                $statuses = $this->statusesRepository->getAll();

                foreach ($sources as $source) {
                    foreach ($statuses as $status) {
                        $this->profitStatisticsRepository->findOrCreateAndUpdate(
                            $dateNow, $status['id'], $source['id']
                        );
                    }
                }
            });
        }
    }
}
