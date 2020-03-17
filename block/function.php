<?php

/**
 * тестовый вывод для целей отладки
 * @param $value
 */
function debug ($value){

    switch(gettype($value)) {
        default:
            $main = print_r($value,true);
            break;
        case "string":
            $main = $value;
            break;
    }

    echo "<pre>".$main."</pre>";
}

/**
 * укорачиваем длину отображаемого в таблице каталога текста
 * @param $text
 * @return string
 */
function shortenText ($text) {

    $max = 100;

    if(strlen ($text) > $max) {

        $text = substr($text, 0, $max);

        $text = rtrim($text, "!,.-");

        $text = substr($text, 0, strrpos($text, ' '))."… ";

    }

    return $text;
}


?>