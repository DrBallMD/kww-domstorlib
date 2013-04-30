<?php
require_once(__DIR__.'/../domstorlib/lib/sp/loader.php');
require_once(__DIR__.'/../domstorlib/domstorlib.php');

$loader = new SP_Loader();
$path = dirname(__FILE__);
$loader->registerPrefix('SP', $path.'/../domstorlib/lib');
$loader->registerPrefix('Doctrine', $path.'/../domstorlib/lib');
$loader->register();

$domstor = new Domstor();
$domstor->setMyId(13);
$domstor->setServerName('domstor.ru');
$domstor->setHomeLocation(2004);

$driver = extension_loaded('apc')? new Doctrine_Cache_Apc() : new Doctrine_Cache_Array();
$domstor->setCacheDriver($driver);
$domstor->setCacheTime(600);

$object = isset($_GET['object'])? $_GET['object'] : null;
$action = isset($_GET['action'])? $_GET['action'] : null;
$page = isset($_GET['page'])? $_GET['page'] : 1;
$id = isset($_GET['id'])? $_GET['id'] : null;

$html = '';
$title = '';
if( $object and $action )
{
    if( $id )
    {
        //error_reporting(E_ALL & !E_NOTICE);
        $detail = $domstor->getObject($object, $action, $id);
        if( $detail )
        {
            $detail->showSecondHead(true);
            $html = $detail->render();
            $title = $detail->getPageTitle();
        }
        $filter = '';
    }
    else
    {
        //if( isset($_GET['ids']) ) $domstor->addParam('id', $_GET['ids'] );
        $params = isset($_GET['ids'])? array('id' => $_GET['ids']) : array();
        $list = $domstor->getList($object, $action, $page, $params);
        $html = $list->getHtml();
        $filter = $list->getFilter();
    }
}

echo $title, '<br/>';
echo $filter;
echo $html;
echo $domstor->getCount($object, $action);