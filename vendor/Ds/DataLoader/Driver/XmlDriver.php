<?php

/**
 * Works with XML source
 *
 * @author pahhan
 */
class Ds_DataLoader_Driver_XmlDriver implements Ds_DataLoader_Driver_DriverInterface
{

    public function read($source)
    {
        try
        {
            $data = new SimpleXMLElement($source);
        }
        catch (Exception $e)
        {
            throw new Ds_DataLoader_Driver_DriverException($e->getMessage());
        }

        $data = $this->toArray($data);
        return $data;
    }

    private function toArray(SimpleXMLElement $element)
    {
        $out = array();
        foreach ($element as $key => $value)
        {
            if (strstr($key, 'node_'))
                $key = str_replace('node_', '', $key);


            if ($value->count() >= 1)
                $out[$key] = $this->toArray($value);
            else
                $out[$key] = (string) $value;
        }

        
        print_r($out);
        return $out;
    }

}
