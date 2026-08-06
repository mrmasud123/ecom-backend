<?php

namespace Modules\Product\Providers;

use Modules\Product\Repositories\AttributeRepository;
use Modules\Product\Repositories\BrandRepository;
use Modules\Product\Repositories\CategoryRepository;
use Modules\Product\Repositories\Interfaces\AttributeRepositoryInterface;
use Modules\Product\Repositories\Interfaces\BrandRepositoryInterface;
use Modules\Product\Repositories\Interfaces\CategoryRepositoryInterface;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;
use Modules\Product\Repositories\ProductRepository;
use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ProductServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Product';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'product';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
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

    public function register(): void
    {
        parent::register();
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(AttributeRepositoryInterface::class, AttributeRepository::class);
    }
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
