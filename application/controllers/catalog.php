<?php

  class Application_Controllers_Catalog extends Lib_BaseController
  {
     public function index()
	 {

         $page = null;
         $sort = null;

         $p_sort['name_asc'] = "inline";
         $p_sort['name_desc'] = "none";

         $p_sort['email_asc'] = "inline";
         $p_sort['email_desc'] = "none";

         $p_sort['status_asc'] = "inline";
         $p_sort['status_desc'] = "none";

         if(isset($_GET['page'])) {
             $page = (int)$_GET['page'];
         }
         
	     $model=new Application_Models_Catalog;
		 $Items = $model->getList($page, $sort);
         //debug($Items);
         $task =$Items['task'];

         ### управление кнопками пагинации
         //видимость всего блока пагинации
         if($Items['total']>1) {$p_button['visible'] = "inline";} else {$p_button['visible'] = "none";}

         // видимость кнопок
         $p_button[3]['visible'] = "inline";
         $p_button[4]['visible'] = "inline";
         $p_button[5]['visible'] = "inline";

         // надписи на кнопках и ссылках
         $p_button[1]['number'] = 1;
         $p_button[2]['number'] = ($Items['page']-2)>0?$Items['page']-2:1;
         if($Items['page']-1>0){$p_button[3]['number'] = $Items['page']-1;}else{$p_button[3]['visible'] = "none";};
         $p_button[4]['number'] = $Items['page'];
         if(($Items['page']+1)<= $Items['total']){$p_button[5]['number'] = $Items['page']+1;}else{$p_button[5]['visible'] = "none";};
         $p_button[6]['number'] = ($Items['page']+2)<= $Items['total']?$Items['page']+2:$Items['total'];
         $p_button[7]['number'] = $Items['total'];

         // класс кнопок (подсветка активной)
         if($Items['page'] == $p_button[3]['number']) {$p_button[3]['class'] = "active";} else {$p_button[3]['class'] = "";}
         if($Items['page'] == $p_button[4]['number']) {$p_button[4]['class'] = "active";} else {$p_button[4]['class'] = "";}
         if($Items['page'] == $p_button[5]['number']) {$p_button[5]['class'] = "active";} else {$p_button[5]['class'] = "";}


         // управление сортировкой
         if(isset($_GET['sort'])) {

             $sArray = explode("_", trim($_GET['sort']));

             $field = $sArray[0];
             $dir = $sArray[1];

             // защита от некорректных данных
             switch($field){
                 default:
                     $n_action = false;
                     break;
                 case"name":
                 case"email":
                 case"status":
                     $n_action = true;
                     break;
             }

             switch($dir){
                 default:
                     $s_action = false;
                     $revers = "";
                     break;
                 case"asc":
                     $s_action = true;
                     $revers = "desc";
                     break;
                 case"desc":
                     $s_action = true;
                     $revers = "asc";
                     break;
             }

             //debug("field: ".$field."\ndir: ".$dir."\nN_action: ".$n_action."\nS_action: ".$s_action);

             if($n_action and $s_action) {

                 uasort($task, function ($a, $b) use  ($field, $dir) {

                     //debug("field: ".$field."\ndir: ".$dir);

                     switch($dir){
                         default:
                             break;
                         case "asc":
                             //return $a[$field] <=> $b[$field];  // будет работать только на PHP7, поэтому добавил вариант для более ранних версий
                             if ($a[$field] > $b[$field]) return -1;
                             if ($a[$field] < $b[$field]) return 1;
                             break;
                         case "desc":
                             if ($b[$field] > $a[$field]) return -1;
                             if ($b[$field] < $a[$field]) return 1;
                             //return $b[$field] <=> $a[$field];  // будет работать только на PHP7, поэтому добавил вариант для более ранних версий
                             break;
                     }

                     return(0);
                 });

                 $p_sort[$field.'_'.$dir] = "inline";
                 $p_sort[$field.'_'.$revers] = "none";
             }

         }
         // передаём значение во view
         $this->Items = $task;
         $this->p_button = $p_button;
         $this->p_sort = $p_sort;

     }

  }

?>