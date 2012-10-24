<?php

$ini = eZINI::instance( 'owmobile.ini' );
$useragent = $_SERVER['HTTP_USER_AGENT'];
$functions = new OWMobileUtils();
$iPadUrl = $ini->variable( 'RedirectUrl', 'ipad' );
$iPhoneUrl = $ini->variable( 'RedirectUrl', 'iphone' );
$mobileUrl = $ini->variable( 'RedirectUrl', 'mobile' );
$normalUrl = $ini->variable( 'RedirectUrl', 'normal' );

$http = eZHTTPTool::instance();
$siteTypeChoice = $http->sessionVariable('siteTypeChoice');
eZDebug::writeDebug($siteTypeChoice, 'ma variable de session dans le module');
       
if (isset($siteTypeChoice)) {
	$http->redirect($siteTypeChoice);      
}
elseif($functions->isMobile($useragent)){
    $http->redirect($mobileUrl);
}
elseif($functions->isIphone($useragent)){
    $http->redirect($iPhoneUrl);
}
elseif($functions->isIpad($useragent)){
    $http->redirect($iPadUrl);
}

?>