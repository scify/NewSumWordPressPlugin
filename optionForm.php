<?php

?>
<div class="wrap">
    <h1><?php _e("NewSum Feed plugin options", "newsumfeed") ?></h1>
    <div>
        <form id='NewSumServerData' method='post'  action="">
        <?php settings_fields( 'newsumfeed-settings' ); ?>
        <?php do_settings_sections( 'newsumfeed-settings' ); ?>            
    <?php 
    // Register field group
    settings_fields( 'newsumfeed-settings' ); 
    ?>
            <table class='form-table'>
                <tr valign='top'><th scope="row"><?php _e("NewSum server URL: ", "newsumfeed") ?></th>
                    <td><input type="text" name="newsumfeed_server_url" value="<?php echo(esc_attr( get_option('newsumfeed_server_url', '') ));?>" size="50"><?php _e(" ex: http://newsumserver.org/service/?wsdl"); ?></td>
                </tr>
                <tr valign='top'><th scope="row"><?php _e("Minimum number of sources to get article: ", "newsumfeed") ?></th>
                    <td><input type="text" name="newsumfeed_min_sources" value="<?php echo(esc_attr( get_option('newsumfeed_min_sources', '3') )); ?>" size="5"><?php _e(" ex: 3", "newsumfeed"); ?></td>
                </tr>
                <tr valign='top'><th scope="row"><?php _e("API key: ", "newsumfeed"); ?></th>
                    <td><input type="text" name="newsumfeed_api_key" value="<?php echo(esc_attr( get_option('newsumfeed_api_key', '') )); ?>" size="35"><?php _e(" ex: XXXX-XXXX-XXXX-XXXX-XXXX", "newsumfeed"); ?></td>
                </tr>
                <tr valign='top'><th scope="row"><?php _e("Language: ", "newsumfeed"); ?></th>
                    <td><input type="text" name="newsumfeed_lang" value="<?php echo(esc_attr( get_option('newsumfeed_lang', 'EN') )); ?>" size="10"><?php _e(" ex: EN", "newsumfeed"); ?></td>
                </tr>
                <tr valign='top'><td span="2"><p class="submit">
                            <!-- <input type="submit" name="Submit" value="<?php _e('Update Options', "newsumfeed") ?>" /> -->
                            <?php submit_button(); ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>