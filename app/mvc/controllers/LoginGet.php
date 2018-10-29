<?php

namespace app\controllers;

class LoginGet extends \AbstractController
{
    protected $allowUnauthorized = true;

    public function handle()
    {
        $this->Smarty->display('Login.tpl');
    }
}
