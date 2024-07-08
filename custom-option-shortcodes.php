<?php 
/*
Plugin Name: Custom Option Shortcodes
Version: 1.0
Author: Aamir Hussain
URI: https://aammir.github.io
Description: This plugin gets value from a custom option and displays it in a shortcode, Also works with Elementor widgets but not in links in Elementor widgets. <br/>&nbsp;<em>(<strong>Note:</strong> Just put data-tel or data-mailto attributes to email or phone links in order for this to work for those email or phone links in elementor).</em>
Version Date: 8th July 2024
*/

// Hook into WordPress initialization to register the dynamic shortcodes
add_action('init', 'register_dynamic_shortcodes');

function register_dynamic_shortcodes() {
    // Retrieve all the saved option names
    $options = get_option('custom_option_names', []);
    
    // Loop through each option and register a shortcode
    foreach ($options as $option) {
        $option_key = str_replace(' ', '_', strtolower($option)); // Replace spaces with underscores
        add_shortcode($option_key, function() use ($option) {
            // Retrieve the saved value for this option
            $option_values = get_option('custom_option_values', []);
            return isset($option_values[$option]) ? ($option_values[$option]) : '';
        });
    }
}

// Admin menu and page content code (as provided)
add_action('admin_menu', 'my_custom_admin_menu');

function my_custom_admin_menu() {
    if (function_exists('add_menu_page')) {
        add_menu_page(
            'Set Custom Options for Dynamic Shortcodes', 
            'Custom Options', 
            'manage_options', 
            'set-options', 
            'set_options', 
            'dashicons-shortcode', 
            20 
        );
    }
}

function set_options() {
    if (isset($_POST['save_option_name_button'])) {
        if (check_admin_referer('save_option_name', 'save_option_name_nonce')) {
            $option_name = sanitize_text_field($_POST['option_name']);
            $options = get_option('custom_option_names', []);
            if (!in_array($option_name, $options)) {
                $options[] = $option_name;
                update_option('custom_option_names', $options);
                echo '<div class="updated"><p>Option name saved.</p></div>';
            } else {
                echo '<div class="error"><p>Option name already exists.</p></div>';
            }
        }
    }

    if (isset($_POST['submit_option_values'])) {
        if (check_admin_referer('save_option_values', 'save_option_values_nonce')) {
            $options = get_option('custom_option_names', []);
            $option_values = [];
            foreach ($options as $option) {
                $option_key = str_replace(' ', '_', $option);
                if (isset($_POST['option_value_' . $option_key])) {
                    $option_values[$option] = sanitize_text_field($_POST['option_value_' . $option_key]);
                }
            }
            update_option('custom_option_values', $option_values);
            echo '<div class="updated"><p>Option values saved.</p></div>';
        }
    }

    if (isset($_POST['delete_option_button'])) {
        if (check_admin_referer('delete_option', 'delete_option_nonce')) {
            $option_to_delete = sanitize_text_field($_POST['delete_option']);
            $options = get_option('custom_option_names', []);
            $index = array_search($option_to_delete, $options);
            if ($index !== false) {
                unset($options[$index]);
                update_option('custom_option_names', array_values($options)); // array_values to reindex
                $option_values = get_option('custom_option_values', []);
                unset($option_values[$option_to_delete]);
                update_option('custom_option_values', $option_values);
                echo '<div class="updated"><p>Option deleted successfully.</p></div>';
            }
        }
    }

    $saved_options = get_option('custom_option_names', []);
    $saved_option_values = get_option('custom_option_values', []); 
    ?>
<div class="wrap">
    <h2>Add Option Name</h2>
    <form method="post" action="">
        <?php wp_nonce_field('save_option_name', 'save_option_name_nonce'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="option_name">Option Name</label></th>
                <td><input type="text" id="option_name" name="option_name" class="regular-text" /></td>
            </tr>
        </table>
        <?php submit_button('Save Option Name', 'primary', 'save_option_name_button'); ?>
    </form>

    <h2>Set Values to Options</h2>
    <div>
        <?php if (!empty($saved_options)) : ?>
        <form method="post" action="">
            <?php wp_nonce_field('save_option_values', 'save_option_values_nonce'); ?>
            <table class="form-table">
                <?php foreach ($saved_options as $option) : ?>
                <?php $option_key = str_replace(' ', '_', $option); ?>
                <tr valign="top">
                    <th scope="row"><?php echo ($option); ?></th>
                    <td>
                        <input type="text" id="option_value_<?php echo esc_attr($option_key); ?>"
                            name="option_value_<?php echo esc_attr($option_key); ?>"
                            value="<?php echo esc_attr($saved_option_values[$option] ?? ''); ?>" class="regular-text" />
                        <form method="post" action="" style="display:inline;">
                            <?php wp_nonce_field('delete_option', 'delete_option_nonce'); ?>
                            <input type="hidden" name="delete_option" value="<?php echo esc_attr($option); ?>" />
                            <button type="submit" class="button" name="delete_option_button"
                                onclick="return confirm('Are you sure you want to delete this option?');">Delete</button>
                        </form>
                        <br>
                        <small>Shortcode: &nbsp;<code>[<?php echo esc_attr(strtolower($option_key)); ?>]</code></small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php submit_button('Save Options Values', 'primary', 'submit_option_values'); ?>
        </form>
        <?php else : ?>
        <p>No Options saved yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php
}

// Add settings link on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'custom_option_shortcodes_settings_link');

function custom_option_shortcodes_settings_link($links) {
    $settings_link = '<a href="admin.php?page=set-options">' . __('Set Custom Options for Dynamic Shortcodes') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

/******
 * 
 * FIX FOR ELEMENTOR 
 * 
 * *****/

 // Ensure Elementor processes shortcodes in all widgets
function parse_shortcodes_in_elementor($content) {
    return do_shortcode($content);
}
add_filter('elementor/widget/render_content', 'parse_shortcodes_in_elementor');

add_action('wp_footer',function(){
    ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var anchorTags = document.querySelectorAll('a[data-tel], a[data-mailto]');

    anchorTags.forEach(function(anchor) {
        var dataTel = anchor.getAttribute('data-tel');
        var dataMailto = anchor.getAttribute('data-mailto');

        if (dataTel) {
            anchor.setAttribute('href', 'tel:' + dataTel);
        } else if (dataMailto) {
            anchor.setAttribute('href', 'mailto:' + dataMailto);
        }
    });
});
</script>
<?php
});