<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
  array( 'script' => 'extension/owmobile/autoloads/mobiledetection.php',
         'class' => 'MobileDetection',
         'operator_names' => array( 'mobiledetection','mobileredirectionstarter'));

?>