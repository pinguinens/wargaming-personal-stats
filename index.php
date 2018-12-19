<?php
define('_DOCUMENT_ROOT_', '/usr/share/nginx/html');

include_once(_DOCUMENT_ROOT_ .'/core/System/autoloader.php');

Core\CApplication::makeInstance();

$newAccessToken = Entity\WargamingAPI\WoT\CAuth::saveNewAccessToken($_GET);
if (!is_null($newAccessToken)) {
    Service\Network\CHeader::reload();
}

$AUTH = new Entity\WargamingAPI\WoT\CAuth();
if (!$AUTH->isLogin()) {
    $AUTH->loginUser();
} else {
    $res = Entity\WargamingAPI\WoT\CAccount::getPlayer('Pinguinens');
    var_dump($res);
}
