<?php

/*
 * Plugin Name:       Author Box
 * Plugin URI:        https://mehrazmorshed.com/
 * Description:       Display a box About Post Author with author name, avatar and description after each blog post.
 * Version:           1.2
 * Tested Up to:      6.6
 * Requires at least: 4.4
 * Requires PHP:      7.0
 * Author:            Mehraz Morshed
 * Author URI:        https://profiles.wordpress.org/mehrazmorshed
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       about-post-author
 * Domain Path:       /languages
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Main function to display the author box
function about_post_author_main( $content ) {
    // Check if it's a single post
    if ( is_single() && is_singular( 'post' ) ) {
        // Get the author data
        $author_id = get_the_author_meta( 'ID' );
        $author_name = get_the_author();
        $author_description = get_the_author_meta( 'description' );
        $author_avatar = get_avatar( $author_id, 120 ); // 80 is the size of the avatar
        
        // Get social media links
        $facebook = get_the_author_meta('facebook', $author_id);
        $twitter = get_the_author_meta('twitter', $author_id);
        $linkedin = get_the_author_meta('linkedin', $author_id);
        $instagram = get_the_author_meta('instagram', $author_id);

        // Construct the author box HTML
        $author_box = '<div class="about-post-author-section">';
        $author_box .= '<div class="author-avatar">' . $author_avatar . '<br>';
        $author_box .= '</div>'; // Add avatar here

        $author_box .= '<div class="author-details">';
        $author_box .= '<h4>' . esc_html( $author_name ) . '</h4>';
        $author_box .= '<p>' . esc_html( $author_description ) . '</p>';

        // Social media icons
        $author_box .= '<div class="social-media-icons">';

        if ($facebook) {
            $author_box .= '<a href="' . esc_url($facebook) . '" target="_blank" class="social-icon"><img src="' . plugins_url('assets/images/facebook.png', __FILE__) . '" alt="Facebook"></a>';
        }
        if ($twitter) {
            $author_box .= '<a href="' . esc_url($twitter) . '" target="_blank" class="social-icon"><img src="' . plugins_url('assets/images/twitter.png', __FILE__) . '" alt="Twitter"></a>';
        }
        if ($linkedin) {
            $author_box .= '<a href="' . esc_url($linkedin) . '" target="_blank" class="social-icon"><img src="' . plugins_url('assets/images/linkedin.png', __FILE__) . '" alt="LinkedIn"></a>';
        }
        if ($instagram) {
            $author_box .= '<a href="' . esc_url($instagram) . '" target="_blank" class="social-icon"><img src="' . plugins_url('assets/images/instagram.png', __FILE__) . '" alt="Instagram"></a>';
        }

        $author_box .= '</div>'; // Close social-media-icons

        $author_box .= '</div>'; // Close author-details

        $author_box .= '</div>'; // Close about-post-author-section

        // Append the author box to the post content
        return $content . $author_box;
    }

    // Return original content if not a single post
    return $content;
}

// Hook the author box to the content
add_filter( 'the_content', 'about_post_author_main' );

// Enqueue public style
function about_post_author_style_enqueue() {
	//wp_enqueue_style( 'about-post-author-styles', plugins_url( 'assets/css/about-post-author-styles.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'about_post_author_style_enqueue' );

// Enqueue admin styles and scripts
function about_post_author_admin_enqueue() {
	wp_enqueue_style( 'about-post-author-admin-styles', plugins_url( 'assets/css/admin-styles.css', __FILE__ ) );
	wp_enqueue_script( 'about-post-author-admin-script', plugins_url( 'assets/js/admin-script.js', __FILE__ ), array( 'jquery' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'about_post_author_admin_enqueue' );

// Enqueue frontend styles
function enqueue_about_post_author_styles() {
    if ( is_single() && is_singular( 'post' ) ) {
        wp_enqueue_style( 'about-post-author-frontend', plugin_dir_url( __FILE__ ) . 'assets/css/author-box.css', array(), '1.0', 'all' );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_about_post_author_styles' );

// Add settings menu page with modern design
function about_post_author_option_page() {
	add_menu_page(
		__( 'About Post Author Settings', 'about-post-author' ),
		__( 'About Post Author', 'about-post-author' ),
		'manage_options',
		'about-post-author',
		'about_post_author_settings_page',
		'dashicons-admin-users',
		101
	);
}
add_action( 'admin_menu', 'about_post_author_option_page' );

// Render settings page with tabs and live preview
function about_post_author_settings_page() {
	?>
	<div class="wrap about-post-author-settings-wrap">
		<h1><?php esc_html_e( 'About Post Author Settings', 'about-post-author' ); ?></h1>

		<div class="about-post-author-tabs">
			<ul>
				<li class="active"><a href="#general"><?php esc_html_e( 'General Settings', 'about-post-author' ); ?></a></li>
				<li><a href="#style"><?php esc_html_e( 'Style Settings', 'about-post-author' ); ?></a></li>
				<li><a href="#preview"><?php esc_html_e( 'Live Preview', 'about-post-author' ); ?></a></li>
			</ul>
		</div>

		<form id="about-post-author-settings-form" action="options.php" method="post">
			<?php
			settings_fields( 'about-post-author-settings-group' );
			do_settings_sections( 'about-post-author-settings-group' );
			?>

			<div class="about-post-author-tab-content" id="general">
				<h2><?php esc_html_e( 'General Settings', 'about-post-author' ); ?></h2>

				<!-- Background Color -->
				<div class="form-group">
					<label for="about-post-author-background-color"><?php esc_html_e( 'Background Color', 'about-post-author' ); ?></label>
					<input type="color" id="about-post-author-background-color" name="about-post-author-background-color" value="<?php echo esc_attr( get_option( 'about-post-author-background-color' ) ); ?>">
				</div>

				<!-- Text Color -->
				<div class="form-group">
					<label for="about-post-author-text-color"><?php esc_html_e( 'Text Color', 'about-post-author' ); ?></label>
					<input type="color" id="about-post-author-text-color" name="about-post-author-text-color" value="<?php echo esc_attr( get_option( 'about-post-author-text-color' ) ); ?>">
				</div>

				<!-- Font Size -->
				<div class="form-group">
					<label for="about-post-author-font-size"><?php esc_html_e( 'Font Size (px)', 'about-post-author' ); ?></label>
					<input type="number" id="about-post-author-font-size" name="about-post-author-font-size" value="<?php echo esc_attr( get_option( 'about-post-author-font-size', 16 ) ); ?>">
				</div>
			</div>

			<div class="about-post-author-tab-content" id="style" style="display:none;">
				<h2><?php esc_html_e( 'Style Settings', 'about-post-author' ); ?></h2>

				<!-- Font Family -->
				<div class="form-group">
					<label for="about-post-author-font-family"><?php esc_html_e( 'Font Family', 'about-post-author' ); ?></label>
					<select id="about-post-author-font-family" name="about-post-author-font-family">
						<option value="Arial" <?php selected( get_option( 'about-post-author-font-family' ), 'Arial' ); ?>>Arial</option>
						<option value="Georgia" <?php selected( get_option( 'about-post-author-font-family' ), 'Georgia' ); ?>>Georgia</option>
						<option value="Helvetica" <?php selected( get_option( 'about-post-author-font-family' ), 'Helvetica' ); ?>>Helvetica</option>
					</select>
				</div>

				<!-- Border Style -->
				<div class="form-group">
					<label for="about-post-author-border-style"><?php esc_html_e( 'Border Style', 'about-post-author' ); ?></label>
					<select id="about-post-author-border-style" name="about-post-author-border-style">
						<option value="solid" <?php selected( get_option( 'about-post-author-border-style' ), 'solid' ); ?>>Solid</option>
						<option value="dashed" <?php selected( get_option( 'about-post-author-border-style' ), 'dashed' ); ?>>Dashed</option>
						<option value="none" <?php selected( get_option( 'about-post-author-border-style' ), 'none' ); ?>>None</option>
					</select>
				</div>

				<!-- Padding -->
				<div class="form-group">
					<label for="about-post-author-padding"><?php esc_html_e( 'Padding (px)', 'about-post-author' ); ?></label>
					<input type="number" id="about-post-author-padding" name="about-post-author-padding" value="<?php echo esc_attr( get_option( 'about-post-author-padding', 10 ) ); ?>">
				</div>
			</div>

			<div class="about-post-author-tab-content" id="preview" style="display:none;">
				<h2><?php esc_html_e( 'Live Preview', 'about-post-author' ); ?></h2>
				<div id="about-post-author-preview-box">
					<!-- Preview will be rendered here by JavaScript -->
				</div>
			</div>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// Register and save settings
function about_post_author_register_settings() {
	register_setting( 'about-post-author-settings-group', 'about-post-author-background-color' );
	register_setting( 'about-post-author-settings-group', 'about-post-author-text-color' );
	register_setting( 'about-post-author-settings-group', 'about-post-author-font-size' );
	register_setting( 'about-post-author-settings-group', 'about-post-author-font-family' );
	register_setting( 'about-post-author-settings-group', 'about-post-author-border-style' );
	register_setting( 'about-post-author-settings-group', 'about-post-author-padding' );
}
add_action( 'admin_init', 'about_post_author_register_settings' );

// Output custom styles on frontend based on settings
function about_post_author_update_style() {
	?>
	<style type="text/css">
		.about-post-author-section {
			background: <?php echo esc_attr( get_option( 'about-post-author-background-color' ) ); ?>;
			color: <?php echo esc_attr( get_option( 'about-post-author-text-color' ) ); ?>;
			font-size: <?php echo esc_attr( get_option( 'about-post-author-font-size' ) ); ?>px;
			font-family: <?php echo esc_attr( get_option( 'about-post-author-font-family', 'Arial' ) ); ?>;
			border-style: <?php echo esc_attr( get_option( 'about-post-author-border-style', 'solid' ) ); ?>;
			padding: <?php echo esc_attr( get_option( 'about-post-author-padding', 10 ) ); ?>px;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'about_post_author_update_style' );

// Add social media fields to user profile
function about_post_author_user_profile_fields($user) {
    ?>
    <h3><?php esc_html_e('Social Media Links', 'about-post-author'); ?></h3>
    
    <table class="form-table">
        <tr>
            <th><label for="facebook"><?php esc_html_e('Facebook', 'about-post-author'); ?></label></th>
            <td>
                <input type="text" id="facebook" name="facebook" value="<?php echo esc_attr(get_the_author_meta('facebook', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="twitter"><?php esc_html_e('Twitter', 'about-post-author'); ?></label></th>
            <td>
                <input type="text" id="twitter" name="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="linkedin"><?php esc_html_e('LinkedIn', 'about-post-author'); ?></label></th>
            <td>
                <input type="text" id="linkedin" name="linkedin" value="<?php echo esc_attr(get_the_author_meta('linkedin', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th><label for="instagram"><?php esc_html_e('Instagram', 'about-post-author'); ?></label></th>
            <td>
                <input type="text" id="instagram" name="instagram" value="<?php echo esc_attr(get_the_author_meta('instagram', $user->ID)); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'about_post_author_user_profile_fields');
add_action('edit_user_profile', 'about_post_author_user_profile_fields');

// Save the social media fields
function about_post_author_save_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    update_user_meta($user_id, 'facebook', $_POST['facebook']);
    update_user_meta($user_id, 'twitter', $_POST['twitter']);
    update_user_meta($user_id, 'linkedin', $_POST['linkedin']);
    update_user_meta($user_id, 'instagram', $_POST['instagram']);
}
add_action('personal_options_update', 'about_post_author_save_user_profile_fields');
add_action('edit_user_profile_update', 'about_post_author_save_user_profile_fields');
