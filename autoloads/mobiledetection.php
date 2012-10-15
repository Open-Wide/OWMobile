<?php

class MobileDetection
{
	var $functions;
	var $useragent;
	var $ini;
	/*!
	 Constructor
	 */
	function MobileDetection()
	{
		$this->Operators = array( 'mobiledetection','mobileredirectionstarter');
        $this->useragent = $_SERVER['HTTP_USER_AGENT'];      
        $this->functions = new MobileUtils();
        $this->ini = eZINI::instance( 'mobileredirection.ini' );
	}

	
	/*!
	 Returns the operators in this class.
	 */
	function &operatorList()
	{
		return $this->Operators;
	}

	/*!
	 \return true to tell the template engine that the parameter list
	 exists per operator type, this is needed for operator classes
	 that have multiple operators.
	 */
	function namedParameterPerOperator()
	{
		return true;
	}
	
	/*!
	 \Executes the needed operator(s).
	 \Checks operator names, and calls the appropriate functions.
	 */
	function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
	{
		switch ( $operatorName )
		{
			case 'mobiledetection':
				{
					$operatorValue = $this->mobiledetectionoperator();
				}
				break;
		    case 'mobileredirectionstarter':
                {
                    $operatorValue = $this->mobileredirectionstarter($operatorValue);
                }
                break;  
		}
	}

	function mobiledetectionoperator()
	{
		eZDebug::writeDebug('execute mobileDetection');
		$useragent = $this->useragent;
		$functions = $this->functions;		
		if($functions->isMobile($useragent)){
		   eZDebug::writeDebug('We are on a mobile');   
	       return "mobile";
		}
        elseif($functions->isIphone($useragent)){
           eZDebug::writeDebug('We are on an iPhone');
           return "iphone";
        }
        elseif($functions->isIpad($useragent)){
           eZDebug::writeDebug('We are on an iPad');
           return "ipad";
        }
    }
	
    function mobileredirectionstarter($site_type)
    {
    	eZDebug::writeDebug('execute mobile redirection', __METHOD__ .'('. __LINE__ .')');
    	
    	//Testing user agent
    	$functions = $this->functions; 
    	$ini = $this->ini;
        
    	//Get user agent
    	$useragent = $_SERVER['HTTP_USER_AGENT'];
    	
    	//Get mobileredirection.ini vars
    	$normalUrl = $ini->variable( 'RedirectUrl', 'normal' );
    	$ipadUrl   = $ini->variable( 'RedirectUrl', 'ipad' );
		$iphoneUrl = $ini->variable( 'RedirectUrl', 'iphone' );
		$mobileUrl = $ini->variable( 'RedirectUrl', 'mobile' );
		
		//check if user website version preference is set instead of auto redirection
    	$http = eZHTTPTool::instance();
    	
    	if($site_type != '')
    	{
    		//set website's version for this session
    		eZDebug::writeDebug('save user siteTypeChoice in session' , __METHOD__ .'(line '. __LINE__ .')');
    		$http->setSessionVariable('siteTypeChoice', $site_type);
    	}
    	
    	//if 'siteTypeChoice' session's var is set and
    	//if user is not already on his favorite website's version
    	//-> user redirected to his favorite website's version
    	if($http->hasSessionVariable('siteTypeChoice',false) && 
    	   !strstr(eZSys::serverURL() , ${$http->sessionVariable('siteTypeChoice').'Url'}))
    	{
    		eZDebug::writeDebug('User is not on his prefered site ' . $http->sessionVariable('siteTypeChoice') . ' => redirect' , __METHOD__ .'(line '. __LINE__ .')');
			$http->redirect(${$http->sessionVariable('siteTypeChoice').'Url'});
    	}
    	//if 'siteTypeChoice' session's var is not set
    	//-> redirected by user agent
    	elseif (!$http->hasSessionVariable('siteTypeChoice',false))
    	{
    		eZDebug::writeDebug('User has no prefered site => redirect according to user agent' , __METHOD__ .'(line '. __LINE__ .')');
			if($functions->isMobile($useragent) ){
			    $http->redirect($mobileUrl);
			}
			elseif($functions->isIphone($useragent)){
			    $http->redirect($iphoneUrl);
			}
			elseif($functions->isIpad($useragent)){
			    $http->redirect($ipadUrl);
			}
        }	
    }
	
	/// \privatesection
	var $Operators;
}

?>