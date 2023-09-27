<?php

namespace App\Jobs;

use App\Repositories\CRM\OrdersRepository;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Statistics\OrderStatisticsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $orderStatisticsRepository;
    private mixed $statusesRepository;
    private mixed $ordersRepository;
    private string $dateNow;

    /**
     * Create a new job instance.
     */
    public function __construct(string $dateNow)
    {
        $this->dateNow = $dateNow;
        $this->orderStatisticsRepository = app(OrderStatisticsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
        $this->ordersRepository = app(OrdersRepository::class);
    }

    /**
     * Execute the job.
     */
    final public function handle(): void
    {
        $statuses = $this->statusesRepository->getAll();
        foreach ($statuses as $status) {
            $this->orderStatisticsRepository->getModelByStatusAndDate($this->dateNow, $status['id']);
        }
    }
}
