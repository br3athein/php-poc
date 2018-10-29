<?php

namespace app\controllers;

use app\models\Users;

class LoginPost extends \AbstractController
{
    protected $allowUnauthorized = true;

    public function handle()
    {
        $givenLogin = $_POST['login'];
        $givenPassword = $_POST['pwd'];

        // TODO: remove echos
        try {
            $requestedUser = \app\models\Users::retrieveByField(
                'login',
                $givenLogin,
                \orm\Model::FETCH_ONE
            );
            if (!$requestedUser) {
                echo sprintf(
                    "User not found: %s.<br>"
                    ."<a href='index.php?action=login'>"
                    ."Try another login, lol.</a>",
                    $givenLogin
                );
            }
            $requestedUser->authenticate($givenPassword);
        } catch (orm\AuthException $e) {
            echo sprintf(
                "Login failed for %s.<br>"
                ."<a href='index.php?action=login'>"
                ."Try another pwd, lol.</a>",
                $givenLogin
            );
        }
    }
}
