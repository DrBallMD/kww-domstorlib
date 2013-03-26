<?php

/**
 * Use curl
 *
 * @author pahhan
 */
class Ds_DataLoader_Reader_CurlReader implements Ds_DataLoader_Reader_ReaderInterface
{
    /**
     *
     * @param type $url
     * @param array $params
     * @return string
     * @throws Ds_DataLoader_Reader_ReaderException
     */
    public function read($url, array $params)
    {
        if( !function_exists('curl_init') )
            throw new Ds_DataLoader_Reader_ReaderException('Curl is not installed');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        $res = curl_exec($curl);
        
        if( !$res )
            throw new Ds_DataLoader_Reader_ReaderException(
                    sprintf ('Curl receive error %d:%s',
                            curl_errno ($curl),
                            curl_error ($curl)
                            )
                    );

       return $res;
    }
}