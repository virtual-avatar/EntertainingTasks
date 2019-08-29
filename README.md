#### Задание 1:
Спроектировать структуру таблиц(ы) для хранения Контактов, которые могут иметь друзей из этого же списка Контактов (в рамках задачи достаточно хранить только Имя Контакта). Если Контакт 2 является другом Контакта 1, это не означает, что Контакт 1 является другом Контакта 2.

#### Решение:

Структура таблиц:

Таблица `users` содержит `id` пользователя и его имя `name`, таблица `friends` содержит связи пользователей, в нашем случае `id_friend_one` и `id_friend_two` это внешние ключи к `users.id` 

```sql
CREATE TABLE `users` (
`id` INT(11) NOT NULL AUTO_INCREMENT ,
`name` VARCHAR(45) ,
PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

CREATE TABLE `friends` (
`id_friend_one` INT(11) ,
`id_friend_two` INT(11) ,
PRIMARY KEY (`id_friend_one`,`id_friend_two`),
FOREIGN KEY (id_friend_one) REFERENCES users(id),
FOREIGN KEY (id_friend_two) REFERENCES users(id)
)DEFAULT CHARSET=utf8;
```

#### Задание 1.1: 

Написать запрос sql, отображающий список Контактов, имеющих больше 5 друзей.

#### Решение:

```sql
SELECT id_user, COUNT(id_friends)
FROM (
	SELECT id AS id_user,id_friend_one AS id_friends FROM friends,users WHERE id_friend_two = id
	UNION
	SELECT id AS id_user,id_friend_two AS id_friends FROM friends,users WHERE id_friend_one = id
  ) t
  GROUP BY id_user
  HAVING COUNT(id_friends) > 5;
```
или, что более правильно с использованием JOIN:
```sql
SELECT id_user, COUNT(id_friends)
FROM (
	SELECT id AS id_user,id_friend_one AS id_friends FROM friends JOIN users ON id_friend_two = id
	UNION
	SELECT id AS id_user,id_friend_two AS id_friends FROM friends JOIN users ON id_friend_one = id
  ) t
  GROUP BY id_user
  HAVING COUNT(id_friends) > 5;
```

#### Задание 1.2. 

Написать запрос sql, отображающий все пары Контактов, которые дружат друг с другом. Исключить дубликаты.
(задача на sql запросы, использование PHP запрещено).

#### Решение:

```sql
SELECT DISTINCT CONCAT('(', LEAST(f1.id_friend_one, f2.id_friend_one), ',', GREATEST(f1.id_friend_two, f2.id_friend_two), ')') row
FROM  friends f1 
join  friends f2 on  f1.id_friend_one = f2.id_friend_two  and f1.id_friend_two = f2.id_friend_one where f1.id_friend_one in (select id from users);
```

#### Задание 2. 

Имеется массив числовых значений, например, [4, 5, 8, 9, 1, 7, 2]. В распоряжении есть функция array_swap(&$arr, $num) { … } которая меняет местами элемент на позиции $num и элемент на 0 позиции. Т.е. при выполнении array_swap([3, 6, 2], 2) на выходе получим [2, 6, 3].
Написать код, сортирующий произвольный массив по возрастанию, используя только функцию array_swap.

#### Решение:

Напишем исходную функцию _array_swap_

```php
function array_swap(&$arr, $num)
{
    list($arr[0], $arr[$num]) = [$arr[$num], $arr[0]];
}
```
Функция сортировки `arraySort`:

```php
//вспомогательная функция -  сдвиг массива вправо
function arrayShift(&$arr)
{

    $tmp = $arr[count($arr) - 1];
    for ($i = count($arr) - 1; $i > 0; $i--)
        $arr[$i] = $arr[$i - 1];

    $arr[0] = $tmp;

}

// вспомогательная функция - поиск максимального элемента
function maхElement($arr, $i)
{
    $max = $arr[$i];
    $k = $i;
    for ($j = $i; $j < count($arr); $j++)
        if ($arr[$j] > $max) {
            $max = $arr[$j];
            $k = $j;
        }

    return $k;
}

// главная функция - сортировка массива по возрастанию
function arraySort(&$arr)
{

    array_swap($arr, maхElement($arr, 0));
    for ($i = 1; $i < count($arr); $i++) {
        $tmp = maхElement($arr, $i);
        arrayShift($arr);
        if ($tmp == count($arr) - 1)
            array_swap($arr, 0);
        else
            array_swap($arr, $tmp + 1);
    }
}
```
Исходный код содержится в файле /Task2/arraySort.php

#### Задание 3. 

Написать на PHP парсер html страницы (на входе url), который на выходе будет отображать количество и название всех используемых html тегов. Использование готовых парсеров и библиотек запрещено.

#### Решение:

Пример использования класса `Parser`

``` php
$parser = Parser::getPage(['url' => 'https://yandex.ru/']); // вернет массив данных | false
$arrTag = $parser->getTag(); // вернет ассоциативный массив значений вида ['html тег'=> 'количество на странице' ] | false

/*
print_r($arrTag);
Array
(
    [!DOCTYPE] => 1
    [!--] => 3
    [html] => 1
    [head] => 1
    [meta] => 15
    [title] => 1
    [link] => 9
    [script] => 17
    [i] => 19
    [style] => 3
    [r] => 1
    [svg] => 1
    [body] => 1
    [table] => 11
    [tr] => 15
    [td] => 26
    [a] => 87
    [div] => 105
    [span] => 82
    [ul] => 6
    [li] => 27
    [h1] => 10
    [form] => 2
    [label] => 3
    [input] => 6
    [button] => 2
    [ol] => 2
    [h2] => 1
    [img] => 6
    [noscript] => 2
    [e] => 1
)
*/
```

Исходный код парсера содержится в файле /Task3/Parser.php
