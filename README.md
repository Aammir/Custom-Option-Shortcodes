# Custom Option Shortcodes

Custom Option Shortcodes plugin allows you to create and manage custom options through the WordPress admin interface. Each custom option can be assigned a value and dynamically outputted using a shortcode. This plugin is perfect for developers and site administrators who need to add and manage custom text, settings, or content that can be easily included in posts, pages, or widgets using shortcodes.

**Version:** 1.0  
**Version Date:** 8th July 2024

## Features

- **Add Custom Options:** Easily add custom options via the WordPress admin panel.
- **Manage Option Values:** Set and update the values for each custom option.
- **Dynamic Shortcodes:** Each custom option automatically generates a shortcode for easy inclusion in posts, pages, and widgets.
- **Delete Options:** Remove unwanted options directly from the admin interface.
- **Secure:** Includes nonce verification for secure option management.
- **Support for Elementor:** Shortcodes do not work in most of the Elementor widgets, but it has the fix built-in. So these shortcodes are going to work with Elementor. *(Note: Just put `data-tel` or `data-mailto` attributes to email or phone links in order for this to work for those email or phone links in Elementor).*

## Installation

1. **Download and Install:**
    - Download the plugin ZIP file.
    - Upload the ZIP file through the WordPress admin interface or extract the contents and upload to the `wp-content/plugins` directory.

2. **Activate the Plugin:**
    - Go to the Plugins menu in WordPress and activate the Dynamic Option Shortcodes plugin.

## Usage

1. **Access the Plugin Settings:**
    - In the WordPress admin menu, navigate to **Set Options** to access the plugin settings page.

2. **Add Custom Options:**
    - Use the **Add Option Name** section to create new options.
    - Enter the option name and click **Save Option Name**.

3. **Set Option Values:**
    - In the **Set Values to Options** section, enter the values for each option.
    - The corresponding shortcode for each option will be displayed next to the delete button.

4. **Use Shortcodes:**
    - Insert the shortcode `[option_name]` (replace `option_name` with your actual option name) in any post, page, or widget to display the value of that option.

5. **Delete Options:**
    - Click the **Delete** button next to any option to remove it.

## Example

1. **Add an Option:**
    - Add an option named **Site Email**.
    - Set the value to `contact@example.com`.
    - The generated shrotcode will appear after to your option input. Like so: [site_email]

2. **Use the Shortcode:**
    - Insert `[site_email]` in a post or page.    
    - The shortcode will output `contact@example.com`.

## Support

For support, issues, or feature requests, please visit the [GitHub repository](https://github.com/Aammir/) and open an issue.

## Contributing

Contributions are welcome! Please fork the repository, make your changes, and submit a pull request.

## License

This plugin is licensed under the MIT License. See the LICENSE file for more information.
