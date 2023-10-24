<?php

namespace Database\Seeders;

use App\Models\Enums\Statuses;
use App\Models\Enums\StatusGroups;
use App\Repositories\Options\StatusesRepository;
use App\Repositories\Options\StatusGroupsRepository;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    private mixed $statusGroupsRepository;
    private mixed $statusesRepository;

    public function __construct()
    {
        $this->statusGroupsRepository = app(StatusGroupsRepository::class);
        $this->statusesRepository = app(StatusesRepository::class);
    }

    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        foreach (StatusGroups::state as $item) {
            if (!$this->statusGroupsRepository->getModelByColumn('slug', $item['slug'])) {
                $this->statusGroupsRepository->create($item);
            }
        }

        foreach (Statuses::state as $item) {
            if (!$this->statusesRepository->getModelByTitleAndGroupSlug($item['title'], $item['group_slug'])) {
                $this->statusesRepository->create($item);
            }
        }
    }
}
