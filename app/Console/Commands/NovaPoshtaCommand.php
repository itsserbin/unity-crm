<?php

namespace App\Console\Commands;

use App\Jobs\UpdateTrackingCode;
use App\Models\Order;
use App\Models\Tenant;
use Illuminate\Console\Command;

class NovaPoshtaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:nova-poshta-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all tracking codes in orders';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    final public function handle(): bool
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $tenant->run(function () {
                $orders = Order::select('id')
                    ->whereHas('trackingCodes', function ($q) {
                        $q->whereHas('deliveryServices', function ($q) {
                            $q->where('status', 1);
                            $q->where('type', 'novaposhta');
                        });
                        $q->where('code', '!=', null);
                    })
                    ->with([
                        'trackingCodes' => function ($q) {
                            $q->select(['code', 'order_id', 'id']);
                            $q->with('deliveryServices:status,type,id,api_key');
                        }])
                    ->get();


                if (!empty($orders)) {
                    foreach ($orders as $order) {
                        if (!empty($order->trackingCodes)) {
                            foreach ($order->trackingCodes as $trackingCode) {
                                UpdateTrackingCode::dispatch(
                                    $trackingCode->code,
                                    $order->id,
                                    $trackingCode->deliveryServices->api_key ?? null
                                );
                            }
                        }
                    }
                }
            });
        }

        return 0;
    }
}
