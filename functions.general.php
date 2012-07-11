<?php

    /**
     * Validates to see if the user has permission to view protected page or post content
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     * @param int $post_id The id of the post contentwe are checking against.
     */
    function member_minder_user_can_view_page($post_id=0)
    {

        global $wp_roles;

        // Retrieve saved permissions meta.
        $permissions_meta = get_post_meta($post_id, MM_PERMISIONS_META_KEY, true);
        $permissions_meta = ($permissions_meta != "") ? unserialize($permissions_meta) : array();

        return member_minder_can_view_content($permissions_meta);

    }
    
    /**
     * Validates to see if the user has permission to view protected content
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.7
     * @param array $privileged_roles An array of roles that can view content on a page.
     */
    function member_minder_can_view_content($privileged_roles)
    {
        global $wp_roles;
        
        // Get the role of the curent user.
        $current_user_role = member_minder_get_current_user_role();

        // Check if not show the user the content and if not return false.
        if(member_minder_role_is_valid($current_user_role) && count($privileged_roles) > 0 && !in_array($current_user_role, $privileged_roles))
            return false;

        // Else assume true;
        return true;
        
    }

    /**
     * Checks to see if the role of the user has only the capability to "read" and/or is level_0
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.7.2
     * @param array $privileged_roles An array of roles that can view content on a page.
     */
    function member_minder_role_is_valid($current_user_role)
    {
        global $wp_roles;

        // Assume the role is valid.
        $valid = true;
        
        if(
                // If we have a non logged in user they should not be able to see content.
                (
                    !isset($wp_roles->roles[$current_user_role]['capabilities'])
                )
                
                // If we have 1 capablity and it is "read" then return trie.
                ||
                (
                    isset($wp_roles->roles[$current_user_role]['capabilities']) &&
                    count($wp_roles->roles[$current_user_role]['capabilities']) == 1 && 
                    isset($wp_roles->roles[$current_user_role]['capabilities']['read']) &&
                    $wp_roles->roles[$current_user_role]['capabilities']['read'] == true
                )

                // If we only have one role and it is "read" return trie
                || 
                (
                    isset($wp_roles->roles[$current_user_role]['capabilities']) &&
                    count($wp_roles->roles[$current_user_role]['capabilities']) == 1 && 
                    isset($wp_roles->roles[$current_user_role]['capabilities']['read']) && 
                    $wp_roles->roles[$current_user_role]['capabilities']['read'] == true
                )
                // If we have 2 capabilities and one is "read" and th other "level_0" then return true
                ||
                (
                    isset($wp_roles->roles[$current_user_role]['capabilities']) &&
                    count($wp_roles->roles[$current_user_role]['capabilities']) == 2 &&
                    isset($wp_roles->roles[$current_user_role]['capabilities']['read']) &&
                    isset($wp_roles->roles[$current_user_role]['capabilities']['level_0']) &&
                    $wp_roles->roles[$current_user_role]['capabilities']['read'] == true &&
                    $wp_roles->roles[$current_user_role]['capabilities']['level_0'] == true
                )
            )
        {
            return true;
        }
        // Return false for anything else.
        else
        {
            return false;
        }
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
            if($content == "") $content ="You do not have permission to view this content";
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
    
    /**
     * Validates to see if the user has permission to view protected content encapsulated in a short code.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.7
     * @param array $atts Attributes passed from the short code
     * @param string $content The content to be protected.
     */
    function member_minder_shortcode( $atts, $content = null ){

        // If the user doesn't have permission then empty the content.
        if(!member_minder_can_view_content(split(',', $atts['secure'])))
        {
            // Clear the content.
            $content = "";

            // If alt text is enabled show that.
            if(isset($atts['enable_alt_text']) && $atts['enable_alt_text'] = true)
            {
                $options =  get_option('member_minder_options');
                $content = $options['unauthorized_access_message'];                
            }
        }
        
        return $content;
    }
