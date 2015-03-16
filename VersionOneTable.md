# Отображение таблицы #
Порядок действий следующий:
  1. cоздать объект класса Domstor
  1. установить идентификатор агентства
  1. установить идентификатор местоположения по умолчанию
  1. получить список для нужного типа недвижимости и действия (в примере тип недвижимости - квартиры, действие - продажа, [список возможных типов/действий](VersionOneEstateTypes.md))
  1. вывести HTML-код списка

```
$domstor = new Domstor();
$domstor->setMyId(1);
$domstor->setHomeLocation(2004);
$list = $domstor->getList('flat', 'sale');
$list->display(); // Либо echo $list->render();
```

По умолчанию [шаблоны ссылок](VersionOneHrefTemplates.md) позволяют передавать тип и действие в GET-запросе.

Например, мы запрашиваем скрипт с параметрами `/index.php?object=house&action=rent`. В скрипте читаем эти параметры.
```
$domstor = new Domstor();
$domstor->setMyId(1);
$domstor->setHomeLocation(2004);
$object = isset($_GET['object'])? $_GET['object'] : 'flat'; // если не задан, то flat
$action = isset($_GET['action'])? $_GET['action'] : 'sale'; // если не задан, то sale
$list = $domstor->getList($object, $action);
echo $list->render();
```
Таким образом, получим список аренды домов.