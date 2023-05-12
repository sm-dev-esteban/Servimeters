<?php

class Repository{

    private $model;

    function __construct($model){
        require_once "../model/". $model .".model.php";
        $this->model = new $model();
    }

    function execute($object, $action){
        $result = $this->model->$action($object);
        return $result; 
    } 

    function delete(){
        $result = $this->model->delete();
        return $result;
    }

    function update(){
        $result = $this->model->update();
        return $result;
    }

    function get($action){
        $result = $this->model->$action();
        return $result;
    }

    function getModel(){
        return $this->model;
    }

}