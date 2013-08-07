<?php
/**
 * Author: Pavel Stepanets
 * Email:  pahhan.ne@gmail.com
 */
interface iDomstorDataPump
{
	public function getData($url);
}

class DomstorPump implements iDomstorDataPump
{
	protected function _getContent($url)
	{
		return @file_get_contents($url);
	}

	public function getData($url)
	{
		$out = array();
		$data = $this->_getContent($url);

		if( $data )
		{
			$data=base64_decode($data);
			if ($data !== false)
			{
				$out = (array)unserialize($data);
			}
		}
		return $out;
	}
}

class Domstor
{
	protected $object;
	protected $action;
	protected $server_name = 'domstor.ru';
	protected $api_path = '/gateway';
	protected $sort_client;
	protected $pager;
	protected $filter;
	protected $filter_tmpl_dir;
	protected $params = array(); // ��������� ������ (�������, ����������  � �.�.)
	protected $my_id = 1; // ������������� �����������
	protected $pagination_count = 15; // ���������� ������ �� ��������
	protected $href_tmpls = array(
		'list' => '?object=%object&action=%action&page=%page%sort%filter',
		'object' => '?object=%object&action=%action&id=%id%sort%filter',
		'page_part' => '',
		'flat_purchase' => '?object=flat&action=purchase&id=%id',
		'house_purchase' => '?object=house&action=purchase&id=%id',
		'commerce_sale' => '?object=commerce&action=sale&id=%id',
		'complex_sale' => '?object=complex&action=sale&id=%id',
	); // ������� ������
	protected $in_region = FALSE; // ���� ���������� ����������� ��������� �������� ������
	protected $empty_list_message = '<p class="domstor_not_found">������� � ������� ����������� �� �������</p>';
	protected $home_location;
	protected $filter_data_loader_config;
    /**
     *
     * @var DomstorSiteMapGenerator
     */
    protected $site_map_generator;

    /**
     *
     * @var Doctrine_Cache_Interface
     */
    protected $cache_driver;

    protected $cache_time = 0;

    /**
     *
     * @var Domstor_DataProvider
     */
    protected $data_provider;

    public static function checkObjectAction($object, $action)
    {
        return Domstor_Helper::checkEstateAction($object, $action);
    }

    public function __construct()
	{
		$this->sort_client = new Domstor_SortClient;
		$this->pager = new SP_Helper_Pager;
		$this->filter_data_loader_config = new Domstor_Filter_DataLoaderConfig;
        $this->data_provider = new Domstor_DataProvider(new Doctrine_Cache_Array(), 3000);
	}

    /**
     *
     * @return type DomstorSiteMapGenerator
     */
    public function getSiteMapGenerator()
    {
        if( !$this->site_map_generator )
            $this->site_map_generator = new DomstorSiteMapGenerator($this->getHrefTemplate('object'));

        return $this->site_map_generator;
    }

	// ��������� �������� �� �������� ������� � ������ ����������
	protected function _addParamsFromRequest($params, $request_array, $keys)
	{
		foreach( $keys as $key => $value )
		{
			if( isset($request_array[$key]) ) $params[$value] = $request_array[$key];
		}
		return $params;
	}

	// �������� url �� ����������
	protected function _getUrlPartsFromRequest($request_array, $keys = array())
	{
		$out = '';
		$keys = array('ref_city', 'inreg');
		foreach( $keys as $key )
		{
			if( isset($request_array[$key]) )
			{
				$out.= '&'.$key;
				if( $request_array[$key] !== '') $out.= '='.$request_array[$key];
			}
		}
		return $out;
	}

	// �������������� ��������� ������� � �������
	protected function _prepareRequestParams(array $params)
	{
		// ��������� id ����������� - ������������ �������� ��� ���� ��������
		$params['aid'] = $this->my_id;
		$params = $this->_addParamsFromRequest($params, $_GET, array('ref_city'=>'ref_city'));
		$params = array_merge($this->params, $params);
		// ���� � ������� ���� ������� ������, �� ����� ������ ref_city �� �������, ����� ������� � ��� �� ��������

		if( $this->getFilterDataLoaderConfig()->subregionsWithBig()
			and $this->hasParam('ref_city', $params)
			and $this->filter
			and $this->filter->getField('district')->getValue()	)
		{
			unset($params['ref_city']);
		}
		return $params;
	}

	// ��������� url ������� � ������� ��� ������
	protected function _getListRequest(array $params)
	{
		// �������� ������ ���������� �������
		$params = $this->_prepareRequestParams($params);

		// ��������� ��������� ����������� ��� ������
		$params['limit'] = $this->getPager()->get('on_page');
        if( isset($params['_no_limit_']) )
        {
            unset($params['limit'], $params['_no_limit_']);
        }

		$url = 'http://'.$this->server_name.$this->api_path.'/list/?'.http_build_query($params).$this->sort_client->getRequestString();

		if( $this->filter ) $url.= $this->filter->getServerRequestString();

		return $url;
	}

    // ��������� url ������� � ������� ��� ������
	protected function _getSitemapRequest(array $params)
	{
		// �������� ������ ���������� �������
		$params = $this->_prepareRequestParams($params);

		$url = 'http://'.$this->server_name.$this->api_path.'/site-map/?'.http_build_query($params);

		if( $this->filter ) $url.= $this->filter->getServerRequestString();

		return $url;
	}

	// ��������� url ������� � ������� ��� ����������
	protected function _getCountRequest(array $params)
	{
		// �������� ������ ���������� �������
		$params = $this->_prepareRequestParams($params);

		$url = 'http://'.$this->server_name.$this->api_path.'/count/?'.http_build_query($params);
		if( $this->filter ) $url.= $this->filter->getServerRequestString();

		return $url;
	}

	// ��������� url ������� � ������� ��� �������
	protected function _getObjectRequest(array $params)
	{
		// �������� ������ ���������� �������
		$params = $this->_prepareRequestParams($params);
		$url = 'http://'.$this->server_name.$this->api_path.'/object/?'.http_build_query($params).$this->sort_client->getRequestString();
		if( $this->filter ) $url.= $this->filter->getServerRequestString();

		//echo $url;
		return $url;
	}

	// ��������� url ������� � ������� ��� ������ ������ �������
	protected function _getLocationsRequest(array $params)
	{
		// �������� ������ ���������� �������
		$params = $this->_prepareRequestParams($params);
		$this_params = array_merge($params, $this->params);
		unset($this_params['ref_city']);
		$url = 'http://'.$this->server_name.$this->api_path.'/org/locations/'.$this->my_id.'/'.$params['object'].'/'.$params['action'].'?'.http_build_query($this_params);
		return $url;
	}

	// �������������� ��������� ��� ������� ������
	protected function _prepareListParams(array $params)
	{
		$list_params = array();
		$list_params['in_region'] = $this->_isInRegion();
		$list_params['server_name'] = $this->server_name;
		$list_params['object_href'] = $this->_processObjectHref($params);
		$list_params['action'] = $params['action'];
		$list_params['empty_list_message'] = $this->empty_list_message;
		$list_params['sort'] = array(
			'uri' => $this->_processSortHref($params),
			'uri_part'=>'&%name%=%desc%',
			'input'=>$_GET,
		);
        $list_params['exchange_flat_href'] = $this->getHrefTEmplate('flat_purchase');
		$list_params['exchange_house_href'] = $this->getHrefTEmplate('house_purchase');
		return $list_params;
	}

	// �������������� ��������� ��� ������� �������
	protected function _prepareObjectParams(array $params)
	{
		$object_params = array();
		$object_params['in_region'] = $this->_isInRegion();
		$object_params['server_name'] = $this->server_name;
		$object_params['object_href'] = $this->_processObjectHref($params);
		$object_params['_action'] = $params['action'];
		$object_params['action'] = ($params['action']=='rent' or $params['action']=='rentuse')? 'rent' : 'sale';
		$object_params['exchange_flat_href'] = $this->getHrefTEmplate('flat_purchase');
		$object_params['exchange_house_href'] = $this->getHrefTEmplate('house_purchase');
		$object_params['commerce_href'] = $this->getHrefTEmplate('commerce_sale');
		return $object_params;
	}

	// �������� ����� %filter � ������ �� ������� �������� �������
	protected function _replaceFilterHref($href_tmpl)
	{
		if( $this->filter ) return $this->filter->replaceString('%filter', $href_tmpl);
		return str_replace('%filter', '', $href_tmpl);
	}

	// �������� ����� %sort � ������ �� ������� �������� �������
	protected function _replaceSortHref($href_tmpl)
	{
		if( strpos($href_tmpl, '%sort') !== FALSE )
		{
			$data = $_GET;
			$out = '';
			if( is_array($data) )
			{
				foreach($data as $key => $value)
				{
					if( strpos($key, 'sort-')===0 ) $out.= '&'.$key.'='.$value;
				}
			}
			return str_replace('%sort', $out, $href_tmpl);
		}
		else
		{
			return $href_tmpl;
		}
	}

	// ������������ ������������ ������ ������
	protected function _replaceObjectAction(array $params, $href_tmpl)
	{
		$keys[] = '%object';
		$values[] = $params['object'];
		$keys[] = '%action';
		$values[] = $params['action'];
		return str_replace($keys, $values, $href_tmpl);
	}

	// ��������� ������ �� ������
	protected function _processObjectHref(array $params)
	{
		$href = $this->_replaceObjectAction($params, $this->getHrefTemplate('object'));
		$href = $this->_replaceFilterHref($href);
		$href = $this->_replaceSortHref($href);
		$href = $href.$this->_getUrlPartsFromRequest($_GET);
		return $href;
	}

	// ��������� ������ �� �������� ������
	protected function _processListHref(array $params)
	{
		$href = $this->_replaceObjectAction($params, $this->getHrefTemplate('list'));
		$href = $this->_replaceFilterHref($href);
		$href = $this->_replaceSortHref($href);
		$href = $href.$this->_getUrlPartsFromRequest($_GET);
		//$href = str_replace('%page', $params['page'], $href);
		return $href;
	}

	// ��������� ������ �� �������� ������� ������ �������
	protected function _processLocationsHref(array $params)
	{
		$href = $this->_replaceObjectAction($params, $this->getHrefTemplate('list'));
		$href = str_replace(array('%filter','%sort','%page'), array('','','1'), $href);
		$href.= '&ref_city=%id';
		return $href;
	}

	// ��������� ������ ��� ���������� ������
	protected function _processSortHref(array $params)
	{
		$href = $this->_replaceObjectAction($params, $this->getHrefTemplate('list'));
		$href = $this->_replaceFilterHref($href);
		$href = str_replace('%page', isset($params['page'])? $params['page'] : '', $href);
		$href = $href.$this->_getUrlPartsFromRequest($_GET);
		return $href;
	}

    protected function _getLocationInfo($location_id)
    {
		$data = $this->read('/gateway/location/info/'.$location_id);
		return $data;
    }

    public function setCacheDriver(Doctrine_Cache_Interface $cache_driver) {
        $this->cache_driver = $cache_driver;
    }

    public function setCacheTime($cache_time) {
        $this->cache_time = $cache_time;
    }

    public function createFilter($object, $action, array $filter_factory_params = array())
	{
		$filter_factory = new Domstor_Filter_FilterFactory;
		if( !isset($filter_factory_params['filter_dir']) ) $filter_factory_params['filter_dir'] = $this->filter_tmpl_dir;
		$filter_factory_params['domstor'] = $this;
		$this->filter = $filter_factory->create($object, $action, $filter_factory_params);
		return $this->filter;
	}

	/**
     *
     * @param string $object
     * @param string $action
     * @param integer $page
     * @param array $params
     * @return boolean|Domstor_List_Common
     */
    public function getList($object, $action, $page, array $params = array())
	{
		// ����������� $object, $action � $page � ���������
		$params['object'] = $object;
		$params['action'] = $action;
		$params['page'] = $page? $page : 1;

		$filter = $this->createFilter($object, $action);
		if( $filter )
		{
			$filter->bindFromRequest();
		}

		// �������� url ������� �� ������ ����������
		$url = $this->_getListRequest($params);

		// �������� ������
		$data = $this->_getData($url);

		// ��������� ������� - ����� ����� ��������
		$total = array_pop($data);

		// ������� ������� �������
		$factory = new Domstor_List_ListFactory;

		// �������� ��������� ��� ������
		$list_params = $this->_prepareListParams($params);
		$list_params['data'] = $data;

		// ������� ������� ������
		$list = $factory->create($object, $action, $list_params);

		if( !$list ) return FALSE;

		// ������� ������ pager ������������� ������
		$this->pager->init(array(
			'total' => $total,
			'pager_count' => $this->pagination_count,
			'href_tmpl'=>$this->_processListHref($params),
			'href_page_part' => $this->getHrefTemplate('page_part'),
			'link_tmpl' => '<a class="domstor_pagination_link" href="%href">%text</a> ',
			'layout_tmpl'=>'<div class="domstor_pagination"><p>%info%text</p></div>',
			'current_page_tmpl' => '<span class="domstor_pagination_selected">%text</span> ',
		));

		// ��������� html-��� pagera � ������
		$list->setPagination($this->getPager()->display($params['page'], array(), TRUE));
		$list->setFilter($filter);

		return $list;
	}

    public function getObject($object, $action, $id, array $params = array())
    {
        return $this->getDetail($object, $action, $id, $params);
    }

	/**
     *
     * @param string $object
     * @param string $action
     * @param string $id
     * @param array $params
     * @return false|Domstor_Detail_Supply
     */
    public function getDetail($object, $action, $id, array $params = array())
	{
        $params['object'] = $object;
		$params['action'] = $action;
		$params['oid'] = $id;

		$filter = $this->createFilter($object, $action);
		if( $filter )
		{
			$filter->bindFromRequest();
		}

		// �������� url ������� �� ������ ����������
		$url = $this->_getObjectRequest($params);

		// �������� ������
		$data = $this->_getData($url);
        if( !isset($data['id']) ) return NULL;

		// ������� ������� ��������
		$factory = new Domstor_Detail_DetailFactory;

		// �������� ��������� ��� ������
		$object_params = $this->_prepareObjectParams($params);
		//$object_params['object'] = $data;

		// ������� ������� ������
		$obj = $factory->create($object, $action, $object_params);
        $obj->setData($data);
		return $obj;
	}

	public function getCount($object, $action, $params = array())
	{
		$params['object'] = $object;
		$params['action'] = $action;
		$url = $this->_getCountRequest($params);
        $cache_time = isset($params['cache'])? $params['cache'] : NULL;
		$data = $this->_getData($url, $cache_time);
		return $data[0];
	}

    public function generateSiteMap($object, $action, array $params = array())
    {
       // ����������� $object, $action � $page � ���������
		$params['object'] = $object;
		$params['action'] = $action;

//		$filter = $this->createFilter($object, $action);
//		if( $filter )
//		{
//			$filter->bindFromRequest();
//		}

		// �������� url ������� �� ������ ����������
		$url = $this->_getSitemapRequest($params);
        //echo $url, '<br>';

		// �������� ������
		$data = $this->data_provider->getData($url);

        //print_r($data);

        $this->getSiteMapGenerator()->setData($data)->setRequestUrl($url);
        $this->getSiteMapGenerator()->generate();
    }

	public function getLocationsList($object, $action, array $params = array())
	{
		//if( !$this->home_location ) return FALSE;
		$params['object'] = $object;
		$params['action'] = $action;
		$params['location'] = $this->home_location;
		$url = $this->_getLocationsRequest($params);
		$data = $this->_getData($url);
		$current_id = $this->getRealParam('ref_city');
		unset($data[$current_id]);
		$tmpl = $this->_processLocationsHref($params);
		$list = new Domstor_LocationsList($data, $tmpl, $this->home_location);
		return $list;
	}

	public function displayLocationsList($object, $action, $prefix = NULL, array $params = array())
	{
		if( $list = $this->getLocationsList($object, $action, $params) )
		{
			echo $list->display($prefix);
		}
	}

	public function getLocationName($pad = 'im')
	{
		$id = isset($_GET['ref_city'])? $_GET['ref_city'] : $this->home_location;
		$data = $this->read('/gateway/location/name/'.$id.'/'.$pad, false);
		return $data[0];
	}

	public function displayFilter($object, $action, $return = FALSE)
	{
		if( is_null($this->filter) ) $this->createFilter($object, $action);
		if( $this->filter )
		{
			if( $return ) return $this->filter->render();
			$this->filter->display();
		}
	}

	public function setMyId($value)
	{
		$this->my_id = (int) $value;
		return $this;
	}

	public function getMyId()
	{
		return $this->my_id;
	}

	public function setFilterTmplDir($value)
	{
		$this->filter_tmpl_dir = $value;
		return $this;
	}

	public function getFilterTmplDir()
	{
		return $this->filter_tmpl_dir;
	}

	public function setHomeLocation($value)
	{
		if( is_null($value) )
		{
			$this->home_location = null;
			$this->deleteParam('ref_city');
		}
		else
		{
			$this->home_location = (integer)$value;
			$this->addParam('ref_city', $this->home_location);
		}
	}

	public function setServerName($value)
	{
		$this->server_name = $value;
		return $this;
	}

	public function getServerName()
	{
		return $this->server_name;
	}

	public function getFilter($object = null, $action = null)
	{
		if( !$this->filter )
		{
			if( $object and $action )
			{
				$this->createFilter($object, $action);
			}
		}
		return $this->filter;
	}

	public function setParams(array $value)
	{
		$this->params = $value;
		return $this;
	}

	public function deleteParam($name)
	{
		if( $this->hasParam($name) ) unset($this->params[$name]);
	}

	public function addParams(array $value)
	{
		$this->params = array_merge($this->params, $value);
		return $this;
	}

	public function addParam($name, $value)
	{
		$this->params[$name] = $value;
		return $this;
	}

	public function hasParam($name, array $params = array())
	{
		if( !count($params) ) $params = &$this->params;
		return array_key_exists($name, $params);
	}

	public function getParam($name)
	{
		if( $this->hasParam($name) ) return $this->params[$name];
	}

	public function getParams()
	{
		return $this->params;
	}

	public function clearParams()
	{
		$this->params = array();
		return $this;
	}

	public function getRealParam($name)
	{
		if( isset($_GET[$name]) ) return $_GET[$name];
		return $this->getParam($name);
	}

	public function setDefaultSort(array $sort)
	{
		$this->getSortClient()->setDefault($sort);
		return $this;
	}

	public function clearDefaultSort()
	{
		$this->setDefaultSort(array());
		return $this;
	}

	public function setExposition($days)
	{
		if( $days == 0 )
		{
			unset($this->params['edit_dt']);
			return $this;
		}

		$seconds = strtotime(date('Y-m-d')) - $days * 86400;
		$this->addParam('edit_dt', array('min'=>date('Y-m-d', $seconds)));
		return $this;
	}

	public function read($uri)
	{
        return $this->_getData('http://'.$this->getServerName().$uri);
	}

	protected function _getData($url, $cache = NULL)
	{
        // ������� ��������� ����������
		$pump = new DomstorPump;
		// �������� ������

        $id = md5($url);
        //echo $url,PHP_EOL;
        if( is_null($cache) ) $cache = $this->cache_time;

        if( $this->cache_driver and $cache) {
            if( $this->cache_driver->contains($id) ) {
                $result = $this->cache_driver->fetch($id);
                return unserialize($result);
            }

            $data = $pump->getData($url);
            $this->cache_driver->save($id, serialize($data), $cache);
            return $data;
        }

		$data = $pump->getData($url);

        return $data;
	}

	public function getSortClient()
	{
		return $this->sort_client;
	}

	public function getPager()
	{
		return $this->pager;
	}

	public function setHrefTemplate($name, $value)
	{
		$this->href_tmpls[$name] = $value;
		return $this;
	}

	public function getHrefTemplate($name)
	{
		return $this->href_tmpls[$name];
	}

	public function setEmptyListMessage($value)
	{
		$this->empty_list_message = $value;
	}

	protected function _isInRegion()
	{
		if( isset($_GET['inreg']) ) return TRUE;
        $ref_city_param = $this->getRealParam('ref_city');
        $loc_id = $ref_city_param? $ref_city_param : $this->home_location;
        if( !$loc_id ) return $this->in_region;
        $info = $this->_getLocationInfo($loc_id);
        if( isset($info['type']) ) {
            return $info['type'] == '2';
        }
	}

	public function inRegion($value = NULL)
	{
		if( is_null($value) ) return $this->_isInRegion();

		$this->in_region = (bool) $value;
		return $this;
	}

	public function check($object, $action)
	{
		return Domstor_Helper::checkEstateAction($object, $action);
	}

	public function setPaginationOnPage($value)
	{
		$value = (int) $value;
		if( $value > 0 ) $this->getPager()->set('on_page', $value);
	}

    public function setPaginationCount($pagination_count)
    {
        $this->pagination_count = $pagination_count;
    }

	public function getListLink($object, $action)
	{
		$page = $this->loadPageNumber();
		$link = $this->_processListHref(array('object'=>$object, 'action'=>$action));
		$link = str_replace('%page', $page, $link);
		return $link;
	}

	public function savePageNumber($val)
	{
		if( !isset($_SESSION) )
		{
			$started = @session_start();
			if( !$started ) return $this;
		}
		$_SESSION['domstor_from_page'] = $val;
		return $this;
	}

	public function loadPageNumber()
	{
		if( !isset($_SESSION) ) session_start();
		$page = isset($_SESSION['domstor_from_page'])? (int) $_SESSION['domstor_from_page'] : 1;
		return $page;
	}

	public function getFilterDataLoaderConfig()
	{
		return $this->filter_data_loader_config;
	}
}

class DomstorSiteMapGenerator
{
    /**
     * XMLWriter
     * @var XMLWriter
     */
    protected $_xml_writer;

    /**
     * Cache time in seconds
     * @var integer
     */
    protected $_cache_time = 86400;

    /**
     * Cache driver for xml caching
     * @var SP_Cache_Interface
     */
    protected $_cache_driver;

    /**
     * Site hostname
     * @var string
     */
    protected $_host = '';

    /**
     * Data for map generation
     * @var array
     */
    protected $_data;

    /**
     * Link priority in sitemap
     * @var float
     */
    protected $_priority = 0.8;

    /**
     * Sitemap update period
     * @var string
     */
    protected $_period = 'weekly';

    /**
     * For generating cache key
     * @var string
     */
    protected $_request_url;

    protected $_object_href;

    function __construct($_object_href) {
        $this->_object_href = $_object_href;
    }

    /**
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    public function setHost($host)
    {
        $this->_host = $host;
        return $this;
    }

    public function setXmlWriter($xml_writer)
    {
        $this->_xml_writer = $xml_writer;
        return $this;
    }

    public function getXmlWriter()
    {
        if( is_null($this->_xml_writer) )
            $this->_xml_writer = new XMLWriter;
        return $this->_xml_writer;
    }

    public function setCacheTime($cache_time)
    {
        $this->_cache_time = $cache_time;
        return $this;
    }

    public function setCacheDriver($cache_driver)
    {
        $this->_cache_driver = $cache_driver;
    }

    public function setPriority($priority)
    {
        $this->_priority = $priority;
        return $this;
    }

    public function setPeriod($period)
    {
        $this->_period = $period;
        return $this;
    }

    public function setRequestUrl($request_url)
    {
        $this->_request_url = $request_url;
        return $this;
    }

    /**
     * Returns hashed _request_url for cache key
     * @return string
     */
    protected function _getCacheKey()
    {
        return md5($this->_request_url);
    }

    /**
     * Creates cache driver
     * @param srting $type
     * @param array $params
     * @return Doctrine_Cache_Interface
     * @throws InvalidArgumentException
     */
    public function createCacheDriver($type, array $options)
    {
        if( $type === 'file' )
            $this->_cache_driver = new SP_Cache_File($options);
        else
            throw new InvalidArgumentException('Unavailable cache driver type "'.$type.'"');

        return $this->_cache_driver;
    }

    public function generate()
    {
        $xml_content = $this->_cache_driver->fetch($this->_getCacheKey());

        if( !$xml_content )
        {
            $url = $this->_object_href;
            $xml = $this->getXmlWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElementNs(null, 'urlset', 'http://www.sitemaps.org/schemas/sitemap/0.9');

            foreach($this->_data as $row)
            {
                $full_url = $this->_host.str_replace('%id', $row->id, $url);

                $this->_genereteElement($xml, $row, $full_url);
            }


            $xml->endElement();
            $xml->endDocument();

            $xml_content = $xml->outputMemory();
            $this->_cache_driver->save($this->_getCacheKey(), $xml_content, $this->_cache_time);
        }

        header("Content-type: text/xml");
        echo $xml_content;
    }

    protected function _genereteElement(XMLWriter $xml, &$row, &$url)
    {
        $xml->startElement('url');
        $xml->writeElement('loc', $url);
        $lastmod = isset($row->edit_dt)? date('Y-m-d', strtotime($row->edit_dt)) : date('Y-m-d');
        $xml->writeElement('lastmod', $lastmod);
        $xml->writeElement('changefreq', $this->_period);
        $xml->writeElement('priority', $this->_priority);
        $xml->endElement();
    }

}
