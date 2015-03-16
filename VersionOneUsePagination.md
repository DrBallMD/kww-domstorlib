# Использование постраничной навигации #
## Вывод постраничной навигации ##
Объект `Domstor` может вернуть объект постраничной навигации, который легко отобразить.
```
$domstor = new Domstor();
$domstor->setMyId(1);
$domstor->setHomeLocation(2004);
$pager = $domstor->getPager(); // получили объект постраничной навигации
$list = $domstor->getList('flat', 'sale');
echo $pager->render(); // вывод HTML-кода постраничной навигации
```

## Получение нужной страницы списка ##
По умолчанию навигатор генерирует ссылки на страницы, добавляя параметр `page` в GET-запрос. Чтобы переход по страницам работал, необходимо передавать значение этого параметра третьим аргументом в метод `Domstor::getList()`
```
$domstor = new Domstor();
$domstor->setMyId(1);
$domstor->setHomeLocation(2004);
$page = isset($_GET['page'])? $_GET['page'] : 1; // Если в запросе есть page берем его, иначе 1.
$list = $domstor->getList('flat', 'sale', $page); // получаем список нужной страницы.
```

## Количество записей на страницу ##
По умолчанию выводится `20` записей на страницу, этот параметр можно менять в диапазоне от `1` до `50`. Важно помнить, что изменение нужно делать до вызова метода `Domstor::getList()`.
```
$domstor = new Domstor();
$domstor->setMyId(1);
$domstor->setHomeLocation(2004);
$page = isset($_GET['page'])? $_GET['page'] : 1; // Если в запросе есть page берем его, иначе 1.
$pager = $domstor->getPager(); // Получили объект постраничной навигации
$pager->set('on_page', 4); // Будем выводить по 4 записи на страницу
$list = $domstor->getList('flat', 'sale', $page);
```