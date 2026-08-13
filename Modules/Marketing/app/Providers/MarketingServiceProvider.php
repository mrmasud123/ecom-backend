<?php

namespace Modules\Marketing\Providers;

use Modules\Marketing\Repositories\DiscountRepository;
use Modules\Marketing\Repositories\Interfaces\DiscountRepositoryInterface;
use Modules\Marketing\Services\DiscountService;
use Modules\Marketing\Services\Interfaces\DiscountServiceInterface;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class MarketingServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Marketing';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'marketing';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];
    public function register():void
    {
        parent::register();
        $this->app->bind(DiscountRepositoryInterface::class, DiscountRepository::class);
        $this->app->bind(DiscountServiceInterface::class, DiscountService::class);
    }
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
