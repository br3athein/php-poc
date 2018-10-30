<?php
/**
 * Simply a routing wrapper.
 */

include __DIR__.'/../mvc/lib/Init.php';

if (isset($_POST['action']) && $action = ucfirst($_POST['action'])) {
    $className = sprintf("\app\controllers\%sPost", $action);
    return $className::dispatch();
} elseif (isset($_GET['action']) && $action = ucfirst($_GET['action'])) {
    $className = sprintf("\app\controllers\%sGet", $action);
    return $className::dispatch();
}
return new \app\controllers\Main();
