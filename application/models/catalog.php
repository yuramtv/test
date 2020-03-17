<?php
//Модель вывода каталога
 class Application_Models_Catalog
  {

     private $page_limit = 3; //на сколько страниц пагинация

	  public function getList($page = null)
	  {
          ### Пагинация
          // Определяем общее число записей в базе данных
          $query = "SELECT COUNT(*) FROM task ";
          $posts = DB()->query($query)->fetchColumn();

          // Находим общее число страниц
          $total = intval(($posts - 1) / $this->page_limit) + 1;
          // Определяем начало сообщений для текущей страницы
          $page = intval($page);
          // Если значение $page меньше единицы или отрицательно
          // переходим на первую страницу
          // А если слишком большое, то переходим на последнюю
          if(empty($page) or $page <= 0) $page = 1;
          if($page > $total) $page = $total;
          // Вычисляем начиная c какого номера следует выводить сообщения
          $start = ($page-1)*$this->page_limit;
          $finish = $start + $this->page_limit;
          // Выбираем $num сообщений начиная с номера $start

          //управление стрелками пагинации сколько всего у нас будет страниц

          $condition = " LIMIT ".$start.",".$this->page_limit;

          $query = "SELECT * FROM task".$condition;

          //debug($query);

          $TaskItems = DB()->query($query)->fetchAll( PDO::FETCH_ASSOC );

          //debug($TaskItems);

          $out['task'] = $TaskItems;

          //управление стрелками
          $out['total'] = $total;
          $out['page'] = $page;

          return $out;
	  }
  } 

?>  
  