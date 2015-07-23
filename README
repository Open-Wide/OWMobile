Extension : OW Mobile
Requires  : eZ Publish 4.x.x (not tested on 3.X)
Author    : Open Wide <http://www.openwide.fr>

# What is OW Mobile?

OW Mobile is an ezpublish extension providing tools for mobile detection and user redirection.
Mobile detection and redirection can be processed either by PHP or JavaScript.
User can choose to visit the standard website even on a mobile device.
Both methods use detection regexp from http://detectmobilebrowsers.com/


# License

This program is free software; you can redistribute it and/or
modify it under the terms of version 2.0  of the GNU General
Public License as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Read /LICENSE


# Installation

Read doc/INSTALL


# How to use owmobileredirectionstarter

##  PHP method
Put this line on top of your pagelayout.tpl : 

```
{first_set( $module_result.view_parameters['site_type'], '' )|owmobileredirectionstarter()}
```

To set a link to the standard website with auto-redirect disabled, use viewparameter 'site_type' with value 'normal|iphone|ipad|mobile' :

```
http://www.standard_website.com/(site_type)/normal
```

**Be careful with reverse-proxys, JS method is safer in this case.**

##  JS method (thanks to https://github.com/sebarmeli/JS-Redirection-Mobile-Site)

- insert "design/standard/javascript/redirection_mobile.js" in your page head
- include "design/standard/templates/redirection_mobile.tpl"

The JS method can receive a GET parameter to disable auto-redirect. The name of this parameter can be set in 'noredirection_param' ini parameter, in owmobile.ini (default value if not set is 'noredirection').

'noredirection_param' GET parameter value must be string 'true'. eg. http://www.standard_website.com/?nomobredirect=true


# Where to get more help

- Update regexp in "/classes/owmobileutils.php" and in "design/standard/javascript/redirection_mobile.js" if you need. Up to date version can be found on http://detectmobilebrowsers.com/
- look into the extension's doc folder
- https://github.com/organizations/Open-Wide
 