<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\NovaPoshtaService;
use Illuminate\Console\Command;

class NovaPoshtaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:nova-poshta-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all tracking codes in orders';

    private mixed $novaPoshtaService;

    public function __construct()
    {
        parent::__construct();
        $this->novaPoshtaService = app(NovaPoshtaService::class);
    }

    /**
     * Execute the console command.
     */
    final public function handle(): bool
    {
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            $tenant->run(function () {
                return $this->novaPoshtaService->updateTrackingCodes();
            });
        }

        return 0;
    }
}
