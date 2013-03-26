<?php

abstract class AbstractDriver implements Ds_DataLoader_Driver_DriverInterface
{

}

class DataLoaderMock extends Ds_DataLoader_BaseDataLoader
{
    protected $server = 'http://test.server';

    public function getFullUrl($name, $data) {
        return 'http://test.server/api';
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function readUrl($url)
    {
        return '<?xml version="1.0" encoding="utf-8"?>
        <data>
            <node_0>
                <id>1</id>
                <name>Элемент 1</name>
            </node_0>
            <node_1>
                <id>2</id>
                <name>Элемент 2</name>
            </node_1>
        </data>';
    }

    public function getData()
    {
        return parent::getData();
    }
}

class DataLoaderClientMock implements Ds_DataLoader_DataLoaderClientInterface
{
    private $key;
    public function __construct($key) {
        $this->key = $key;
    }

    public function getLoaderInfo() {
        return array(
            'key' => $this->key,
            'data' => array(
                'types' => array(
                    'params' => array(
                        'object' => 'flat',
                        'ref_city' => 2004,
                    )
                ),
                'states' => array(
                    'params' => array(
                        'ref_city' => 2004,
                    )
                ),
            )
        );
    }

    public function onDataReceived(array $data) {

    }
}

/**
 * Description of BaseDataLoaderTest
 *
 * @author pahhan
 */
class BaseDataLoaderTest extends PHPUnit_Framework_TestCase
{
    private function getLoaderInfo()
    {
        $info =  array(
            'key' => 'form.builder.flat',
            'data' => array(
                'types' => array(
                    'params' => array(
                        'object' => 'flat',
                        'ref_city' => 2004,
                    )
                ),
                'states' => array(
                    'params' => array(
                        'ref_city' => 2004,
                    )
                ),
            )
        );
        return $info;
    }

    public function testGetData()
    {
        $driver = new Ds_DataLoader_Driver_XmlDriver();
        $loader = new DataLoaderMock($driver);
        $client1 = new DataLoaderClientMock('client1');
        $client2 = new DataLoaderClientMock('client2');

        $loader->registerClient($client1);
        $loader->registerClient($client2);

        $data = $loader->getData();
        $compare = array(
            'client1' => array(
                'types' => array(
                    0 => array(
                        'id' => '1',
                        'name' => 'Элемент 1'
                    ),
                    1 => array(
                        'id' => '2',
                        'name' => 'Элемент 2'
                    ),
                ),
                'states' => array(
                    0 => array(
                        'id' => '1',
                        'name' => 'Элемент 1'
                    ),
                    1 => array(
                        'id' => '2',
                        'name' => 'Элемент 2'
                    ),
                ),
            ),
            'client2' => array(
                'types' => array(
                    0 => array(
                        'id' => '1',
                        'name' => 'Элемент 1'
                    ),
                    1 => array(
                        'id' => '2',
                        'name' => 'Элемент 2'
                    ),
                ),
                'states' => array(
                    0 => array(
                        'id' => '1',
                        'name' => 'Элемент 1'
                    ),
                    1 => array(
                        'id' => '2',
                        'name' => 'Элемент 2'
                    ),
                ),
            ),
        );

        $this->assertTrue($data === $compare, 'Result not equal to test');
    }

    /**
     * @expectedException Ds_DataLoader_DataLoaderException
     */
    public function testGetDataException()
    {

        // Create a stub for the SomeClass class.
        $driver = $this->getMock('Ds_DataLoader_Driver_XmlDriver');
        $driver->expects($this->any())
            ->method('read')
            ->will($this->returnValue(array('error'=>array('message'=>'Error'))));

        $loader = new DataLoaderMock($driver);
        $client1 = new DataLoaderClientMock('client1');
        $loader->registerClient($client1);


         $loader->getData();
    }
}

