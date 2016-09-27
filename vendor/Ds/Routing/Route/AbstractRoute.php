<?php
/**
 * Description of AbstractRoute
 *
 * @author pahhan
 */
abstract class Ds_Routing_Route_AbstractRoute implements Ds_Routing_RouteInterface
{
    protected $path;
    protected $name;

    public function generateUri(array $params)
    {
        $out = $this->path;
        foreach($params as $name => $value)
        {
            $search = ':'.$name;
            if( strpos($this->path, $search) !== FALSE ) {
                $out = str_replace($search, $value, $out);
                unset($params[$name]);
            }
        }

        if( count($params) ) {
            $out.= '?'.http_build_query($params);
        }

        return $out;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getName()
    {
        return $this->name;
    }


}
