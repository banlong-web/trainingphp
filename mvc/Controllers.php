<?php
class Controllers
{
    public function model($model)
    {
        require_once './mvc/models/' . $model . '.php';
        return new $model;
    }
}
