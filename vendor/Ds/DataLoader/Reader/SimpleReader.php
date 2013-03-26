<?php

/**
 * Use file_get_contents
 *
 * @author pahhan
 */
class Ds_DataLoader_Reader_SimpleReader implements Ds_DataLoader_Reader_ReaderInterface
{
    /**
     *
     * @param string $url
     * @param array $params
     * @return string
     * @throws Ds_DataLoader_Reader_ReaderException
     */
    public function read($url, array $params)
    {
        $new_url = $url.'?'.http_build_query($params);
        $res =  @file_get_contents($new_url);
        if( $res === FALSE or is_null($res) )
            throw new Ds_DataLoader_Reader_ReaderException(
                    sprintf('Can\'t get contents from: %s', $new_url));

        return $res;
    }
}

