<?php

abstract class AbstractClient implements Ds_DataLoader_DataLoaderClientInterface
{

}

abstract class AbstractDriver implements Ds_DataLoader_Driver_DriverInterface
{

}

/**
 * Description of AbstractDataLoaderTest
 *
 * @author pahhan
 */
class AbstractDataLoaderTest extends PHPUnit_Framework_TestCase
{
    public function testRegisterClientUndefinedKeyException()
    {
        $this->setExpectedException('Ds_DataLoader_DataLoaderException',
                'Undefined key in loader info');

        $client = $this->getMockForAbstractClass('AbstractClient');
        $client->expects($this->any())
               ->method('getLoaderInfo')
               ->will($this->returnValue(array()));

        $loader = $this->getMockBuilder('Ds_DataLoader_AbstractDataLoader')
                     ->disableOriginalConstructor()
                     ->getMockForAbstractClass();

        $loader->registerClient($client);
    }

    public function testLoadNotArrayException()
    {
        $this->setExpectedException('Ds_DataLoader_DataLoaderException',
                'getData() method must returns an array');


        $loader = $this->getMockBuilder('Ds_DataLoader_AbstractDataLoader')
                     ->disableOriginalConstructor()
                     ->getMockForAbstractClass();

        $loader->expects($this->any())
             ->method('getData')
             ->will($this->returnValue('foo'));

        $loader->load();
    }

    public function testClientOnGetData()
    {
        $client = $this->getMockForAbstractClass('AbstractClient');
        $client->expects($this->once())
               ->method('onDataReceived')
               ->with($this->equalTo(array('test'=>1)));

        $client->expects($this->any())
               ->method('getLoaderInfo')
               ->will($this->returnValue(array('key'=>'somekey')));

        $loader = $this->getMockBuilder('Ds_DataLoader_AbstractDataLoader')
                     ->disableOriginalConstructor()
                     ->getMockForAbstractClass();

        $loader->expects($this->any())
             ->method('getData')
             ->will($this->returnValue(array('somekey'=>array('test'=>1))));

        $loader->registerClient($client);
        $loader->load();
    }
}

