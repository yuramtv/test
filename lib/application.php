<?php
 class Lib_Application
 {
    private function getRoute()
    {
	   if (empty($_GET['route'])) {$route = 'index'; } else {$route = $_GET['route'];}
		return $route;
    }

    private function getController()
	{       
       $route=$this->getRoute();
	   $path_contr = 'application/controllers/';
       $controller= $path_contr. $route . '.php';
       return $controller;
    }
	 
	public function getView()
	{
       $route=$this->getRoute();
	   $path_view = 'Application/views/' ;
       $view = $path_view . $route . '.php';
       return $view;
    }
	 
	public function Run()
	{ 
	   session_start();

	   $controller = $this->getController();

	   $cl=explode('.', $controller);
       $name_contr = str_replace("/", "_", $cl[0]);

	   $contr= new $name_contr;
       $contr->index();

	   $member=$contr->member;

	   return $member;
	}
 }
?>