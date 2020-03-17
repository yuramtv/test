<?php


class Application_Controllers_Task extends Lib_BaseController
{
    public function index()
    {

        $model = new Application_Models_Task;

        if(isset($_GET['id'])) {

            $task_id = $_GET['id'];
        }

        if(isset($_POST['name']) and isset($_POST['email']) and isset($_POST['sText']) ){


            $user_name = addslashes (htmlspecialchars(trim($_POST['name'])));

            $email = addslashes (htmlspecialchars(trim($_POST['email'])));

            $sText = addslashes (htmlspecialchars(trim($_POST['sText'])));

            if (isset($_POST['status']) ) { $status = 1;} else {$status = 0; }

            if(!$_GET['id']) {
                $task_id = $model->createTask($user_name,$email,$sText);

                $this->success = "Создана задача № ".$task_id;
            } else {

                if($_SESSION["Auth"]) {

                    // проверяем, изменился ли тескст
                    $old_task_array = $model->getTask($task_id);

                    if($old_task_array['sText'] != $sText) {
                        $corrected = true;
                    } else {
                        $corrected = null;
                    }

                   if($model->saveTask($task_id, $user_name, $email, $sText, $status, $corrected)){
                       $this->success = "Задача № ".$task_id. " отредактирована";
                   };
                } else {
                    $this->error = "Авторизуйтесь для редактирования задачи: <a href='/enter' target='_blank' >Войти</a>";
                }
            }
        }

        if(isset($task_id)) {
        $task = $model->getTask($task_id);
        $this->task=$task;
        }

    }
}