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

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

/**
 * This is the terraform manager class.
 *
 * @method \TerraformV2\TerraformV2 connection(string|null $name)
 * @method \TerraformV2\TerraformV2 reconnect(string|null $name)
 * @method array<string,\TerraformV2\TerraformV2> getConnections(string $name)
 * @method \TerraformV2\Api\Action action()
 * @method \TerraformV2\Api\Image image()
 * @method \TerraformV2\Api\Domain domain()
 * @method \TerraformV2\Api\DomainRecord domainRecord()
 * @method \TerraformV2\Api\Size size()
 * @method \TerraformV2\Api\Region region()
 * @method \TerraformV2\Api\Key key()
 * @method \TerraformV2\Api\Droplet droplet()
 * @method \TerraformV2\Api\RateLimit rateLimit()
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DigitalOceanManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \Sdwru\Terraform\DigitalOceanFactory
     */
    protected $factory;

    /**
     * Create a new terraform manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository          $config
     * @param \Sdwru\Terraform\DigitalOceanFactory $factory
     *
     * @return void
     */
    public function __construct(Repository $config, DigitalOceanFactory $factory)
    {
        parent::__construct($config);
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return \TerraformV2\TerraformV2
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'terraform';
    }

    /**
     * Get the factory instance.
     *
     * @return \Sdwru\Terraform\DigitalOceanFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
