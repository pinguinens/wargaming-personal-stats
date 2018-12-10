<?php
spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className) .'.php';
    
    if (file_exists($_SERVER['DOCUMENT_ROOT'] .'/classes/'. $className)) {
        include_once($_SERVER['DOCUMENT_ROOT'] .'/classes/'. $className);
    }
});
