<?php

namespace App\Http\Controllers\Api;

use App\Repositories\DeliveryServicesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryServicesController extends BaseController
{
    private mixed $deliveryServicesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->deliveryServicesRepository = app(DeliveryServicesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function index(Request $request): JsonResponse
    {
        $result = $this->deliveryServicesRepository->getAllWithPaginate($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @return JsonResponse
     */
    final public function list(): JsonResponse
    {
        $result = $this->deliveryServicesRepository->list();

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function create(Request $request): JsonResponse
    {
        $result = $this->deliveryServicesRepository->create($request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function edit(int $id): JsonResponse
    {
        $result = $this->deliveryServicesRepository->getModelById($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    final public function update(int $id, Request $request): JsonResponse
    {
        $result = $this->deliveryServicesRepository->update($id, $request->all());

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    final public function destroy(int $id): JsonResponse
    {
        $result = $this->deliveryServicesRepository->destroy($id);

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }


    final public function setPublished(Request $request): JsonResponse
    {
        $result = $this->deliveryServicesRepository->setPublished($request->get('id'), $request->get('value'));

        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}
