<?php
spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className) .'.php';
    
    if (file_exists(_DOCUMENT_ROOT_ .'/classes/'. $className)) {
        include_once(_DOCUMENT_ROOT_ .'/classes/'. $className);
    }
});
