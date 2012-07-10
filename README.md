Member Minder
=============

Member Minder is a Wordpress plugin that allows a site owner to decide who sees what content based on roles that a user is given.  Roles must be non administrative and be specifically level_0 privileged, since it makes no sense to restrict users on the front end who have privileges to edit on the back end.

Install the plugin like any other Wordpress plugin, then activate the plugin.  Go to the Settings -> Member Minder and create the default text a user will see if they do not have permission to view a page.  Once that is done go to a page you want to protect and look in the upper right corner under "Select Content Viewable Permissions".  Select "Protect Content", and then choose the Roles that can view the content.  Save the document, and log out.  Going to that page now displays the message that you put in the options.