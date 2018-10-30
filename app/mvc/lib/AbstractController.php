<?php

/**
 * Common interface for any controller in scope.
 */
abstract class AbstractController
{
    /**
     * Any controller should check whether current user is authenticated,
     * redirect to login page if needed.
     * noop ATM.
     */
    protected $allowUnauthorized = false;
    private $Smarty;

    public abstract function handle();

    public function __construct()
    {
        if (!$this->allowUnauthorized && !$this->_checkAuth()) {
            $this->redirect('/?action=login');
        }
    }

    public function __get($property)
    {
        if ($property === 'Smarty' && !isset($this->Smarty)) {
            $this->Smarty = new \Shmarty;
            return $this->Smarty;
        }
    }

    protected function _checkAuth() {
        // surely this needs to be implemented... slightly better.
        return isset($_SESSION['login']);
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        die();
    }

    /**
     * Actually runs controller logic.
     */
    public static function dispatch()
    {
        $instance = new static();
        return $instance->handle();
    }
}
