<?php

// Operator autoloading

$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] =
  array( 'script' => 'extension/owmobile/autoloads/owmobiledetection.php',
         'class' => 'OWMobileDetection',
         'operator_names' => array( 'owmobiledetection','owmobileredirectionstarter'));

?>