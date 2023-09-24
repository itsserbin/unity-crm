<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Tenants\CRM\ClientsController;
use App\Http\Requests\TenantsRequest;
use App\Models\Catalog\Product;
use App\Repositories\Catalog\ProductsRepository;
use App\Repositories\CRM\ClientsRepository;
use App\Repositories\CRM\OrdersRepository;
use App\Repositories\Options\SourcesRepository;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\TenantsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends BaseController
{

    private mixed $sourcesRepository;
    private mixed $ordersRepository;
    private mixed $clientsRepository;
    private mixed $productsRepository;
    private mixed $statusesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->sourcesRepository = app(SourcesRepository::class);
        $this->ordersRepository = app(OrdersRepository::class);
        $this->clientsRepository = app(ClientsRepository::class);
        $this->productsRepository = app(ProductsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function create(Request $request): JsonResponse
    {
        $token = $request->bearerToken();
        dd($orderData);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Апі токен не знайдено'
            ], 401);
        }

        $params = $request->all();

        $client = $this->createOrUpdateClient($params);

        $items = $this->processProducts($params);

        $status = $this->statusesRepository->getByColumn('title', 'Новий');

        $orderData = [
            'status_id' => $status['id'],
            'source_id' => $params['source_id'],
            'delivery_service_id' => $params['delivery_service_id'] ?? null,
            'delivery_address' => $params['delivery_address'] ?? null,
            'comment' => $params['comment'] ?? null,
            'manager_id' => $params['manager_id'] ?? null,
            'client_id' => $client['id'] ?? null,
            'items' => $items
        ];


        $this->ordersRepository->create($orderData);

        return response()->json([
            'success' => true,
            'message' => 'Замовлення створене'
        ]);
    }

    private function createOrUpdateClient($params)
    {
        if (isset($params['client'])) {
            $client = $this->clientsRepository->findByColumn('phones', $params['client']['phone']);

            if (!$client) {
                $clientData = [
                    'full_name' => $params['client']['name'] ?? 'Не вказано',
                    'phones' => [$params['client']['phone']],
                    'emails' => isset($params['client']['email']) ? [$params['client']['email']] : []
                ];

                $client = $this->clientsRepository->create($clientData);
            } else {
                $updateData = [];

                if (isset($params['client']['name'])) {
                    $updateData['full_name'] = $params['client']['name'];
                }

                if (isset($params['client']['email'])) {
                    $updateData['emails'] = [$params['client']['email']];
                }

                $client->update($updateData);
            }

            return $client;
        }

        return null;
    }

    private function processProducts($params)
    {
        $items = [];

        if (count($params['products'])) {
            foreach ($params['products'] as $item) {
                $product = null;

                if (isset($item['id'])) {
                    $product = $this->productsRepository->getModelById($item['id']);
                } elseif (isset($item['sku'])) {
                    $product = $this->productsRepository->findByColumn('sku', $item['sku']);
                }

                if (!$product) {
                    $productData = [
                        'sku' => $item['sku'],
                        'price' => $item['price'],
                        'discount_price' => $item['discount_price'],
                        'title' => $item['title'],
                    ];

                    $product = $this->productsRepository->create($productData);
                }

                $items[] = [
                    'sale_price' => $item['discount_price'] ?? $item['price'],
                    'sku' => $item['sku'],
                    'title' => $item['title'],
                ];
            }
        }

        return $items;
    }

}
