<?php

namespace App\Services;

use JsonException;

class MonobankService
{
    private mixed $apiService;
    private mixed $token;

    public function __construct()
    {
        $this->apiService = app(ApiService::class);
    }

    /**
     */
    final public function getPersonalInfo(string $token): array
    {
        return $this->apiService->response(
            config('monobank.clientInfoUrl'),
            [],
            'GET',
            ['X-Token: ' . $token]
        );
    }

    /**
     */
    final public function getExtract(array $data): array
    {
        if (isset($data['from'])) {
            $fromDate = \Carbon\Carbon::parse($data['from']);
            $from = $fromDate->timestamp;
        } else {
            $from = \Carbon\Carbon::now()->subDays(30)->timestamp;
        }
        if (isset($data['to'])) {
            $toDate = \Carbon\Carbon::parse($data['to']);
            $to = $toDate->timestamp;
        } else {
            $to = time();
        }

        $url = config('monobank.statementUrl') . $data['clientId'] . '/' . $from . '/' . $to;

        $headers = ['X-Token: ' . $data['token']];

        return $this->apiService->response($url, [], 'GET', $headers);
    }

    final public function setData()
    {
        $data = $this->getExtract();

//        foreach ($data['result'] as $item) {
//            if (isset($item['comment'])) {
//
//                if (preg_match('/^#RC-\d+$/', $item['comment'])) {
//                    $orderId = explode('-', $item['comment'])[1];
//                    $code = '#RC';
//                } else {
//                    $code = $item['comment'];
//                }
//
//                $date = date("Y-m-d H:i:s", $item['time']);
//
//                $ccModel = $this->costCategoriesRepository->getByCode($code);
//
//                if ($ccModel && !$this->costsRepository->getByCostCategoryAndDate($ccModel->id, $date)) {
//
//                    $this->costsRepository->onCreate([
//                        'date' => $date,
//                        'quantity' => 1,
//                        'sum' => (int)(abs($item['amount']) / 100) === abs($item['amount']) / 100
//                            ? number_format(abs($item['amount']) / 100, 0, '.', '')
//                            : number_format(abs($item['amount']) / 100, 2, '.', ''),
//                        'cost_category_id' => $ccModel->id
//                    ]);
//                }
//
//            }
//        }
    }
}
