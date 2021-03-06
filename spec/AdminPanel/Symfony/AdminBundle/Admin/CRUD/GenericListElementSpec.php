<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericListElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyList');
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericListElement');
    }

    function it_is_list_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    /**
     * @param \FSi\Component\DataGrid\DataGridFactory $factory
     * @throws \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid($factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface"))
            ->during('createDataGrid');
    }

    /**
     * @param \FSi\Component\DataSource\DataSourceFactory $factory
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource($factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface"))
            ->during('createDataSource');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'template_list' => null,
        ));
    }
}
