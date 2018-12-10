<?php
include_once($_SERVER['DOCUMENT_ROOT'] .'/core/System/autoloader.php');

$APPLICATION = Core\CApplication::getInstance();

var_dump($APPLICATION);

Entity\WargamingStats\WoT\CAccount::_api();
