<?php
require dirname(__FILE__).'/../domstorlib/bootstrap.php';

/*
 * In controller
 */

$estate_type = empty($_GET['estate'])? 'flat' : $_GET['estate'];
$action_type = empty($_GET['action'])? 'sale' : $_GET['action'];

$builder = new Custom_FactoryBuilder();
$factory = $builder->build(sprintf('%s_%s', $estate_type, $action_type));

$hs = new Ds_Helper_Session();

$form_params = $hs->get('form', array());
$sort_params = $hs->get('sort', array());
$params = $form_params + $sort_params + array('room_no_empty' => 1,'target' => 'detail');
$params['id'] = $_GET['id'];
if( !$params['id'] )
    die('Undefined id');

$detail = $factory->createDetail($params);

$factory->getDataLoader()->load();

if( !$detail->hasData() )
    die('No data');

$detail->getBlock('navigation')->setParams(array(
    'url' => '/detail.php?id=:id'
));

/*
 * In view
 */


echo $detail->getHeadTitle();
echo $detail->getPageTitle();

echo $detail->render();
