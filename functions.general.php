<?php

    /**
     * Validates to see if the user has permission to view protected content
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     * @param int $post_id The id of the post contentwe are checking against.
     * @param int $user_id The id of the user trying t view the post content.
     */
    function member_minder_user_can_view_page($post_id=0, $user_id = 0)
    {
        return false;
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
        global $post_id;
        $user_id=1;
        
        // If the user does not have permission to view the page get message to display.
        if(!member_minder_user_can_view_page($post_id, $user_id))
        {
            $content = "Unauthorized to view this page.";
        }
        
        return $content;
    }