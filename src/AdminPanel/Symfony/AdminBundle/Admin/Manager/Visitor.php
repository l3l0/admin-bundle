<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Manager;

use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;

interface Visitor
{
    /**
     * @param ManagerInterface $manager
     */
    public function visitManager(ManagerInterface $manager);
}
