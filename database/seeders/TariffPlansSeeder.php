<?php

namespace Database\Seeders;

use App\Repositories\TariffPlansRepository;
use Illuminate\Database\Seeder;

class TariffPlansSeeder extends Seeder
{
    private array $data;
    private array $features;
    private TariffPlansRepository $tariffPlansRepository;

    public function __construct()
    {
        $this->tariffPlansRepository = app(TariffPlansRepository::class);
        $this->features = [
            'Початковий' => [
                'domains' => 1,
                'products' => 10,
                'users' => 3,
                'finance' => false,
                'statistics' => false,
                'support' => false,
                'api' => false,
            ],
            'Стандарт' => [
                'domains' => 3,
                'products' => 100,
                'users' => 10,
                'finance' => false,
                'statistics' => true,
                'support' => false,
                'api' => true,
            ],
            'Преміум' => [
                'domains' => 10,
                'products' => 1000,
                'users' => 100,
                'finance' => true,
                'statistics' => true,
                'support' => true,
                'api' => true,
            ]
        ];
        $this->data = [
            [
                'name' => 'Початковий',
                'description' => '',
                'price' => 15,
                'duration_days' => 30,
                'features' => $this->features['Початковий']
            ],
            [
                'name' => 'Стандарт',
                'description' => '',
                'price' => 29,
                'duration_days' => 30,
                'features' => $this->features['Стандарт']
            ],
            [
                'name' => 'Преміум',
                'description' => '',
                'price' => 49,
                'duration_days' => 30,
                'features' => $this->features['Преміум']
            ],
            [
                'name' => 'Пробний початковий',
                'description' => '',
                'price' => 15,
                'duration_days' => 14,
                'features' => $this->features['Початковий']
            ],
            [
                'name' => 'Пробний стандарт',
                'description' => '',
                'price' => 29,
                'duration_days' => 14,
                'features' => $this->features['Стандарт']
            ],
            [
                'name' => 'Пробний преміум',
                'description' => '',
                'price' => 49,
                'duration_days' => 14,
                'features' => $this->features['Преміум']
            ]
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        foreach ($this->data as $item) {
            if (!$this->tariffPlansRepository->getModelByColumn('name', $item['name'])) {
                $this->tariffPlansRepository->create($item);
            }
        }
    }
}
