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
class TerraformManager extends AbstractManager
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
     * @param \Sdwru\Terraform\TerraformFactory $factory
     *
     * @return void
     */
    public function __construct(Repository $config, TerraformFactory $factory)
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
     * @return \Sdwru\Terraform\TerraformFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * Get the the api configuration.
     *
     * @param string|null $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getApiConfig()
    {
        return $this->getNamedConfig('api', 'API', 'url');
    }
    
    /**
     * Get the the ssl configuration.
     *
     * @param string|null $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getSslConfig()
    {
        return $this->getNamedConfig('api', 'SSL', 'ssl');
    }
    
    /**
     * Make the connection instance.
     *
     * @param string $name
     *
     * @return object
     */
    protected function makeConnection(string $name)
    {
        $config = $this->getConnectionConfig($name);
        $apiUrl = $this->getApiConfig();
        $ssl = $this->getSslConfig();
        $config['apiUrl'] = $apiUrl['base'];
        $config['sslVerify'] = $ssl['verify'];
        if (isset($this->extensions[$name])) {
            return $this->extensions[$name]($config);
        }

        if ($driver = Arr::get($config, 'driver')) {
            if (isset($this->extensions[$driver])) {
                return $this->extensions[$driver]($config);
            }
        }

        return $this->createConnection($config);
    }
}
