<?php
define('_DOCUMENT_ROOT_', '/usr/share/nginx/html');

include_once(_DOCUMENT_ROOT_ .'/core/System/autoloader.php');

Core\CApplication::getInstance();
