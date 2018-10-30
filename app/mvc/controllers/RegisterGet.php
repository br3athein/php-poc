<?php

namespace app\controllers;

class RegisterGet extends \AbstractController
{
    protected $allowUnauthorized = true;

    public function handle()
    {
        $this->Smarty->display('Register.tpl');
    }
}
