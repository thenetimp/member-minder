<?php

    // Define necessary variables.
    define("MM_PERMISIONS_META_KEY", '_member_minder_permissions');

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
    