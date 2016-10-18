<?php
require_once  dirname(__FILE__).'/../domstorlib/bootstrap.php';

/*
 * In ListController
 */

/**
 * Необходимо создать два контроллера: для списка и для детального просмотра
 * Для контроллера списка определить url вида /:estate/:action, назовем list_route
 * (/flat/sale, /flat/rent, /house/sale, /house/rent)
 * Для детального просмотра - /:estate/:action/:id, назовем detail_route
 */

// Получаем переменные из системы роутинга
//$estate = $route->get('estate');
//$action = $route->get('action');

$estate = empty($_GET['estate'])? 'flat' : $_GET['estate'];
$action = empty($_GET['action'])? 'sale' : $_GET['action'];


$builder = new Custom_FactoryBuilder();
$factory = $builder->build(sprintf('%s_%s', $estate, $action));

// Create form, bind with request and get value for request to data source
$form = $factory->createForm();
$form->bind($_REQUEST);
$form_value = $form->getSourceValue();

// Get UrlGenetrator and set url pattern for current page
$url_generator = $factory->getUrlGenerator();
$url_generator->setUrlPattern(sprintf('/index2.php?estate=%s&action=%s', $estate, $action)/* Сюда текущий URI */);

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
$detail_url = '/detail.php?id=:id';
/*
 * При использовании системы роутинга нужно как-то так:
 * $detail_url = $detail_route->generateUri(array('estate' => $estate, 'action' => $action, 'id' => ':id'));
 * должно получиться что-то типа /flat/sale/:id
 * (обязательно что был :id этот кусок строки заменяется на реальный id объекта)
 *
 */
$list = $factory->createList($detail_url, $form_value
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
        <link href="http://domstor.ru/assets/jquery-ui/css/domstor/jquery-ui-1.9.1.custom.min.css" rel="stylesheet">
        <link href="http://domstor.ru/assets/jquery-ui/widgets/multiselect/jquery.multiselect.css" rel="stylesheet">
        <link href="http://domstor.ru/assets/jquery-ui/widgets/multiselect/jquery.multiselect.filter.css" rel="stylesheet">
        <link href="http://domstor.ru/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="http://domstor.ru/assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link href="http://domstor.ru/css/search/catalog.css" rel="stylesheet">

        <script type="text/javascript" src="http://domstor.ru/assets/jquery/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery/plugins/jquery.utils.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery-ui/js/jquery-ui-1.9.1.custom.min.js"></script>
        <script type="text/javascript" src="http://domstor.ru/js/jquery_plugins/jquery.cookie.js"></script>
        <script type="text/javascript" src="http://domstor.ru/js/jquery_plugins/jquery.uuid.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery-ui/js/jquery.ui.touch-punch.min.js"></script>
        <script async="async" type="text/javascript" src="http://domstor.ru/js/wt_link.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/js/knockout-3.4.0.js"></script>
        <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/yandex/js/util.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/yandex/js/draw_rectangle_behavior.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/yandex/js/draw_point_behavior.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/yandex/js/map_model.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/yandex/js/supply_map.js"></script>



        <script type="text/javascript" src="http://domstor.ru/assets/globalize/lib/globalize.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/globalize/lib/cultures/globalize.culture.ru-RU.cp1251.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/globalize/lib/cultures/globalize.culture.en-US.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery-ui/widgets/multiselect/jquery.multiselect.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery-ui/widgets/multiselect/jquery.district.multiselect.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery-ui/widgets/multiselect/jquery.multiselect.filter.min.js"></script>
        <script type="text/javascript" src="http://domstor.ru/assets/jquery-ui/widgets/domstor/search_extended_area.js"></script>
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
