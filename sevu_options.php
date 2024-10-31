<?php
/** Step 2 (from text above). */
add_action( 'admin_menu', 'sevu_menu' );

/** Step 1. */
function sevu_menu() {
    add_options_page( 'ScaleEngine Virtual Usher Options', 'SEVU', 'administrator', 'sevu','sevu_settings_page');
    add_action('admin_init','sevu_options');
}

/** Step 3. */
function sevu_options() {
    register_setting('sevu-settings-group','sevu_cdn');
    register_setting('sevu-settings-group','sevu_api_public');
    register_setting('sevu-settings-group','sevu_api_secret');
    register_setting('sevu-settings-group','sevu_api_address');
    register_setting('sevu-settings-group','sevu_show_errors');
    
    if(!get_option('sevu_api_address'))
    {
        update_option('sevu_api_address','https://api.scaleengine.net/stable/');
    }
}

function sevu_settings_page()
{?>
    <h2>SEVU API Authentication settings</h2>
    <form method="POST" action="options.php">
    <?php
        settings_fields('sevu-settings-group');
        //do_settings_fields('sevu-settings-group');
    ?>
        <table id="sevu_form">
            <tr valign="top">
                <th>SEVU CDN#:</th>
                <td><input type="text" name="sevu_cdn" value="<?php echo get_option('sevu_cdn');?>" style="width:50px;" /></td>
            </tr>
            <tr valign="top">
                <th>SEVU API Public Key:</th>
                <td><input type="text" name="sevu_api_public" value="<?php echo get_option('sevu_api_public');?>" style="width:250px;" /></td>
            </tr>
            <tr valign="top">
                <th>SEVU API Secret Key:</th>
                <td><input type="password" name="sevu_api_secret" value="<?php echo get_option('sevu_api_secret');?>" style="width:250px;" /></td>
            </tr>
            <tr valign="top">
                <th>SEVU API Address:</th>
                <td><input type="text" name="sevu_api_address" value="<?php echo get_option('sevu_api_address');?>" style="width:250px;" /><br/>
                (do not change unless directed by a ScaleEngine employee)</td>
            </tr>
            <tr valign="top">
                <th>Show Error Messages:</th>
                <td>
                    <select name="sevu_show_errors">
                        <option value="0">Off</option>
                        <option value="1" <?php if(get_option('sevu_show_errors')) echo "SELECTED"?>>On</option>
                    </select>
                </td>
            </tr>
        </table>
    
    
    <?php submit_button()?>
    </form>
<?php
}
?>