<?php
class Controllers
{
    public function __construct()
	{
		# code...
	}
    public function model($model)
    {
        require_once './mvc/models/' . $model . '.php';
        return new $model;
    }
}
