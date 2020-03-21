<?php

########################
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('max_execution_time', 0);
###############

require_once "./config.php";
require_once ('block/function.php');

$router = new Lib_Application; //создаем объект, который будет искать нуджные контролеры
$member=$router->Run();//Начинаем поиск нужного контролера
$member['init']=0;
  foreach ($member as $key => $value)
	{
	 	$$key= $value;
	}
	
// подключаем шаблон сайта

require_once "./template/header.php";

 $view = strtolower ($router->getView());

 include ($view);

require_once "./template/footer.php";