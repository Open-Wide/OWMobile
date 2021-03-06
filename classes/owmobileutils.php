<?php

class OWMobileUtils {
	
	private $useragent;
	private $ini;
	
	function __construct() {
		$this->useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->ini = eZINI::instance( 'owmobile.ini' );
	}
	
	function setCookieAlive($name,$value,$expires) {
		$_COOKIE[$name] = $value;
		setCookie($name,$value,$expires);
	}
	
	function isMobile() {
		$is_mobile = '0';
		
		// Check if the UA is a mobile one (regexp from http://detectmobilebrowsers.com/ (WURFL))
        // regexp update by Open Wide on 2015-03-19
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$this->useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($this->useragent,0,4))) {
            $is_mobile = 1;
		}
		return $is_mobile;
	}
	
	function isIphone() {
		$iphone=0;
		if (preg_match('/iphone/',strtolower($this->useragent))) {
			$iphone=1;
		}
		return $iphone;
	}
	
	function isIpad() {
		$ipad=0;
		if (preg_match('/ipad/',strtolower($this->useragent))) {
			$ipad=1;
		}
		return $ipad;
	}
	
	function redirect($url) {
		$ini = new eZINI( 'owmobile.ini' );
		$customHeaders = $ini->variable('PHPRedirectionParams', 'customHTTPHeadersBeforeRedirect');
		foreach ($customHeaders as $customHeader => $value) {
			eZHTTPTool::headerVariable($customHeader,$value);
		}
		
		$http = eZHTTPTool::instance();
		$http->redirect($url);
	}
	
	/*
	 * Performs mobile detection and returns the type of mobile device
	 * 
	 * return string iphone|ipad|mobile|unknown
	 */
	function OWMobileDetection() {
		eZDebug::writeDebug('execute OWMobileDetection');
		
        if($this->isIphone()) {
			eZDebug::writeDebug('We are on an iPhone');
           	return "iphone";
        }
        elseif($this->isIpad()) {
           	eZDebug::writeDebug('We are on an iPad');
           	return "ipad";
        }
        elseif($this->isMobile()) {
		   	eZDebug::writeDebug('We are on a mobile');
	       	return "mobile";
		}
		else {
			eZDebug::writeDebug('Unable to detect device');
	       	return "unknown";
		}
    }
	
    function OWMobileRedirectionStarter($site_type){
    	eZDebug::writeDebug('execute OWMobileRedirection', __METHOD__ .'('. __LINE__ .')');
    	
    	$http = eZHTTPTool::instance();
        
    	//Get owmobile.ini vars
    	$normalUrl = $ini->variable( 'RedirectUrl', 'normal' );
    	$ipadUrl   = $ini->variable( 'RedirectUrl', 'ipad' );
		$iphoneUrl = $ini->variable( 'RedirectUrl', 'iphone' );
		$mobileUrl = $ini->variable( 'RedirectUrl', 'mobile' );
		
		//check if user website version preference is set instead of auto redirection
    	if($site_type != '') {
    		//set website's version for this session
    		eZDebug::writeDebug('save user siteTypeChoice in session' , __METHOD__ .'(line '. __LINE__ .')');
    		$http->setSessionVariable('siteTypeChoice', $site_type);
    	}
    	
    	//if 'siteTypeChoice' session's var is set and
    	//if user is not already on his favorite website's version
    	//-> user redirected to his favorite website's version
    	if($http->hasSessionVariable('siteTypeChoice',false) && 
    	   !strstr(eZSys::serverURL() , ${$http->sessionVariable('siteTypeChoice').'Url'})) {
    		eZDebug::writeDebug('User is not on prefered site "' . $http->sessionVariable('siteTypeChoice') . '" => redirect' , __METHOD__ .'(line '. __LINE__ .')');
    		$this->redirect(${$http->sessionVariable('siteTypeChoice').'Url'});
    	}
    	//if 'siteTypeChoice' session's var is not set
    	//-> redirected by user agent
    	elseif (!$http->hasSessionVariable('siteTypeChoice',false)) {
    		eZDebug::writeDebug('User has no prefered site => redirect according to user agent' , __METHOD__ .'(line '. __LINE__ .')');
			if($this->isIphone()) {
			    $this->redirect($iphoneUrl);
			}
			elseif($this->isIpad()) {
			    $this->redirect($ipadUrl);
			}
			elseif($this->isMobile()) {
			    $this->redirect($mobileUrl);
			}
        }	
    }
}
?>
