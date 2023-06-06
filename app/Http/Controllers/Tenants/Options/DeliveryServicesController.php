<?php

namespace App\Http\Controllers\Tenants\Options;

use App\Http\Controllers\Tenants\BaseController;
use App\Models\Enums\DeliveryServices;
use App\Repositories\Options\DeliveryServicesRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeliveryServicesController extends BaseController
{
    private mixed $deliveryServicesRepository;

    final public function __construct()
    {
        parent::__construct();
        $this->deliveryServicesRepository = app(DeliveryServicesRepository::class);
    }

    final public function create(Request $request): Response
    {
        $deliveryServices = $this->deliveryServicesRepository->getAllWithPaginate($request->all());
        $globalServices = DeliveryServices::state;

        return Inertia::render('Tenants/Options/DeliveryServices/Index', [
            'deliveryServices' => $deliveryServices,
            'globalServices' => $globalServices
        ]);
    }
}
