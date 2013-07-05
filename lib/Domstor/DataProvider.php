<?php

/**
 * Description of DataProvider
 *
 * @author pahhan
 */
class Domstor_DataProvider
{
    /**
     *
     * @var Doctrine_Cache_Driver
     */
    protected $cacheDriver;

    protected $cache_time;

    function __construct(Doctrine_Cache_Driver $cacheDriver, $cache_time) {
        $this->cacheDriver = $cacheDriver;
        $this->cache_time = $cache_time;
    }

    public function getData($url, $cache_time = NULL)
    {
        $id = $this->generateCacheId($url);
        if( $this->cacheDriver->contains($id) ) {
            $content = $this->cacheDriver->fetch($id);
        }
        else {
            $content = $this->readUrl($url);
            $this->cacheDriver->save($id, $content);
        }

        $data = $this->contentToData($content);
        return $data;
    }

    protected function generateCacheId($url, array $params = array())
    {
        return md5($url);
    }

    protected function readUrl($url)
    {
        return file_get_contents($url);
    }

    protected function contentToData($content)
    {
        $data = base64_decode($content);
        return unserialize($data);
    }
}

