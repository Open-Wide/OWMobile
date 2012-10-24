/*!
* JS Redirection Mobile
*
* Developed by
* Sebastiano Armeli-Battana (@sebarmeli) - http://www.sebastianoarmelibattana.com
* Dual licensed under the MIT or GPL Version 3 licenses.
*/
/*
	Copyright (c) 2011 Sebastiano Armeli-Battana (http://www.sebastianoarmelibattana.com)

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
* This script will cover a basic scenario of full JS mobile redirection.
* The user will be redirected to the mobile version of the site (home page)
* if it's trying to access the site from a mobile device. This check is
* mainly done checking the User-Agent string. 
* The mobile URL will be obtained appending a prefix (default is "m") to 
* the hostname of the current URL. It's used a naked domain to avoid conflict with
* www.
* 
* In some cases the user needs to be redirected to the Desktop version of the site 
* from a mobile device. This happens when the user clicks on a link such as "Go to full site"
* or when there is a specific parameter in the querystring.
*
* In that case a new key/value in sessionStorage (for modern browsers) 
* will be set and until the user doesn't close browser window or tab it will access
* to the desktop version from a mobile device. There is a fallback for old browsers that
* don't support sessionStorage, and it will use a cookie. The cookie will expiry in one hour 
* (default value) or you configure the expiry time.
* 
* To use this function, you need to call it as SA.redirection_mobile(config);
* E.g. SA.redirection_mobile ({noredirection_param : "noredirection", mobile_prefix : "mobile", cookie_hours : "2" })
* or
* E.g. SA.redirection_mobile ({mobile_url : "mobile.whatever.com/example", mobile_sheme : "https" })
* or
* E.g. SA.redirection_mobile ({mobile_prefix : "mobile"})
* or
* E.g. SA.redirection_mobile ({mobile_prefix : "mobile", mobile_scheme : "https", redirection_paramName : "modile_redirect"})
* or
* E.g. SA.redirection_mobile ({mobile_url : "mobile.whatever.com/example", tablet_redirection : "true"})
* or
* E.g. SA.redirection_mobile ({{mobile_url : "mobile.whatever.com/example", beforeredirection_callback : (function(){alert('2')}) }})
* or
* E.g. SA.redirection_mobile ({{mobile_url : "mobile.whatever.com", tablet_url : "tablet.whatever.com")
*
*
* @link http://github.com/sebarmeli/JS-Redirection-Mobile-Site/
* @author Sebastiano Armeli-Battana
* @version 0.9.5
* @date 25/07/2011 
* 
*/
	
/*globals window,document, navigator, SA */
if (!window.SA) {window.SA = {};}

/*
* @param configuration object containing three properties:
*			- mobile_prefix : prefix appended to the hostname (such as "m" to redirect to "m.domain.com")
*			- mobile_url : mobile url to use for the redirection (such as "mobile.whatever.com" to redirect to "whatever.com" )
*			- mobile_scheme : url scheme (http/https) of the mobile site domain
*			- cookie_hours : number of hours the cookie needs to exist after redirection to desktop site
*			- noredirection_param : parameter to pass in the querystring of the URL to avoid the redirection (the value must be equal to "true").
*				It's also the name of the item in the localStorage (or cookie name) to avoid mobile
*				redirection. Default value is "noredirection". Eg: http://domain.com?noredirection=true
*			- tablet_redirection : boolean value that enables/disables(default) the redirection for tablet such as iPad, Samsung Galaxy Tab, Kindle or Motorola Xoom. - Default:false
*				Default Url for redirection will be the same as the one for mobile devices.
*			- tablet_url : url to use for the redirection in case user is using a tablet to access the site
*			- keep_path : boolean to determine if the destination url needs to keep the path from the original url
*			- keep_query : boolean to determine if the destination url needs to keep the querystring from the original url
*			- beforeredirection_callback : callback launched before the redirection happens
*/
SA.redirection_mobile = function(configuration) {

	// Helper function for adding time to the current date
	var addTimeToDate = function(msec) {

		// Get the current date
		var exdate = new Date();

		// Add time to the date
		exdate.setTime(exdate.getTime() + msec);

		//Return the new Date
		return exdate;

	};

	// Helper function for getting a value from a parameter in the querystring
	var getQueryValue = function(param) {

		if (!param) {
			return;
		}

		var querystring = document.location.search,
			queryStringArray = querystring && querystring.substring(1).split("&"),
			i = 0,
			length = queryStringArray.length;

		for (; i < length; i++) {
			var token = queryStringArray[i],
				firstPart = token && token.substring(0, token.indexOf("="));
			if (firstPart === param ) {
				return token.substring(token.indexOf("=") + 1, token.length);
			}
		}

	};
				
	// Retrieve the User Agent of the browser
	var agent = navigator.userAgent.toLowerCase(),
	
		// Constants
		FALSE = "false",
		TRUE = "true",

		// configuration object
		config = configuration || {},
	
		// parameter to pass in the URL to avoid the redirection
		redirection_param = config.noredirection_param || "noredirection",
		
		// prefix appended to the hostname
		mobile_prefix = config.mobile_prefix || "m",
		
		// new url for the mobile site domain 
		mobile_url = config.mobile_url,
		
		// protocol for the mobile site domain 
		mobile_protocol = config.mobile_scheme ?
			config.mobile_scheme + ":" :
				document.location.protocol,
		
		// URL host of incoming request
		host = document.location.host,

		// value for the parameter passed in the URL to avoid the redirection
		queryValue = getQueryValue(redirection_param),

		// Compose the mobile hostname considering "mobile_url" or "mobile_prefix" + hostname
		mobile_host = mobile_url ||
			(mobile_prefix + "." + 
				(!!host.match(/^www\./i) ?
					host.substring(4) : 
						host)),

		// Expiry hours for cookie
		cookie_hours = config.cookie_hours || 1,
		
		// Parameters to determine if the pathname and the querystring need to be kept
		keep_path = config.keep_path || false,
		keep_query = config.keep_query || false,

		// new url for the tablet site domain 
		tablet_host = config.tablet_url || mobile_host,
		isUAMobile = false;
		
		// Check if the UA is a mobile one (regexp from http://detectmobilebrowsers.com/ (WURFL))
		// regexp added by Open Wide on 2012-10-23
		// When updating the regexp, change "test(a" to "test(agent" and keep only the "if" instruction
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(agent)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(agent.substr(0,4)))
			isUAMobile = true;

	// Check if the referrer was a mobile page (probably the user clicked "Go to full site") or in the 
	// querystring there is a parameter to avoid the redirection such as "?mobile_redirect=false"
	// (in that case we need to set a variable in the sessionStorage or in the cookie)
	if (document.referrer.indexOf(mobile_host) >= 0 || queryValue === TRUE ) {

		if (window.sessionStorage) {
			window.sessionStorage.setItem(redirection_param, TRUE);
		} else {
			document.cookie = redirection_param + "=" + TRUE + ";expires="+
				addTimeToDate(3600*1000*cookie_hours).toUTCString();
		}
	}

	// Check if the sessionStorage contain the parameter
	var isSessionStorage = (window.sessionStorage) ? 
			(window.sessionStorage.getItem(redirection_param) === TRUE) :
				false,

		// Check if the Cookie has been set up
		isCookieSet = document.cookie ? 
			(document.cookie.indexOf(redirection_param) >= 0) :
				false;
	
	// Check if the device is a Tablet such as iPad, Samsung Tab, Motorola Xoom or Amazon Kindle
	if (!!(agent.match(/(iPad|SCH-I800|xoom|kindle)/i))) {

		// Check if the redirection needs to happen for iPad
		var isUATablet = (config.tablet_redirection === TRUE || !!config.tablet_url) ? true : false;
		isUAMobile = false;
	}

	// Check that User Agent is mobile, cookie is not set or value in the sessionStorage not present
	if ((isUATablet || isUAMobile) && !(isCookieSet || isSessionStorage)) {

		// Callback call
		if (config.beforeredirection_callback) {
			if (!config.beforeredirection_callback.call(this)) {
				return;
			}
		}
		
		var path_query = "";
		
		if(keep_path) { 
			path_query += document.location.pathname;
		}
		
		if (keep_query) {
			path_query += document.location.search;
		}
		
		if (isUATablet){
			document.location.href = mobile_protocol + "//" + tablet_host + path_query;
		} else if (isUAMobile) {
			document.location.href = mobile_protocol + "//" + mobile_host + path_query;
		}
		
	} 
};	