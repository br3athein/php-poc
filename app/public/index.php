<?php
/**
 * Main app entrypoint
 *
 * PHP version 7.2.2
 *
 * @category Generic
 * @package  MyAuth
 * @author   br3athein <br3athein@gmail.com>
 * @license  https://gnu.org/licenses/agpl AGPL-3
 * @version  GIT: 84ba1797862a95b074519f3c56bdbeff1b0de60b
 * @link     https://github.com/br3athein/php-poc
 */

echo 'GET:<br>';
var_dump($_GET);
echo '<br>';
echo 'POST:<br>';
var_dump($_POST);
echo '<hr>';

if ($action = (isset($_GET['action']) ? $_GET['action'] : null)) {
    include '/app/mvc/controllers/' . $action . '.get.php';
} elseif ($action = (isset($_POST['action']) ? $_POST['action'] : null)) {
    include '/app/mvc/controllers/' . $action . '.post.php';
} else {
    include '/app/mvc/controllers/landing.php';
}
