<?

if(!$unVisibleForm):?>
    <h1>Вход в личный кабинет</h1>
<?endif;?>

    <div class="col-sm-4 panel" >

        <?php if(!$unVisibleForm):

        if(isset($msg)) {
        echo "<div class='alert alert-danger' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span></button>
            <strong>".$msg."</strong>
        </div>";}
        ?>

        <form action="/enter" method="POST">
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" class="form-control" name="login" value="<?=$login?>" required="required" />
            </div>
            <div class="form-group">
                <label for="pass">Пароль</label>
                <input type="password" class="form-control" name="pass" value="" required="required" /><br />
                <input type="submit" class="btn btn-primary" value="Вход" />
        </form>
    </div>
<? else: ?>
        <h1>Личный кабинет пользователя <?=$userName?></h1>
<a href="/enter?out=1">Выйти из кабинета</a>
<?endif;?>
