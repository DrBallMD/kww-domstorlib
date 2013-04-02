<?php
require __DIR__.'/../domstorlib/bootstrap.php';

/*
 * In controller
 */
$factory = new Custom_FlatSaleFactory();
$hs = new Ds_Helper_Session();

$form_params = $hs->get('form', array());
$sort_params = $hs->get('sort', array());
$params = $form_params + $sort_params + array('room_no_empty' => 1);
$params['id'] = $_GET['id'];

$detail = $factory->createDetail($params);

$factory->getDataLoader()->load();

if( !$detail->hasData() )
    die('No data');

$detail->getBlock('flat.sale.navigation')->setParams(array(
    'url' => '/detail.php?id=:id'
));

/*
 * In view
 */


echo $detail->getHeadTitle();
echo $detail->getPageTitle();

echo $detail->render();
