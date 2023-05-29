<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TrackingCode;
use App\Services\ApiService;
use Illuminate\Http\Request;

class NovaPoshtaController extends Controller
{
    private $apiService;

    public function __construct()
    {
        $this->apiService = app(ApiService::class);
    }

    final public function index()
    {
        $order = Order::where('id', 12)->with('trackingCodes')->first();

        foreach ($order->trackingCodes as $trackingCode) {
            $trackingModel = TrackingCode::where('code', $trackingCode)->first();
            if (!$trackingModel) {
                $trackingModel = new TrackingCode();
                $trackingModel->code = $trackingCode->code;
                $trackingModel->order_id = $order->id;
                $trackingModel->save();
            }
            $res = $this->apiService->response(env('NOVA_POSHTA_API_URL'), $this->dataParams($order->tracking_code));
            if ($res['result']['success']) {
                $response = $res['result']['data'][0];
                $data = [
                    'Очікувана дата доставки' => $response['ScheduledDeliveryDate'],
                    'Фактична дата доставки' => $response['ActualDeliveryDate'],
                    'Вартість доставки' => $response['DocumentCost'],
                    'Тип оплати' => $response['PaymentMethod'],
                    'Місто отримувача' => $response['CityRecipient'],
                    'Склад отримувача' => $response['WarehouseRecipient'],
                    'Місто відправника' => $response['CitySender'],
                    'Відділення відправника' => $response['WarehouseSender'],
                    'Дата створення ЕН' => $response['DateCreated'],
                    'Адреса відділення одержувача' => $response['WarehouseRecipientAddress'],
                    'Адреса відділення відправника' => $response['WarehouseSenderAddress'],
                ];
                $trackingModel->data = $data;

                $newStatus = [
                    'code' => $response['StatusCode'],
                    'status' => $response['Status'],
                    'scan_date' => $response['DateScan']
                ];
                if (empty($trackingModel->log) || ($newStatus['code'] !== end($trackingModel->log)['code'] && $newStatus['scan_date'] !== end($trackingModel->log)['scan_date'])) {
                    $log = $trackingModel->log;
                    $log[] = $newStatus;
                    $trackingModel->log = $log;
                }
                $trackingModel->update();
            }
        }
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
