<?php

namespace App\Http\Controllers\Api\Tenants;

use App\Repositories\ImagesRepository;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    private mixed $imageUploadService;
    private mixed $imagesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->imageUploadService = app(ImageUploadService::class);
        $this->imagesRepository = app(ImagesRepository::class);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final public function uploadProductPreview(Request $request): JsonResponse
    {
        $result = $this->imageUploadService->uploadAndFormatImage($request->file('image'), 100);
        $this->imagesRepository->create([
            'alt' => null,
            'data' => $result
        ]);
        return $this->returnResponse([
            'success' => true,
            'result' => $result,
        ]);
    }
}
