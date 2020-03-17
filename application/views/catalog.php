<h1>Каталог задач</h1>

<a type="button" class="btn btn-success" href="/task"  >Создать задачу</a>

            <div class='col-sm-10'>
<?php
    //echo "<pre>".print_r($p_button,true)."</pre>";
    // если в GET параметрах передаётся номер страницы,
    // то добавим её на кнопках сортировки
    if(isset($_GET['page'])) {$base_link = "?page=".$_GET['page']."&";}else{$base_link = "?";}
?>
                <table class='table table-hover table-bordered'>
                   <thead>
                      <tr>
                         <th>id</th>
                         <th>имя <a style="display:<?= $p_sort['name_desc'] ?>;"  href="<?= $base_link ?>sort=name_asc" ><i class="glyphicon glyphicon-chevron-up pull-right"></i></a><a style="display:<?= $p_sort['name_asc'] ?>;"  href="<?= $base_link ?>sort=name_desc" ><i class="glyphicon glyphicon-chevron-down pull-right"></i></a></th>
                         <th>е-mail <a style="display:<?= $p_sort['email_desc'] ?>;"  href="<?= $base_link ?>sort=email_asc" ><i class="glyphicon glyphicon-chevron-up pull-right"></i></a><a style="display:<?= $p_sort['email_asc'] ?>;"  href="<?= $base_link ?>sort=email_desc" ><i class="glyphicon glyphicon-chevron-down pull-right"></i></a></th>
                         <th>текст задачи</th>
                         <th>cтатус <a style="display:<?= $p_sort['status_desc'] ?>;"  href="<?= $base_link ?>sort=status_asc" ><i class="glyphicon glyphicon-chevron-up pull-right"></i></a><a style="display:<?= $p_sort['status_asc'] ?>;"  href="<?= $base_link ?>sort=status_desc" ><i class="glyphicon glyphicon-chevron-down pull-right"></i></a></th>
                        <th></th>
                      </tr>
                   </thead>
                   <tbody>
   <?php
        foreach ($Items as $item) {

                echo "<tr>
                          <th>" . $item['id'] . "</th>
                          <th>" . $item['name'] . "</th>
                          <th>" . $item['email'] . "</th>
                          <th>" . shortenText ($item['sText']).($item['corrected']==1?"<span class='label label-warning'>отредактировано</span>":"") . "</th>
                          <th>" . ($item['status']==1?"<span class='label label-success'>выполнено</span>":"<span class='label label-default'>ожидание</span>") . "</th>
                          <th><a href='/task?id= ". $item['id'] . "' >".($_SESSION["Auth"]?'Edit':'View')."</a></th>
                      </tr>";
        }   ?>
                   </tbody>
                 </table>

<?php
// если в адресной строке присутствует переменная сортировки, то дабавляем её в ссылки на кнопках
if(isset($_GET['sort'])) {
   $end_link = "&sort=".$_GET['sort'];}else{$end_link = "";}
?>

                <nav aria-label="кнопки пагинации" style="display:<?= $p_button['visible'] ?>;" >
                    <ul class="pagination">
                        <li style="display:<?= $p_button[1]['visible'] ?>;"  ><a href="?page=<?= $p_button[1]['number'].$end_link ?>" ><<</a></li>
                        <li style="display:<?= $p_button[2]['visible'] ?>;"  ><a href="?page=<?= $p_button[2]['number'].$end_link ?>" ><</a></li>
                        <li class="<?= $p_button[3]['class'] ?>" style="display:<?= $p_button[3]['visible'] ?>;" ><a href="?page=<?= $p_button[3]['number'].$end_link ?>" style="display:<?= $p_button[3]['visible']; ?>" ><?= $p_button[3]['number'] ?></a></li>
                        <li class="<?= $p_button[4]['class'] ?>" style="display:<?= $p_button[4]['visible'] ?>;" ><a href="?page=<?= $p_button[4]['number'].$end_link ?>" style="display:<?= $p_button[4]['visible']; ?>" ><?= $p_button[4]['number'] ?></a></li>
                        <li class="<?= $p_button[5]['class'] ?>" style="display:<?= $p_button[5]['visible'] ?>;" ><a href="?page=<?= $p_button[5]['number'].$end_link ?>" style="display:<?= $p_button[5]['visible']; ?>" ><?= $p_button[5]['number'] ?></a></li>
                        <li style="display:<?= $p_button[6]['visible'] ?>;" ><a href="?page=<?= $p_button[6]['number'].$end_link ?>" >></a></li>
                        <li style="display:<?= $p_button[7]['visible'] ?>;" ><a href="?page=<?= $p_button[7]['number'].$end_link ?>" >>></a></li>
                    </ul>
                </nav>

            </div>
