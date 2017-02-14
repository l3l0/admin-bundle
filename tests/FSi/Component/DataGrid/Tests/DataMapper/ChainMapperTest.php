<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\DataMapper;

use FSi\Component\DataGrid\DataMapper\ChainMapper;
use FSi\Component\DataGrid\Exception\DataMappingException;

class ChainMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappersInChainWithInvalidMappers()
    {
        $this->expectException('InvalidArgumentException');
        $chain = new ChainMapper([
            'foo',
            'bar'
        ]);
    }

    public function testMappersInChainWithEmptyMappersArray()
    {
        $this->expectException('InvalidArgumentException');
        $chain = new ChainMapper([
            'foo',
            'bar'
        ]);
    }

    public function testGetDataFromTwoMappers()
    {
        $mapper = $this->createMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $mapper1 = $this->createMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');

        $mapper->expects($this->once())
               ->method('getData')
               ->will($this->throwException(new DataMappingException));

        $mapper1->expects($this->once())
               ->method('getData')
               ->will($this->returnValue('foo'));

        $chain = new ChainMapper([$mapper, $mapper1]);

        $this->assertSame(
            'foo',
            $chain->getData('foo', 'bar')
        );
    }

    public function testSetDataWithTwoMappers()
    {
        $mapper = $this->createMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $mapper1 = $this->createMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');

        $mapper->expects($this->once())
               ->method('setData')
               ->will($this->throwException(new DataMappingException));

        $mapper1->expects($this->once())
               ->method('setData')
               ->with('foo', 'bar', 'test')
               ->will($this->returnValue(true));

        $chain = new ChainMapper([$mapper, $mapper1]);

        $this->assertTrue($chain->setData('foo', 'bar', 'test'));
    }
}
