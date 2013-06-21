<?php
require_once  dirname(__FILE__).'/../domstorlib/bootstrap.php';
/*
 * In controller
 */

$estate_type = empty($_GET['estate'])? 'flat' : $_GET['estate'];
$action_type = empty($_GET['action'])? 'sale' : $_GET['action'];


$builder = new Custom_FactoryBuilder();
$factory = $builder->build(sprintf('%s_%s', $estate_type, $action_type));

// Create form, bind with request and get value for request to data source
$form = $factory->createForm();
$form->bind($_REQUEST);
$form_value = $form->getSourceValue();

// Get UrlGenetrator and set url pattern for current page
$url_generator = $factory->getUrlGenerator();
$url_generator->setUrlPattern(sprintf('/index2.php?estate=%s&action=%s', $estate_type, $action_type));

// Set form value to UrlGenerator
$url_generator->setFormValue(array('f' => $form->getValue()));

// Get sort value and set it to UrlGenerator
$sort_value_from_definer = $factory->getSortDefiner()->define();

if( empty($sort_value_from_definer) ) {
    $sort_value = $factory->getDefaultSort();
}
else {
    $sort_value = $sort_value_from_definer;
    $url_generator->setSortValue(array('s' => $sort_value));
}

// Get page value and set it to UrlGenerator
$page_value = $factory->getPageDefiner()->define();
$url_generator->setPageValue($page_value);

// Get onpage value and set it to UrlGenerator
$onpage_value = $factory->getOnPageDefiner()->define();
$url_generator->setOnPageValue($onpage_value);

// Create session helper and save form and sort values for correct sort on detail page
$hs = new Ds_Helper_Session();
$hs->set('form', $form_value);
$hs->set('sort', array('s' => $sort_value));

// Create list with params for data request
$list = $factory->createList('/detail.php?id=:id', $form_value
        + array(
            's' => $sort_value,
            'page' => $page_value,
            'limit' => $onpage_value,
            'target' => 'table',
            )
        + $factory->getAdditionalApiParams()
        );

// Create counter with params for data request
$counter = $factory->createCounter();
$counter->need('total_count', $form_value + $factory->getAdditionalApiParams());

// Load data
$factory->getDataLoader()->load();

// Get and setup pagination
$pagination = $factory->createPagination();
$pagination->setTotal($counter->get('total_count'))
           ->setCurrent($page_value)
           ->setOnPage($onpage_value);

/* Creates object that generates hidden fields
 * for save sort and onpage values when submit button pressed
 */
$hidden_fields = Ds_IoC_Container::instance()->get('form.hidden_fields');
$hidden_fields->setValues(array(
    's' => $sort_value_from_definer,
    'onpage' => $onpage_value
));

$hidden_fields_for_onpage = Ds_IoC_Container::instance()->get('form.hidden_fields');
$hidden_fields_for_onpage->setValues(array(
    's' => $sort_value_from_definer,
    'f' => $form_value,
));
$onpage_form = new Ds_Form_OnPageForm($hidden_fields_for_onpage->render());
$onpage_form->setValue($onpage_value);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="http://domstor.ru/js/jquery.js"></script>
    </head>
    <body>
        <?php
        echo $form->render(array('hidden_fields' => $hidden_fields->render()));
        echo $counter->get('flat_sale');
        echo $list->render();
        echo $onpage_form->render();
        echo $pagination->render();
        ?>
    </body>
</html>
