<?php

    /**
     * Validates to see if the user has permission to view protected content
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     * @param int $post_id The id of the post contentwe are checking against.
     * @param int $user_id The id of the user trying t view the post content.
     */
    function member_minder_user_can_view_page($post_id=0)
    {

        global $wp_roles;

        // Retrieve saved permissions meta.
        $permissions_meta = get_post_meta($post_id, MM_PERMISIONS_META_KEY, true);
        $permissions_meta = ($permissions_meta != "") ? unserialize($permissions_meta) : array();

        // Get the role of the curent user.
        $current_user_role = member_minder_get_current_user_role();

        // If the user is only of level_0
        // If the array has a length greater than 0 and the current user role
        // does not exist in the permissions_meta array then return false.
        if(!isset($wp_roles->roles[$current_user_role]['capabilities']['level_1']) && count($permissions_meta) > 0 && !in_array($current_user_role, $permissions_meta))
            return false;

        // Else asume true;
        return true;
    }
    
    
    /**
     * Gets the role for the current logged in user.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_get_current_user_role()
    {
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
        return $user_role;
    }

    /**
     * Filter hook that intercepts the page content before it is displayed on the page and attempts to see if 
     * the user has permission to view the content or not throwing an unauthorized message if they do not.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_the_content_filter($content)
    {
        global $post;
        
        // If the user does not have permission to view the page get message to display.
        if(!member_minder_user_can_view_page($post->ID))
        {
            $options =  get_option('member_minder_options');
            $content = $options['unauthorized_access_message'];
        }
        
        return $content;
    }
    
    /**
     * Filter hook that intercepts the page content before it is displayed on the page and attempts to see if 
     * the user has permission to view the content or not throwing an unauthorized message if they do not.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_comments_template_filter($template)
    {
        global $post;
        
        // If the user does not have permission to view the page get message to display.
        if(!member_minder_user_can_view_page($post->ID))
        {
            $template = realpath(dirname(__FILE__) . '/views/empty_file.php');
        }
        
        return $template;
    }