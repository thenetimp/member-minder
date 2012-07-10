Member Minder
=============

Member Minder is a Wordpress plugin that allows a site owner to decide who sees what content based on roles that a user is given.  Roles must be non administrative and be specifically level_0 privileged, since it makes no sense to restrict users on the front end who have privileges to edit on the back end.

Install the plugin like any other Wordpress plugin, then activate the plugin.  Go to the Settings -> Member Minder and create the default text a user will see if they do not have permission to view a page.  Once that is done go to a page you want to protect and look in the upper right corner under "Select Content Viewable Permissions".  Select "Protect Content", and then choose the Roles that can view the content.  Save the document, and log out.  Going to that page now displays the message that you put in the options.

Short Code
==========

You can also hide content from unprivileged users by use the [memberminder] short code.  To do so you need a required attribute "secure".

    [memberminder secure="members-only"]This content is only viewable by members[/memberminder]

The text "This content is only viewable by members" will only be displayed if the user has the role "members-only".  To allow more than one role to view the content add roles in a comma delimited list without spaces like so.

    [memberminder secure="members-only,friends"]This content is only viewable by members[/memberminder]

Now the roles "members-only" and "friends" can view the content.

When using short codes if a user is not in the "secure" role(s). The content simply does not display.  There will be no message to indicate that there is even any content where the short code is.  This allows for content injection based on roles on a page or post.  If you want the default message to show simply add the attribute 'enable_alt_text="true"' to your short code like this.

    [memberminder secure="members-only" enable_alt_text="true"]This content is only viewable by members[/memberminder]
    
Now the default message for secure content will show up where the shortcode is placed.


Roles
=====
The plugin does not currently support the addition of roles.  Please use another plugin such as

http://wordpress.org/extend/plugins/user-role-editor/

Please note that in order to lock a user out of viewing content they must be level_0 and only be able to "read" as a capability.