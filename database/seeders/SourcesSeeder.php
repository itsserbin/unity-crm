<?php

namespace Database\Seeders;

use App\Models\Enums\Sources;
use App\Repositories\Options\SourcesRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourcesSeeder extends Seeder
{
    private mixed $sourcesRepository;

    public function __construct()
    {
        $this->sourcesRepository = app(SourcesRepository::class);
    }

    /**
     * Run the database seeds.
     */
    final public function run(): void
    {
        foreach (Sources::state as $source) {
            if (!$this->sourcesRepository->getModelByColumn('source', $source['value'])) {
                $this->sourcesRepository->create([
                    'title' => $source['title'],
                    'source' => $source['value']
                ]);
            }
        }
    }
}
