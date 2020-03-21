<?php

  class Application_Controllers_Catalog extends Lib_BaseController
  {
     public function index()
	 {

         $sort = null;

	     $model = new Application_Models_Catalog;

         if(isset($_GET['page'])) {$page = (int)$_GET['page'];} else {$page = 1;}
         if($page <0 ){$page = 1;}

         // управление сортировкой

         // инициализация кнопок сортировки
         $p_sort['name_asc'] = "inline";
         $p_sort['name_desc'] = "none";

         $p_sort['email_asc'] = "inline";
         $p_sort['email_desc'] = "none";

         $p_sort['status_asc'] = "inline";
         $p_sort['status_desc'] = "none";

         if(isset($_GET['sort'])) {

             $sArray = explode("_", trim($_GET['sort']));

             $field = $sArray[0];
             $dir = $sArray[1];

             // защита от некорректных данных
             switch($field){
                 default:
                     $field = null;
                     break;
                 case"name":
                 case"email":
                 case"status":
                     break;
             }

             switch($dir){
                 default:
                     $dir = null;
                     $revers = "";
                     break;
                 case"asc":
                     $revers = "desc";
                     break;
                 case"desc":
                     $revers = "asc";
                     break;
             }

             if($dir != null and $field!=null) {
                 $p_sort[$field . '_' . $dir] = "inline";
                 $p_sort[$field . '_' . $revers] = "none";
             }

         }

         $Items = $model->getList($page,$field,$dir);

         $task =$Items['task'];

         // передаём значение во view
         $this->Items = $task;
         $this->p_button = $Items['p_button'];
         $this->p_sort = $p_sort;

     }

    private function sort ($task,$field,$dir)
    {
         uasort($task, function ($a, $b) use  ($field, $dir) {

             switch($dir){
                 default:
                     break;
                 case "asc":
                     return $a[$field] <=> $b[$field];  // будет работать только на PHP7, поэтому добавил вариант для более ранних версий
                     //if ($a[$field] > $b[$field]) return -1;
                     //if ($a[$field] < $b[$field]) return 1;
                     break;
                 case "desc":
                     //if ($b[$field] > $a[$field]) return -1;
                     //if ($b[$field] < $a[$field]) return 1;
                     return $b[$field] <=> $a[$field];  // будет работать только на PHP7, поэтому добавил вариант для более ранних версий
                     break;
             }

             //return(0);
         });

        return $task;
     }
  }
