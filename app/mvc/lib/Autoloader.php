<?php

$autoloadFunction = (function ($className) {
    $className = explode('\\', $className);
    if ($className[0] === 'app') {
        unset($className[0]);
        $className = array_merge([__DIR__, '..'], $className);
        $filepath = implode(DIRECTORY_SEPARATOR, $className);
        $filepath .= '.php';
        if (file_exists($filepath)) {
            require $filepath;
        } else {
            throw new \MyException("No file found at '{$filepath}'.");
        }
    }
});

spl_autoload_register($autoloadFunction);
