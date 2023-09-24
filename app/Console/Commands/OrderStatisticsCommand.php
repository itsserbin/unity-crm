<?php

namespace App\Console\Commands;

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

    private mixed $orderStatisticsRepository;
    private mixed $statusesRepository;
    private mixed $ordersRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderStatisticsRepository = app(OrderStatisticsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
        $this->ordersRepository = app(OrdersRepository::class);
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
                $statuses = $this->statusesRepository->getAll();
                foreach ($statuses as $status) {
                    if (!$this->orderStatisticsRepository->getModelByStatusAndDate($dateNow, $status['id'])) {
                        $model = $this->orderStatisticsRepository->createModelClass();
                        $model['date'] = $dateNow;
                        $model['status_id'] = $status['id'];
                        $model['count'] = $this->ordersRepository->countOrderByDate($dateNow, $status['id']);
                        $model->save();
                    }
                }
            });
        }
    }
}
