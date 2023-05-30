<?php

namespace App\Services;

use App\Models\Order;
use App\Models\TrackingCode;
use Illuminate\Database\Eloquent\Model;

class NovaPoshtaService
{
    private $apiService;

    public function __construct()
    {
        $this->apiService = app(ApiService::class);
    }

    final public function updateTrackingCodes()
    {
        $orders = Order::select('id')
            ->whereHas('trackingCodes', function ($q) {
                $q->whereHas('deliveryServices', function ($q) {
                    $q->where('status', 1);
                    $q->where('type', 'novaposhta');
                });
                $q->where('code', '!=', null);
            })
            ->with('trackingCodes:code,order_id,id')
            ->get();


        if (!empty($orders)) {
            foreach ($orders as $order) {
                if (!empty($order->trackingCodes)) {
                    foreach ($order->trackingCodes as $trackingCode) {
                        $this->updateItem($trackingCode, $order);
                    }
                }
            }
        }

        return 0;
    }

    private function updateItem(Model $trackingCode, Model $order): bool
    {
        $trackingModel = TrackingCode::where('code', $trackingCode->code)
            ->with('deliveryServices:id,type,api_key,configuration,status')
            ->first();

        if (!$trackingModel) {
            $trackingModel = new TrackingCode();
            $trackingModel->code = $trackingCode->code;
            $trackingModel->order_id = $order->id;
            $trackingModel->save();
        }
        $res = $this->apiService->response(env('NOVA_POSHTA_API_URL'), $this->dataParams($trackingCode->code));

        if ($res['result']['success']) {
            $response = $res['result']['data'][0];
            $data = [
                'ScheduledDeliveryDate' => $response['ScheduledDeliveryDate'],
                'ActualDeliveryDate' => $response['ActualDeliveryDate'],
                'DocumentCost' => $response['DocumentCost'],
                'PaymentMethod' => $response['PaymentMethod'],
                'CityRecipient' => $response['CityRecipient'],
                'WarehouseRecipient' => $response['WarehouseRecipient'],
                'CitySender' => $response['CitySender'],
                'WarehouseSender' => $response['WarehouseSender'],
                'DateCreated' => $response['DateCreated'],
                'WarehouseRecipientAddress' => $response['WarehouseRecipientAddress'],
                'WarehouseSenderAddress' => $response['WarehouseSenderAddress'],
            ];
            $trackingModel->data = $data;

            $newStatus = [
                'code' => $response['StatusCode'],
                'status' => $response['Status'],
                'scan_date' => $response['DateScan']
            ];
            $log = $trackingModel->log;
            if (empty($trackingModel->log) || ($newStatus['code'] !== end($log)['code'] && $newStatus['scan_date'] !== end($log)['scan_date'])) {
                $log[] = $newStatus;
                $trackingModel->log = $log;
            }
            return $trackingModel->update();
        }
        return false;
    }

    private function dataParams(string $tracking_code): array
    {
        return [
            'apiKey' => env('NOVA_POSHTA_API_KEY'),
            'modelName' => 'TrackingDocument',
            'calledMethod' => 'getStatusDocuments',
            'methodProperties' => [
                'Documents' => [
                    ['DocumentNumber' => $tracking_code],
                ]
            ]
        ];
    }
}
