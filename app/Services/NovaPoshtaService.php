<?php

namespace App\Services;

use App\Models\Order;
use App\Models\TrackingCode;
use App\Repositories\TrackingCodesRepository;
use Illuminate\Database\Eloquent\Model;

class NovaPoshtaService
{
    private mixed $apiService;
    private mixed $trackingCodesRepository;

    public function __construct()
    {
        $this->apiService = app(ApiService::class);
        $this->trackingCodesRepository = app(TrackingCodesRepository::class);
    }

    /**
     * @throws \JsonException
     */
    final public function updateTrackingCodes(): bool
    {
        $orders = Order::select('id')
            ->whereHas('trackingCodes', function ($q) {
                $q->whereHas('deliveryServices', function ($q) {
                    $q->where('status', 1);
                    $q->where('type', 'novaposhta');
                });
                $q->where('code', '!=', null);
            })
            ->with(['trackingCodes:code,order_id,id' => function ($q) {
                $q->select(['code', 'order_id', 'id']);
                $q->with('deliveryServices:status,type,id,api_key');
            }])
            ->get();


        if (!empty($orders)) {
            foreach ($orders as $order) {
                if (!empty($order->trackingCodes)) {
                    foreach ($order->trackingCodes as $trackingCode) {
                        $this->updateItem($trackingCode->code, $order->id, $trackingCode->deliveryServices->api_key);
                    }
                }
            }
        }

        return 0;
    }

    /**
     * @throws \JsonException
     */
    final public function updateItem(string $trackingCode, int $id, string $api = null): bool
    {
        if (!$api) {
            $api = config('novaposhta.key');
        }

        $trackingModel = $this->trackingCodesRepository->getModelByCode($trackingCode);

        if (!$trackingModel) {
            $trackingModel = $this->trackingCodesRepository->create([
                'code' => $trackingCode,
                'order_id' => $id
            ]);
        }
        $res = $this->apiService->response(config('novaposhta.url'), $this->dataParams($trackingCode, $api));

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

    private function dataParams(string $tracking_code, string $api): array
    {
        return [
            'apiKey' => $api,
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
