
<?php
if(isset($error)) {

echo "<div class='alert alert-danger' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span></button>
    <strong>".$error."</strong>
</div>";
}

if(isset($success)) {

echo "<div class='alert alert-success alert-dismissible fade in' role='alert'>
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>&times;</span></button>
    <strong>".$success."</strong>
</div>";
}
?>

<div class="col-sm-6 panel" >


    <form method="post" action="task<?= isset($task['id'])?"?id=".$task['id']:"" ?>" autocomplete="off">

        <div class="form-group">
            <label for="name">Ваше имя</label>
            <input name="name" type="text" class="form-control" id="name" value="<?= $task['name']?>" placeholder="Введите имя" required="required" />
        </div>
        <div class="form-group">
            <label for="email">Email адрес</label>
            <input name="email" type="email" class="form-control" id="email" value="<?= $task['email']?>" placeholder="Введите email" required="required" />
        </div>

        <div class="form-group">
            <label for="sText" >Описание задачи</label>
            <textarea class="form-control" id="sText" name="sText" rows="3" required="required" placeholder="Введите описание задачи" ><?= $task['sText']?></textarea>
        </div>

        <? if($_SESSION["Auth"] or !isset($task['id'])) {
            if (isset($task['id'])) { ?>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="status" <?php if (($task['status'])==1) {echo "checked";}?> name="status" />
            <label class="form-check-label" for="status">отметка о выполнении</label>
        </div>
          <? } ?>

        <button type="submit" class="btn btn-primary"><?= isset($task['id'])?'Сохранить':'Создать' ?></button>
        <?php } else {
            echo "<div class='form-group form-check'><h2>";
            if($task['status']==1){echo "<span class='label label-success'>задание выполнено</span>";} else {echo "<span class='label label-default'>ожидание</span>";}
            echo "</h2></div>";
        }
        ?>

    </form>
</div>