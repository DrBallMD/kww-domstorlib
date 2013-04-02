<?php
require_once(__DIR__.'/../domstorlib/lib/sp/loader.php');
require_once(__DIR__.'/../domstorlib/domstorlib.php');

$loader = new SP_Loader();
$path = dirname(__FILE__);
$loader->registerPrefix('SP', $path.'/../domstorlib/lib');
$loader->register();

$domstor = new Domstor();
$domstor->setMyId(1);
$domstor->setServerName('t-domstor.ru');

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
    }
    else
    {
        $list = $domstor->getList($object, $action, $page);
        $html = $list->getHtml();
    }
}

echo $title, '<br/>';
echo $html;