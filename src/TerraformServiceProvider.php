<?php

declare(strict_types=1);

/*
 * This file is part of Laravel DigitalOcean.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sdwru\Terraform;

use TerraformV2\TerraformV2;
use Sdwru\Terraform\Adapter\ConnectionFactory as AdapterFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the terraform service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DigitalOceanServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath($raw = __DIR__.'/../config/terraform.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('terraform.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('terraform');
        }

        $this->mergeConfigFrom($source, 'terraform');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAdapterFactory();
        $this->registerDigitalOceanFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    /**
     * Register the adapter factory class.
     *
     * @return void
     */
    protected function registerAdapterFactory()
    {
        $this->app->singleton('terraform.adapterfactory', function () {
            return new AdapterFactory();
        });

        $this->app->alias('terraform.adapterfactory', AdapterFactory::class);
    }

    /**
     * Register the terraform factory class.
     *
     * @return void
     */
    protected function registerDigitalOceanFactory()
    {
        $this->app->singleton('terraform.factory', function (Container $app) {
            $adapter = $app['terraform.adapterfactory'];

            return new DigitalOceanFactory($adapter);
        });

        $this->app->alias('terraform.factory', DigitalOceanFactory::class);
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('terraform', function (Container $app) {
            $config = $app['config'];
            $factory = $app['terraform.factory'];

            return new DigitalOceanManager($config, $factory);
        });

        $this->app->alias('terraform', DigitalOceanManager::class);
    }

    /**
     * Register the bindings.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind('terraform.connection', function (Container $app) {
            $manager = $app['terraform'];

            return $manager->connection();
        });

        $this->app->alias('terraform.connection', TerraformV2::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'terraform.adapterfactory',
            'terraform.factory',
            'terraform',
            'terraform.connection',
        ];
    }
}
