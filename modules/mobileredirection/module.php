<?php

$Module = array(
    'name' => 'mobileredirection',
    'variable_params' => true,
);

$ViewList = array(
    'setsessionredirection' => array(
		        'script' => 'setsessionredirection.php',
		        'params' => array('sitetype')
		        ),
    'redirection' => array(
         	        'script' => 'redirection.php'
	        )
);

?>