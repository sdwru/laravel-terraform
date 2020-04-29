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

/**
 * This is the terraform factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class TerraformFactory
{
    /**
     * The adapter factory instance.
     *
     * @var \Sdwru\Terraform\Adapter\ConnectionFactory
     */
    protected $adapter;

    /**
     * Create a new filesystem factory instance.
     *
     * @param \Sdwru\Terraform\Adapter\ConnectionFactory $adapter
     *
     * @return void
     */
    public function __construct(AdapterFactory $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Make a new terraform client.
     *
     * @param string[] $config
     *
     * @return \TerraformV2\TerraformV2
     */
    public function make(array $config)
    {
        $adapter = $this->createAdapter($config);
        $baseApiUrl = $config['apiUrl'];

        return new TerraformV2($adapter, $baseApiUrl);
    }

    /**
     * Establish an adapter connection.
     *
     * @param array $config
     *
     * @return \TerraformV2\Adapter\AdapterInterface
     */
    public function createAdapter(array $config)
    {
        return $this->adapter->make($config);
    }

    /**
     * Get the adapter factory instance.
     *
     * @return \Sdwru\Terraform\Adapter\ConnectionFactory
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
