<?php

namespace Modules\Inventory\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class InventoryServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Inventory';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'inventory';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    public function register(): void{
        parent::register();
        $this->app->bind(
            \Modules\Inventory\Repositories\Interfaces\ProductionBatchRepositoryInterface::class,
            \Modules\Inventory\Repositories\ProductionBatchRepository::class
        );

        $this->app->singleton(\Modules\Inventory\Services\StockService::class);
    }
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     *
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
