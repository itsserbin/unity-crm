<?php

namespace App\Jobs;

use App\Repositories\Options\SourcesRepository;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Statistics\ProfitStatisticsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProfitStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $profitStatisticsRepository;
    private mixed $statusesRepository;
    private mixed $sourcesRepository;
    private string $dateNow;

    /**
     * Create a new job instance.
     */
    public function __construct(string $dateNow)
    {
        $this->dateNow = $dateNow;
        $this->profitStatisticsRepository = app(ProfitStatisticsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
        $this->sourcesRepository = app(SourcesRepository::class);
    }

    /**
     * Execute the job.
     */
    final public function handle(): void
    {
        $sources = $this->sourcesRepository->getAll();
        $statuses = $this->statusesRepository->getAll();
        foreach ($sources as $source) {
            foreach ($statuses as $status) {
                $this->profitStatisticsRepository->findOrCreateAndUpdate(
                    $this->dateNow, $status['id'], $source['id']
                );
            }
        }
    }
}
