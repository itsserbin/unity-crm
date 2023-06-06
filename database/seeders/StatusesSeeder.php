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
        foreach (StatusGroups::state as $statsGroup) {
            if (!$this->statusGroupsRepository->getModelBySlug($statsGroup['slug'])) {
                $this->statusGroupsRepository->create($statsGroup);
            }
        }

        foreach (Statuses::state as $status) {
            if (!$this->statusesRepository->getModelByTitleAndGroupSlug($status['title'], $status['group_slug'])) {
                $this->statusesRepository->create($status);
            }
        }
    }
}
