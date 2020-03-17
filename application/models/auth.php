<?php
//модель авторизации
 class Application_Models_Auth
  {	  
	//проверка данных авторизации
	  public function ValidData($login,$pass)
	  {
		 
		if($login=="admin" && $pass=="123"){
			$_SESSION["Auth"]=true;  
			$_SESSION["User"]=$login;  
			} 
		else $_SESSION["Auth"]=false;  

		if (!$_SESSION["Auth"]){
			$msg="<em><span style='color:red'>Данные введены не верно!</span></em>";
		}	
		else {
			$msg="<em><span style='color:green'>Вы верно ввели данные!</span></em>";
			$unVisibleForm=true;
		}
		
		$result=array("unVisibleForm"=>$unVisibleForm,
						"userName"=>$login,
						"msg"=>$msg,
						"login"=>$login,
						"pass"=>$pass,);
		return $result;
	  }
  } 

?>  
  