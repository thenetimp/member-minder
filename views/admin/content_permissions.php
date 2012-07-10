<input type="checkbox" name="member_minder_protected_content" value="true" 
<?php echo (count($permissions_meta) > 0) ? "checked" : "" ?> /> Protected Content <br /><br />

<h3>Allowed Roles</h3>
<ul>
    <?php foreach($roles as $role => $role_info): if(!isset($role_info['capabilities']['level_1'])): ?>
    <li>
        <input type="checkbox" name="member_minder_protected_content_roles[]" value="<?php echo $role; ?>" 
        <?php echo (in_array($role, $permissions_meta)) ? "checked" : "" ?> />  <?php echo $role_info['name'] ?>
    </li>
    <?php endif; endforeach; ?>
</ul>


