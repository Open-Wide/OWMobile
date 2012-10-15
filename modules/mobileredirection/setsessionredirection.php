<?php

$sitetype = $Params['sitetype'];
if(!$sitetype){
    $sitetype = 'normal';
}

$settings = eZINI::instance( 'mobileredirection.ini' );
$destination = $settings->variable( 'RedirectUrl', $sitetype );

$http = eZHTTPTool::instance();
$http->removeSessionVariable('siteTypeChoice');
$http->setSessionVariable('siteTypeChoice', $destination);
$http->redirect($destination);


?>