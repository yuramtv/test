<?php

  class Application_Controllers_Enter extends Lib_BaseController
  {
     public function index()
	 {
		if($_REQUEST['login']||$_REQUEST['pass']){
			$model=new Application_Models_Auth;
			$resultValid=$model->ValidData($_REQUEST['login'],$_REQUEST['pass']);

			$this->unVisibleForm=$resultValid['unVisibleForm'];
			$this->userName=$resultValid['login'];
			$this->msg=$resultValid['msg'];
			$this->login=$resultValid['login'];
			$this->pass=$resultValid['pass'];
		}
		else 
			if($_SESSION["Auth"])$this->unVisibleForm=true;
		
		if($_REQUEST['out']=="1"){
			$_SESSION["Auth"]=false;
			$_SESSION["User"]="";
			$this->unVisibleForm=false;
		}
	 }
  }
