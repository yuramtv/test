<?php
//Модель вывода каталога
 class Application_Models_Catalog
  {
     private $table_name = "task"; // имя таблицы

     private $page_limit = 3; //на сколько страниц пагинация

	  public function getList($page = null)
	  {
          $query = "SELECT * FROM ".$this->table_name;

          $out = $this->pagination($page);

          $query .= $out['condition'];

          $TaskItems = DB()->query($query)->fetchAll( PDO::FETCH_ASSOC );

          //debug($TaskItems);

          $out['task'] = $TaskItems;

          return $out;
	  }

     private function pagination($page){

         // Определяем общее число записей в базе данных
         $query = "SELECT COUNT(*) FROM ".$this->table_name;
         $posts = DB()->query($query)->fetchColumn();

         // Находим общее число страниц
         $total = intval(($posts - 1) / $this->page_limit) + 1;
         // Определяем начало сообщений для текущей страницы
         $page = (int)$page;

         //Если запрашиваемая страница больше $total, то переходим на последнюю
         if($page > $total) {$page = $total;}

         // Вычисляем стартовый номер строки для вывода
         $start = ($page-1)*$this->page_limit;
         // Выбираем $num сообщений начиная с номера $start

         $out['condition'] = " LIMIT ".$start.",".$this->page_limit;
         $out['total'] = $total;
         $out['page'] = $page;

         // управление кнопками пагинации
         //видимость всего блока пагинации
         if($total>1) {$p_button['visible'] = "inline";} else {$p_button['visible'] = "none";}

         // видимость кнопок
         $p_button[3]['visible'] = "inline";
         $p_button[4]['visible'] = "inline";
         $p_button[5]['visible'] = "inline";

         // надписи на кнопках и ссылках
         $p_button[1]['number'] = 1;
         $p_button[2]['number'] = ($page-2)>0?$page-2:1;
         if($page-1>0){$p_button[3]['number'] = $page-1;}else{$p_button[3]['visible'] = "none";};
         $p_button[4]['number'] = $page;
         if(($page+1)<= $total){$p_button[5]['number'] = $page+1;}else{$p_button[5]['visible'] = "none";};
         $p_button[6]['number'] = ($page+2)<= $total?$page+2:$total;
         $p_button[7]['number'] = $total;

         // класс кнопок (подсветка активной)
         if($page == $p_button[3]['number']) {$p_button[3]['class'] = "active";} else {$p_button[3]['class'] = "";}
         if($page == $p_button[4]['number']) {$p_button[4]['class'] = "active";} else {$p_button[4]['class'] = "";}
         if($page == $p_button[5]['number']) {$p_button[5]['class'] = "active";} else {$p_button[5]['class'] = "";}

         $out['p_button'] = $p_button;

         return $out;
     }

  } 

?>  
  