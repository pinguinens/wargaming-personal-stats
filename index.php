<?php
include_once(str_replace('/index.php', '/core/config.php', $_SERVER['SCRIPT_FILENAME']));
include_once(_DOCUMENT_ROOT_ .'/core/System/autoloader.php');

$APPLICATION = Core\CApplication::getInstance();

var_dump($APPLICATION);

Entity\WargamingStats\WoT\CAccount::_api();
