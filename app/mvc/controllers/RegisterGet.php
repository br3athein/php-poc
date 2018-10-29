<?php

namespace app\controllers;

class RegisterGet
{
    protected $allowUnauthorized = true;

    public function handle()
    {
        $this->Smarty->display('Register.tpl');
    }
}
