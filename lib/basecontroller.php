<?php

 class Lib_BaseController
 {
     private $member;

     public function __set($name,$val)
	 {
	     $this->member[$name] = $val;
     }

     public function __get($name)
	 {
		  return $this->member;	
     }        			 
 }

?>