<?php

namespace app\controllers;

class Main extends \AbstractController
{
    public function handle()
    {
        return $this->Smarty->display('Main.tpl');
    }
}
