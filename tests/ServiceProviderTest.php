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

namespace GrahamCampbell\Tests\DigitalOcean;

use TerraformV2\TerraformV2;
use Sdwru\Terraform\Adapter\ConnectionFactory as AdapterFactory;
use Sdwru\Terraform\DigitalOceanFactory;
use Sdwru\Terraform\DigitalOceanManager;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAdapterFactoryIsInjectable()
    {
        $this->assertIsInjectable(AdapterFactory::class);
    }

    public function testDigitalOceanFactoryIsInjectable()
    {
        $this->assertIsInjectable(DigitalOceanFactory::class);
    }

    public function testDigitalOceanManagerIsInjectable()
    {
        $this->assertIsInjectable(DigitalOceanManager::class);
    }

    public function testBindings()
    {
        $this->assertIsInjectable(TerraformV2::class);

        $original = $this->app['terraform.connection'];
        $this->app['terraform']->reconnect();
        $new = $this->app['terraform.connection'];

        $this->assertNotSame($original, $new);
        $this->assertEquals($original, $new);
    }
}
