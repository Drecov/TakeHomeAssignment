<?php

spl_autoload_register(function ($class) {
    
    $paths =   [__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . $class . ".php",
                __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "services" . DIRECTORY_SEPARATOR . $class . ".php",
                __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "models" . DIRECTORY_SEPARATOR . $class . ".php",
                __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "utils" . DIRECTORY_SEPARATOR . $class . ".php"];

    foreach ($paths as $path) {
        if(file_exists($path)) {
            require_once $path;
        } 
    }
});