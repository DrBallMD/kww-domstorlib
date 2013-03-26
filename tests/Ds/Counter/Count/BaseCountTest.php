<?php

/**
 * Description of BaseCountTest
 *
 * @author pahhan
 */
class BaseCountTest extends PHPUnit_Framework_TestCase
{
    public function testGetLoaderInfo()
    {
        $count = new Ds_Counter_Count_BaseCount('test', array('param1' => 1, 'param2' => 2));

        $compare = array(
            'key' => 'count.test',
            'data' => array(
                'count' => array(
                    'params' => array('param1' => 1, 'param2' => 2),
                ),
            )
        );

        $this->assertEquals($count->getLoaderInfo(), $compare);
    }

    public function testGet()
    {
        $count = new Ds_Counter_Count_BaseCount('test', array('test'=>0));

        $count->onDataReceived(array('count' => array('count'=>45)));

        $this->assertEquals($count->get(), 45);

    }
}

