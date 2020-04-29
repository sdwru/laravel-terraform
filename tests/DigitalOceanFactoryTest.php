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

use TerraformV2\Adapter\AdapterInterface;
use TerraformV2\TerraformV2;
use Sdwru\Terraform\Adapter\ConnectionFactory;
use Sdwru\Terraform\DigitalOceanFactory;
use Sdwru\Terraform\DigitalOceanManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Mockery;

/**
 * This is the terraform factory test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DigitalOceanFactoryTest extends AbstractTestBenchTestCase
{
    public function testMake()
    {
        $config = ['driver' => 'buzz', 'token'  => 'your-token'];

        $manager = Mockery::mock(DigitalOceanManager::class);

        $factory = $this->getMockedFactory($config, $manager);

        $return = $factory->make($config, $manager);

        $this->assertInstanceOf(TerraformV2::class, $return);
    }

    public function testAdapter()
    {
        $factory = $this->getDigitalOceanFactory();

        $config = ['driver' => 'guzzlehttp', 'token'  => 'your-token'];

        $factory->getAdapter()->shouldReceive('make')->once()
            ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));

        $return = $factory->createAdapter($config);

        $this->assertInstanceOf(AdapterInterface::class, $return);
    }

    protected function getDigitalOceanFactory()
    {
        $adapter = Mockery::mock(ConnectionFactory::class);

        return new DigitalOceanFactory($adapter);
    }

    protected function getMockedFactory($config, $manager)
    {
        $adapter = Mockery::mock(ConnectionFactory::class);

        $mock = Mockery::mock(DigitalOceanFactory::class.'[createAdapter]', [$adapter]);

        $mock->shouldReceive('createAdapter')->once()
            ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));

        return $mock;
    }
}
