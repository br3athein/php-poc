<?php
/**
 * Render landing view.
 */

require '/app/mvc/controllers/secure.php';

echo 'checking auth... ';
if (\secure\authorized()) {
    echo 'auth nok<br>';
    include '/app/mvc/views/landing.htm';
} else {
    echo 'auth ok<br>';
    include '/app/mvc/views/insight.htm';
}
