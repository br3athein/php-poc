<?php

class MyException extends Exception
{
    public function __construct($msg, $code = 0, $previous = null)
    {
        parent::__construct($msg, $code, $previous);
        echo "<br><b>FATAL: {$msg}</b><br>";
    }
}

class AuthException extends MyException
{
}
