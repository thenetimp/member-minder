<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2>Member Minder Options</h2>
    <p>When a person who doesn't have access to a page visits that page you will want to display some kind of message to them.  Use the box below to create that message. Maybe offer them a link back to the homepage, using HTML links.</p>
    <form method="post" action="options.php" enctype="multipart/form-data">
        <?php settings_fields('member_minder_options_group'); ?>
        <?php do_settings_sections('member_minder_options_group'); ?>
        <p class="submit">
            <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </p>
    </form>
</div>