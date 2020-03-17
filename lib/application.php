<?php
 class Lib_Application //класс маршрутизатор, подбирает нужный контролер для обработки данных
 {
    private function getRoute() //получить маршрут .htaccess формирует ссылку таким образом что в параметры гет запроса попадает требуемый маршрут
    {
	   if (empty($_GET['route']))
       {
           $route = 'index';
	   }
         else
	   {
             $route = $_GET['route'];
	   }
		return $route;
    }

    private function getController()//получить контролер
	{       
       $route=$this->getRoute();
	   $path_contr = 'application/controllers/';
       $controller= $path_contr. $route . '.php';
       return $controller;
    }
	 
	public function getView()//получить представление для контролера
	{
       $route=$this->getRoute();
	   $path_view = 'Application/views/' ;
       $view = $path_view . $route . '.php';
       return $view;
    }
	 
	public function Run()// запуск процесса обработки данных
	{ 
	   session_start(); //открываем сессию

	   $controller = $this->getController(); //получаем контролер

	   $cl=explode('.', $controller);

	   $cl=$cl[0]; //отбрасываем расширение, получаем только путь до контролера
       $name_contr = str_replace("/", "_", $cl);//заменяем в пути слеши на подчеркивания, таким образом получая название класса
	   $contr= new $name_contr;//создаем экземпляр класса контролера
       $contr->index();//запускаем контролер на выполнение (index() должна быть у любого контролера)
	   $member=$contr->member;//получаем переменные контролера
	   return $member;
	
	}
 }
?>