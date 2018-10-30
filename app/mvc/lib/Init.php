<?php
/**
 * __DIR__ = /app/mvc/lib
 */

session_start();

// Internal stuff
include __DIR__.'/Exceptions.php';
include __DIR__.'/Autoloader.php';
include __DIR__.'/AbstractController.php';
include __DIR__.'/DatabaseConnection.php';

// External stuff w/ configurations
include __DIR__.'/../../vendor/autoload.php';
include __DIR__.'/Shmarty.php';
