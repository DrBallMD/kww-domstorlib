<?php
// к сожалению не все E_NOTICE исправлены :(
error_reporting(E_ALL & !E_NOTICE);

// подключаем автозагрузчик
require_once(__DIR__.'/../domstorlib/autoload.php');

// создаем объект Domstor
$domstor = new Domstor();

// устанавливаем идентификатор организации
$domstor->setMyId(0);

// устанавливаем идентификатор местоположения по умолчанию
$domstor->setHomeLocation(2004);

// конфигурируем кэш-драйверы - рекомендуется включить для увеличения быстродействия
//$driver = extension_loaded('apc')? new Doctrine_Cache_Apc() : new Doctrine_Cache_Array();
//$domstor->setCacheDriver($driver);
//$domstor->setCacheTime(600);

// получаем параметры object, action необходимые для отображения
/**
 * Возможные значения object:
 * flat - квартиры
 * house - дома и коттеджи
 * land - земля и дачи
 * garage - гаражи и парковки
 * commerce - вся коммерческая
 * Коммерческую можно разделить на
 *  trade - торговую
 *  office - офисную
 *  product - производственную
 *  storehouse - складскую
 *  landcom - земля
 *  complex - комплекс
 *  other - прочие
 *
 * Возможные значения для action:
 * sale - продают
 * rent - сдают
 * purchase - купят
 * rentuse - снимут
 * exchange - обмен (возможен только для flat, house)
 * new - новостройки (только для flat)
 */
$object = isset($_GET['object'])? $_GET['object'] : null;
$action = isset($_GET['action'])? $_GET['action'] : null;

// получаем текущую страницу
$page = isset($_GET['page'])? $_GET['page'] : 1;

// получаем идентификатор объекта
$id = isset($_GET['id'])? $_GET['id'] : null;

// эти переменный выводим во view
$html = '';
$title = '';
$filter = '';
$count = '';

// проверяем $object $action
if( Domstor_Helper::checkEstateAction($object, $action) )
{
    // если определен id - покажем страницу с детальной информацией
    if( $id )
    {
        // запрашиваем объект детального описания
        $detail = $domstor->getDetail($object, $action, $id);

        // проверяем что получен
        if( $detail )
        {
            // получаем html-код детального описания
            $html = $detail->render();
            // получает тайтл
            $title = $detail->getPageTitle();
        }
        else
        {
            // показать 404
        }

    }
    else // иначе список
    {
        // получем объект список
        $list = $domstor->getList($object, $action, $page);
        // получаем html-код списка
        $html = $list->getHtml();
        // получаем объект фильтр
        $filter = $list->getFilter();
        // получаем общее количество найденых записей
        $count = $domstor->getCount($object, $action);
    }
}
else
{
    // обработать ошибку
}
?>

<!-- где-то во view... -->
<?php echo $title ?><br/>
<?php echo $filter ?> <!-- у объекта filter есть волшебный метод __toString -->
<?php if($count): ?>Всего: <?php echo $count ?><?php endif ?>
<?php echo $html ?>

