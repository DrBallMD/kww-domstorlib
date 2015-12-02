<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base64SerializeDataProvider
 *
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Base64SerializeDataProvider  implements Domstor_DataProviderInterface
{
    /**
     *
     * @var Domstor_UrlReaderInterface
     */
    private $urlReader;

    public function __construct(Domstor_UrlReaderInterface $urlReader)
    {
        $this->urlReader = $urlReader;
    }

    public function getData($url)
    {
        $content = $this->urlReader->read($url);

        $data = base64_decode($content);
        if ($data !== false) {
            $data = (array) unserialize($data);
        }
        
        return $data;
    }

}
