<?php

/**
 * Description of XmlDriverTest
 *
 * @author pahhan
 */
class XmlDriverTest extends PHPUnit_Framework_TestCase
{
    private $xml = '<?xml version="1.0" encoding="utf-8"?>
        <data>
            <node_0>
                <id>1247</id>
                <name>КГТ (Гостинка)</name>
            </node_0>
            <node_1>
                <id>1632</id>
                <name>Неблагоустроенная</name>
            </node_1>
            <node_14>
                <tests>
                    <a>Node a</a>
                    <b>Node b</b>
                </tests>
                <node_1>First</node_1>
                <node_2>Second</node_2>
            </node_14>
        </data>';

    private $error_xml = '<?xml version="1.0" encoding="utf-8"?>
<data><error><message>Error message</message></error></data>';

    private $error_array = array(
        'error' => array(
            'message' => 'Error message'
        )
    );

    private $array = array(
        0 => array(
            'id' => '1247',
            'name' => 'КГТ (Гостинка)'
        ),
        1 => array(
            'id' => '1632',
            'name' => 'Неблагоустроенная'
        ),
        14 => array(
            'tests' => array(
                'a' => 'Node a',
                'b' => 'Node b'
            ),
            1 => 'First',
            2 => 'Second'
        )
    );

    public function testRead()
    {
        $driver = new Ds_DataLoader_Driver_XmlDriver();
        $data = $driver->read($this->xml);
        $this->assertTrue($data === $this->array);
    }

    public function testReadError()
    {
        $driver = new Ds_DataLoader_Driver_XmlDriver();
        $data = $driver->read($this->error_xml);
        $this->assertTrue($data === $this->error_array);
    }

    /**
     * @expectedException Ds_DataLoader_Driver_DriverException
     */
    public function testReadException()
    {
        $driver = new Ds_DataLoader_Driver_XmlDriver();
        $driver->read('invalid xml');
    }
}

