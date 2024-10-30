<?
/*
Plugin Name: Google Buzz This Button
Plugin URI: http://www.buzzth.is/
Description: Adds a button which easily buzz ur your blog posts
Version: 1.0
Author: BuzzTh.is 
Author URI: http://www.buzzth.is/
*/

function buzzthis_button_option() {
	add_options_page('BuzzTh.is Setting', 'BuzzThis Button', 8, basename(__FILE__), 'buzzthis_button_options_page');
}
function buzzthis_button_options_page() {
?>
    <div class="wrap">
    <div class="icon32" id="icon-options-general"><br/></div><h2>Settings for Buzzthis Button</h2>
    <p>This plugin will install the Buzzthis Button for each of your blog posts.</p>
    <form method="post" action="options.php">
    <?
        if(function_exists('settings_fields')){
            settings_fields('buzzthis_button-options');
        } else {
            wp_nonce_field('update-options');
            ?>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="'buzzthis_button_showin_page','buzzthis_button_showin_front','buzzthis_button_location','buzzthis_button_style','buzzthis_button_type'" />
            <?
        }
    ?>
        <table class="form-table">
            <tr>
	            <tr>
	                <th scope="row">
	                    Display
	                </th>
	                <td>
	                    <p>
	                        <input type="checkbox" value="1" <? if (get_option('buzzthis_button_showin_page') == '1') echo 'checked="checked"'; ?> name="buzzthis_button_showin_page" id="buzzthis_button_showin_page" group="buzzthis_button_display"/>
	                        <label for="buzzthis_button_showin_page">Display the button on pages</label>
	                    </p>
	                    <p>
	                        <input type="checkbox" value="1" <? if (get_option('buzzthis_button_showin_front') == '1') echo 'checked="checked"'; ?> name="buzzthis_button_showin_front" id="buzzthis_button_showin_front" group="buzzthis_button_display"/>
	                        <label for="buzzthis_button_showin_front">Display the button on the front page (home)</label>
	                    </p>
	                </td>
	            </tr>
                <th scope="row">
                    Position
                </th>
                <td>
                	<p>
                		<select name="buzzthis_button_location">
                			<option <? if (get_option('buzzthis_button_location') == 'before') echo 'selected="selected"'; ?> value="before">Before</option>
                			<option <? if (get_option('buzzthis_button_location') == 'after') echo 'selected="selected"'; ?> value="after">After</option>
                			<option <? if (get_option('buzzthis_button_location') == 'manual') echo 'selected="selected"'; ?> value="manual">Manual</option>
                		</select>
                	</p>
					<span class="setting-description">For Manual option add<code>&lt;?php echo buzzth_is(); ?&gt;</code> to show the button. U can also add Button Type and Style as parameter E.g.<code>&lt;?php echo buzzth_is('COMPACT','float: left; margin-right: 10px;'); ?&gt;</code>. Available button types are <code>NORMAL</code> and<code>COMPACT</code></span>
                </td>
            </tr>
			<tr>
                <th scope="row">
                    Button Type
                </th>
                <td>
                    <p>
                        <input type="radio" value="NORMAL" <? if (get_option('buzzthis_button_type') == 'NORMAL') echo 'checked="checked"'; ?> name="buzzthis_button_type"  group="buzzthis_button_type"/>
                        <label for="buzzthis_button_type">The normal button</label>
                    </p>
                    <p>
                        <input type="radio" value="COMPACT" <? if (get_option('buzzthis_button_type') == 'COMPACT') echo 'checked="checked"'; ?> name="buzzthis_button_type"  group="buzzthis_button_type" />
                        <label for="buzzthis_button_type">The compact button</label>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="buzzthis_button_style">Home Page Styling</label></th>
                <td>
                    <input type="text" value="<? echo htmlspecialchars(get_option('buzzthis_button_style')); ?>" name="buzzthis_button_style" id="buzzthis_button_style" />
                    <span class="setting-description">Add style to the div that surrounds the button E.g. <code>float: left; margin-right: 10px;</code></span>
                </td>
            </tr>
			<tr>
                <th scope="row"><label for="buzzthis_profile_name">Google Profile Name</label></th>
                <td>
                    <input type="text" value="<? echo htmlspecialchars(get_option('buzzthis_profile_name')); ?>" name="buzzthis_profile_name" id="buzzthis_profile_name" />
                    <span class="setting-description">E.g.<code>TechCrunch</code></span>
                </td>
            </tr>
			
			<tr>
                <th scope="row"><label for="buzzthis_profile_url">Google Profile URL</label></th>
                <td>
                    http://www.google.com/profiles/<input type="text" value="<? echo htmlspecialchars(get_option('buzzthis_profile_url')); ?>" name="buzzthis_profile_url" id="buzzthis_profile_url" />
                    <span class="setting-description">E.g. <code>http://www.google.com/profiles/<b>TechCrunch</b></code></span>
                </td>
            </tr>
			
        </table>
        <p class="submit">
            <input type="submit" name="Submit" value="<? _e('Save Changes') ?>" />
        </p>
    </form>
    </div>
<?
}
function buzzthis_button_init(){
    if(function_exists('register_setting')){
        register_setting('buzzthis_button-options', 'buzzthis_button_showin_page');
        register_setting('buzzthis_button-options', 'buzzthis_button_showin_front');
        register_setting('buzzthis_button-options', 'buzzthis_button_location');
        register_setting('buzzthis_button-options', 'buzzthis_button_style');
		register_setting('buzzthis_button-options', 'buzzthis_profile_name');
		register_setting('buzzthis_button-options', 'buzzthis_profile_url');
        register_setting('buzzthis_button-options', 'buzzthis_button_type');
     }
}
function buzzthis_button_add($content) {

    global $post;

    if (get_option('buzzthis_button_location') == 'manual') {
        return $content;
    }

    if (get_option('buzzthis_button_showin_page') == "" && is_page()) {
        echo "<b>Inside Loop<b>\n";
		return $content;
    }

    if (get_option('buzzthis_button_showin_front') == null && is_home()) {
        return $content;
    }

    if (is_feed()) {
    	
    	return $content;
    } 
    if (get_post_meta($post->ID, 'buzzthis_button-options') == '') {
			$button = buzzthis_create_button();    
	        if (get_option('buzzthis_button_location') == 'before') {
	            return $button . $content;
	        } else {
	            return $content . $button;
	        }
	    } else {
	        return $content;
	    }
}
function buzzthis_button_remove($content) {
	remove_action('the_content', 'buzzthis_button_add');
	return $content;
}
function buzzthis_create_button($button_type=null, $style=null) {
	global $post;
	$url = '';
	$title = null;
	
	// let users override these vars when calling manually
	$button_type = ($button_type === null) ? get_option('buzzthis_button_type') : $button_type;
	$style = ($style === null) ? get_option('buzzthis_button_style') : $style;
	
	$profile_url = ($style === null) ? get_option('buzzthis_profile_url') : $style;
	$profile_name = ($style === null) ? get_option('buzzthis_profile_name') : $style;
	
	
	if (get_post_status($post->ID) == 'publish') {
		$url = get_permalink();
		$title = $post->post_title;
	}
	$button = '<script type="text/javascript">' .
			'var buzzthis = {' .
			'buzzthis_url: \'' . $url . '\'' .
			',buzzthis_title: \'' . wp_specialchars($title, '1') . '\'';
	if ($button_type == 'COMPACT') {
		$button .= ',buzzthis_button_type: \'' . $button_type . '\'';
	}
	
	if (trim(get_option('buzzthis_profile_url')) != '') {
		$button .= ',buzzthis_profile_url: \'' . get_option('buzzthis_profile_url') . '\'';
	}
	if (trim(get_option('buzzthis_profile_name')) != '') {
		$button .= ',buzzthis_profile_name: \'' . get_option('buzzthis_profile_name') . '\'';
	}
	$button .= '} </script>';

	if ($style !== '') {
		$button .= '<div style="' . $style . '">';
	}

	$button .= '<script type="text/javascript" src="http://button.buzzth.is/js/button.js"></script>';
	
	if ($style !== '') {
		$button .= '</div>';
	}
			 
	return $button;
	
}
function buzzthis_button_activate() {
    add_option('buzzthis_button_showin_page', '1');
    add_option('buzzthis_button_showin_front', '1');
	add_option('buzzthis_button_location', 'before');
    add_option('buzzthis_button_style', 'float: right; margin-left: 10px;');
    add_option('buzzthis_button_type', 'normal');
	add_option('buzzthis_button-options', '');
	add_option('buzzthis_button-options', '');

}
// Manual Button
function buzzth_is($button_type=null, $style=null) {
    if (get_option('buzzthis_button_location') == 'manual') {
        return buzzthis_create_button($button_type,$style);
    } else {
        return false;
    }
}
if(is_admin()){
    add_action('admin_menu', 'buzzthis_button_option');
    add_action('admin_init', 'buzzthis_button_init');
}
add_filter('the_content', 'buzzthis_button_add');
add_filter('get_the_excerpt', 'buzzthis_button_remove', 9);
register_activation_hook( __FILE__, 'buzzthis_button_activate');

?>