<?php

namespace App\Jobs;

use App\Repositories\CRM\TrackingCodesRepository;
use App\Services\ApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTrackingCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private mixed $apiService;
    private mixed $trackingCodesRepository;

    private string $trackingCode;
    private int $id;
    private string $api;

    /**
     * Create a new job instance.
     */
    public function __construct(string $trackingCode, int $id, string $api = null)
    {
        $this->trackingCode = $trackingCode;
        $this->id = $id;
        $this->api = $api ?? config('novaposhta.key');
        $this->apiService = app(ApiService::class);
        $this->trackingCodesRepository = app(TrackingCodesRepository::class);
    }

    /**
     * Execute the job.
     * @throws \JsonException
     */
    final public function handle(): bool
    {
        $trackingModel = $this->trackingCodesRepository->getModelByCodeAndOrderId(
            $this->trackingCode,
            $this->id
        );

        if (!$trackingModel) {
            $trackingModel = $this->trackingCodesRepository->create([
                'code' => $this->trackingCode,
                'order_id' => $this->id
            ]);
        }

        $res = $this->apiService->response(config('novaposhta.url'), $this->dataParams($this->trackingCode, $this->api));

        if ($res['result']->success) {
            $response = $res['result']->data[0];

            $data = [
                'ScheduledDeliveryDate' => $response->ScheduledDeliveryDate,
                'ActualDeliveryDate' => $response->ActualDeliveryDate,
                'DocumentCost' => $response->DocumentCost,
                'PaymentMethod' => $response->PaymentMethod,
                'CityRecipient' => $response->CityRecipient,
                'WarehouseRecipient' => $response->WarehouseRecipient,
                'CitySender' => $response->CitySender,
                'WarehouseSender' => $response->WarehouseSender,
                'DateCreated' => $response->DateCreated,
                'WarehouseRecipientAddress' => $response->WarehouseRecipientAddress,
                'WarehouseSenderAddress' => $response->WarehouseSenderAddress,
            ];

            $trackingModel->data = $data;

            $newStatus = [
                'code' => $response->StatusCode,
                'status' => $response->Status,
                'scan_date' => $response->DateScan
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
