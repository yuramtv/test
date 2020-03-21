<?php

class Application_Models_Task
{
    /**
     * Загружаем задачу по её ID
     * @param $id
     * @return mixed
     */
    public function getTask($id)
    {
        $query = "SELECT * FROM task WHERE id='$id'";

        $TaskArray = DB()->query($query)->fetchAll( PDO::FETCH_ASSOC );

        return $TaskArray[0];
    }

    /**
     * Создание задачи
     * @param $name
     * @param $email
     * @param $sText
     * @return mixed
     */
    public function createTask($name,$email,$sText)
    {

        $query = "INSERT INTO task ( name ,email,sText) VALUES ('$name','$email','$sText')";

        DB()->prepare($query)->execute();

        $rec_id = DB()->lastInsertId();

        return $rec_id;
    }

    /**
     * Редактирование задачи
     * @param $id
     * @param $name
     * @param $email
     * @param $sText
     * @param $status
     * @param null $corrected
     * @return mixed
     */
    public function saveTask($id,$name,$email,$sText,$status,$corrected = null)
    {
        // если текст отредактирован администратором
        if($corrected !=null) {
            $corrected = ",`corrected` ='1'";
        } else {
            $corrected = "";
        }

        $query = "UPDATE `task` SET `name` ='$name',`email` ='$email',`sText`='$sText',`status` ='$status' $corrected WHERE `id` ='$id'";

        $result = DB()->prepare($query)->execute();

        return $result;
    }
}
