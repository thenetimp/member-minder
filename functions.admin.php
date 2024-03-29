<?php

    /**
     * Gets an array list of editable roles excluding the defalt wordpress roles.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_admin_get_editable_roles() {

        global $wp_roles;

        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);

        return $editable_roles;
    }
    
    /**
     * Tell the admin section when to display admin meta boxes for controlling content permissions
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_admin_boxes() {

    	add_meta_box(
    		'admin-content-permissoin', // id of the <div> we'll add
    		'Select Content Viewable Persmission', //title
    		'member_minder_select_content_permissions', // callback function that will echo the box content
    		'post', // where to add the box: on "post", "page", or "link" page
    		'side', // put it in the side bar
    		'high' // put it high on the page.
    	);

    	add_meta_box(
    		'admin-content-permissoin', // id of the <div> we'll add
    		'Select Content Viewable Persmission', //title
    		'member_minder_select_content_permissions', // callback function that will echo the box content
    		'page', // where to add the box: on "post", "page", or "link" page
    		'side', // put it in the side bar
    		'high' // put it high on the page.
    	);
    }
    
    /**
     * Build the admin meta box for controling content permissions.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_select_content_permissions()
    {
        global $post_id;

        // Get roles we can use.
        $roles = member_minder_admin_get_editable_roles();
        
        // Get te current meta permissions
        $permissions_meta = get_post_meta($post_id, MM_PERMISIONS_META_KEY, true);
        $permissions_meta = ($permissions_meta != "") ? unserialize($permissions_meta) : array();

        include('views/admin/content_permissions.php');
    }    
    
    /**
     * Save the what roles can view the content of a post/page.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_save_content_permissions()
    {
        global $post_id;

        // Retrieve saved permissions meta.
        $current_permissions_meta = get_post_meta($post_id, MM_PERMISIONS_META_KEY, true);

        // If permissions are enabled then save the content into an array.
        if(isset($_POST['member_minder_protected_content']) && $_POST['member_minder_protected_content'] == true)
        {
            $permissions_meta = (isset($_POST['member_minder_protected_content_roles'])) ?
                                                    $_POST['member_minder_protected_content_roles'] : array(); 

        }
        else
        {
            // If page is viewable to everyone then set an empty array.
            $permissions_meta = array();
        }

        // Serialize the data to save
        $serialized_permissions = serialize($permissions_meta);

        // If we don't have any current permissions stored then we want to add them.
        if(!is_array(unserialize($current_permissions_meta)) && $current_permissions_meta =="")
        {
            add_post_meta($post_id, MM_PERMISIONS_META_KEY, $serialized_permissions, true);
        }
        // If we have current permissions update them.
        else
        {
            update_post_meta($post_id, MM_PERMISIONS_META_KEY, $serialized_permissions, $current_permissions_meta);
        }
    }

    /**
     * Register plugin settings.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_register_settings()
    {
        register_setting( 'member_minder_options_group', 'member_minder_options', 'member_minder_options_validate');
        add_settings_section('member_minder_main', '', 'member_minder_main_options_text', 'member_minder_options_group');
        add_settings_field('member_minder_text_string', 'Unauthorized Access Message', 'member_minder_setting_string', 'member_minder_options_group', 'member_minder_main');
    }
    
    
    /**
     * Options text currently returns an empty string.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_main_options_text()
    {
        return "";
    }
    
    /**
     * Create the form field we'll use for settings.
     * 
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_setting_string()
    {
        $options = get_option('member_minder_options');
        echo '<textarea rows="10" cols="75" name=member_minder_options[unauthorized_access_message]>' .
        
        ((isset($options['unauthorized_access_message'])) ? $options['unauthorized_access_message'] : "") .
        
        
        '</textarea><br />';
        echo '(You can use the following HTML tags &lt;p&gt; &lt;a&gt; &lt;br&gt; &lt;img&gt;)';

    }
    
    /**
     * options validation function.  Return the input for now.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_options_validate($input)
    {
        return $input;
    }
    
    
    /**
     * Display the Memberminder Admin Menu link
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_admin_menu()
    {
        add_options_page('Member Minder', 'Member Minder', 'manage_options', 'member_minder', 'member_minder_options_page');
    }
 
    /**
     * Display the Memberminder admin page.
     *
     * @author  James Andrews <project_support@jamesmandrews.com>
     * @since 0.0.1
     */
    function member_minder_options_page()
    {
        if ( !current_user_can( 'manage_options' ) )  {
    		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    	}
    	
        include('views/admin/options.php');
    }