<?php

/*
Имеется массив числовых значений, например, [4, 5, 8, 9, 1, 7, 2].
В распоряжении есть функция array_swap(&$arr, $num) { … } которая меняет местами
элемент на позиции $num и элемент на 0 позиции.
Т.е. при выполнении array_swap([3, 6, 2], 2) на выходе получим [2, 6, 3].
Написать код, сортирующий произвольный массив по возрастанию,
используя только функцию array_swap.
*/

// исходная функция из задания
function array_swap(&$arr, $num)
{
    list($arr[0], $arr[$num]) = [$arr[$num], $arr[0]];
}

//сдвиг массива вправо
function arrayShift(&$arr)
{

    $tmp = $arr[count($arr) - 1];
    for ($i = count($arr) - 1; $i > 0; $i--)
        $arr[$i] = $arr[$i - 1];

    $arr[0] = $tmp;

}

// поиск максимального элемента
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

/* массив для вариантов теста
for($i = 0; $i < rand(1,100); $i++)
    $arr[] = rand(-100,100);
*/

//---------Выполнение----------------

$arr = [4, 5, 8, 9, 1, 7, 2, 2];

echo "Исходный массив\n";
print_r($arr);

arraySort($arr);

echo "Отсортированый по возрастанию массив\n";
print_r($arr);
