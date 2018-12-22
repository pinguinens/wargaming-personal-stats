<?php
define('_DOCUMENT_ROOT_', '/usr/share/nginx/html');

include_once(_DOCUMENT_ROOT_ .'/core/System/autoloader.php');

Core\CApplication::makeInstance();

$newAccessToken = Entity\WargamingAPI\WoT\CAuth::saveNewAccessToken($_GET);
if (!is_null($newAccessToken)) {
    Service\Network\CHeader::reload();
}

$AUTH = new Entity\WargamingAPI\WoT\CAuth();

// if ($_GET['logout'] = 'true') {
//     $AUTH->logoutUser();
//     Service\Network\CHeader::reload();
// }1206869

if (!$AUTH->isLogin()) {
    $AUTH->loginUser();
} else {
    $ACCOUNT = new Entity\WargamingAPI\WoT\CAccount($AUTH->getAuthInfo());
    $res = $ACCOUNT->getGarage();
    var_dump($res);
}
