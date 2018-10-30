<?php

namespace app\controllers;

class RegisterPost extends \AbstractController
{
    protected $allowUnauthorized = true;

    public function handle()
    {
        if (empty($_POST['login']) || empty($_POST['pwd'])) {
            throw new \MyException('Provide both desired login and password pls.');
        }

        $Users = new \app\models\Users;
        $login = $_POST['login'];
        $pwd = $_POST['pwd'];

        if (!$Users->checkLoginExists($login)) {
            $Users->register($login, $pwd);
            $this->redirect('/');
        }
        throw new \MyException('User w/ that login exists already.');
    }
}
