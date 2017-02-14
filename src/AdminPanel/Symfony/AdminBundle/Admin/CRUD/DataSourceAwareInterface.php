<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Component\DataSource\DataSourceFactoryInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement instead.
 */
interface DataSourceAwareInterface
{
    /**
     * @param \AdminPanel\Component\DataSource\DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory);
}
