Extension : OW Mobile
Requires  : eZ Publish 4.x.x (not tested on 3.X)
Author    : Open Wide <http://www.openwide.fr>

What is OW Mobile?
-------------------

OW Mobile is an ezpublish extension providing tools for mobile management.

License
-------

This program is free software; you can redistribute it and/or
modify it under the terms of version 2.0  of the GNU General
Public License as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

Installation
------------

Read doc/install

How to use mobileredirectionstarter
-------------

Put this line on top of your pagelayout.tpl:
{first_set( $module_result.view_parameters['site_type'], '' )|mobileredirectionstarter()}

Where to get more help
----------------------
- look into the extension's doc folder
- https://github.com/organizations/Open-Wide
