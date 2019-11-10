<?php


namespace app\controllers;


use app\core\base\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render("index");
    }
}
