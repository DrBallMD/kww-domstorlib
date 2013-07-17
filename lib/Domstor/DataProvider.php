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
     * @var Doctrine_Cache_Interface
     */
    protected $cacheDriver;

    protected $cache_time;

    function __construct(Doctrine_Cache_Interface $cacheDriver, $cache_time)
    {
        $this->cacheDriver = $cacheDriver;
        $this->cache_time = $cache_time;
    }

    public function getData($url, $cache_time = NULL)
    {
        if( !$cache_time ) $cache_time = $this->cache_time;
        $id = $this->generateCacheId($url);
        if( $this->cacheDriver->contains($id) ) {
            $content = $this->cacheDriver->fetch($id);
        }
        else {
            $content = $this->readUrl($url);
            $this->cacheDriver->save($id, $content, $cache_time);
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
        $data = json_decode($content);
        if( $error = json_last_error() ) {
            throw new Domstor_JsonException($error);
        }
        return $data;
    }
}

