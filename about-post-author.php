<?php

/*
 * Plugin Name:       About Post Author
 * Plugin URI:        https://wordpress.org/plugins/about-post-author/
 * Description:       Display a box About Post Author with author name, avatar and description after each blog post.
 * Version:           1.0
 * Tested Up to:      6.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mehraz Morshed
 * Author URI:        https://profiles.wordpress.org/mehrazmorshed
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       about-post-author
 * Domain Path:       /languages
 */

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

// do not access the file directlt
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

//main function
if ( ! function_exists( 'about_post_author_main' ) ) {

	function about_post_author_main( $content ) {

		global $post;

		if ( is_single() && isset( $post->post_author ) ) {

			$display_name = get_the_author_meta( 'display_name', $post->post_author );

			if ( empty( $display_name ) ) {

				$display_name = get_the_author_meta( 'nickname', $post->post_author );
			}

			$user_description = get_the_author_meta( 'user_description', $post->post_author );
			$user_website = get_the_author_meta('url', $post->post_author);
			$user_posts = get_author_posts_url( get_the_author_meta( 'ID' , $post->post_author) );

			$author_details = '<div class="about-post-author-avatar">' . get_avatar( get_the_author_meta('email') , 90 ) . '</div>';

			if ( ! empty( $display_name ) ) {

				

				$author_details .= '<div class="about-post-author-name"><a href="'. $user_posts .'">' . $display_name . '</a></div>';
			}

			if ( ! empty( $user_description ) ) {

				$author_details .= '<div class="about-post-author-details">' . nl2br( $user_description ). '</div>';
			}

			$content = $content . '<footer class="about-post-author-section" >' . $author_details . '</footer>';
		}
		return $content;
	}
}

// show about author section after the post
add_action( 'the_content', 'about_post_author_main' );
 
// allow html inside the author bio section
remove_filter('pre_user_description', 'wp_filter_kses');

// enqueue public style
function about_post_author_style_enqueue() {

	wp_enqueue_style( 'about-post-author-styles', plugins_url( 'assets/css/about-post-author-styles.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'about_post_author_style_enqueue' );

// enqueue admin style
function about_post_author_settings_enqueue() {
	wp_enqueue_style( 'about-post-author-settings', plugins_url( 'assets/css/about-post-author-settings.css', __FILE__ ), false, "1.0.0" );
}
add_action( 'admin_enqueue_scripts', 'about_post_author_settings_enqueue' );

// function for settings
function about_post_author_option_page() {

	add_menu_page( 'About Post Author Settings', 'About Post Author', 'manage_options', 'about-post-author', 'about_post_author_settings_page', 'dashicons-admin-plugins', 101 );
}
add_action( 'admin_menu', 'about_post_author_option_page' );

// render settings page
function about_post_author_settings_page() {
	?>
	<div class="about-post-author-main">
		<div class="about-post-author-body about-post-author-common">
			<h1 id="page-title"><?php print esc_html__( 'About Post Author Settings', 'about-post-author' ); ?></h1>
			<form action="options.php" method="post">
				<?php wp_nonce_field( 'update-options' ); ?>
				<!-- Background Color -->
				<label for="about-post-author-background-color" name="about-post-author-background-color"><?php echo esc_html__( 'Background Color', 'about-post-author' ); ?></label>
				<input type="color" id="about-post-author-background-color" name="about-post-author-background-color" value="<?php echo esc_attr( get_option('about-post-author-background-color') ); ?>">
				<!-- Text Color -->
				<label for="about-post-author-text-color" name="about-post-author-text-color"><?php echo esc_html__( 'Text Color', 'about-post-author' ); ?></label>
				<input type="color" id="about-post-author-text-color" name="about-post-author-text-color" value="<?php echo esc_attr( get_option('about-post-author-text-color') ); ?>">

				<!-- input type -->
				<input type="hidden" name="action" value="update">
				<input type="hidden" name="page_options" value="about-post-author-background-color, about-post-author-text-color">
				<input class="button button-primary" type="submit" name="submit" value="<?php esc_attr_e( 'Save Changes', 'about-post-author' ); ?>">
			</form>
		</div>
		<div class="about-post-author-aside about-post-author-common">
			<h2 class="aside-title"><?php echo esc_html__( 'Plugin Author Info', 'about-post-author' ); ?></h2>
			<div class="author-card">
				<a class="link" href="<?php echo esc_url('https://profiles.wordpress.org/mehrazmorshed/'); ?>" target="_blank">
					<img class="center" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/author.png' ); ?>" width="128px">
					<h3 class="author-title"><?php echo esc_html__( 'Mehraz Morshed', 'about-post-author' ); ?></h3>
					<h4 class="author-title"><?php echo esc_html__( 'WordPress Developer', 'about-post-author' ); ?></h4>
				</a>
				<h1 class="author-title">
					<a class="link" href="<?php echo esc_url('https://www.facebook.com/mehrazmorshed/'); ?>" target="_blank"><span class="dashicons dashicons-facebook"></span></a>
					<a class="link" href="<?php echo esc_url('https://twitter.com/mehrazmorshed/'); ?>" target="_blank"><span class="dashicons dashicons-twitter"></span></a>
					<a class="link" href="<?php echo esc_url('https://www.linkedin.com/in/mehrazmorshed/'); ?>" target="_blank"><span class="dashicons dashicons-linkedin"></span></a>
					<a class="link" href="<?php echo esc_url('https://www.youtube.com/@mehrazmorshed/'); ?>" target="_blank"><span class="dashicons dashicons-youtube"></span></a>
				</h1>
			</div>
			<h3 class="aside-title"><?php echo esc_attr_e( 'Other Useful Plugins', 'about-post-author' ); ?></h3>
			<div class="author-card">
				<a class="link" href="<?php echo esc_url('https://wordpress.org/plugins/hide-titles'); ?>" target="_blank">
					<span class="dashicons dashicons-admin-plugins"></span> <b><?php echo esc_html__( 'Hide Titles', 'about-post-author' ); ?></b>
				</a>
				<hr>
				<a class="link" href="<?php echo esc_url('https://wordpress.org/plugins/turn-off-comments'); ?>" target="_blank">
					<span class="dashicons dashicons-admin-plugins"></span> <b><?php echo esc_html__( 'Turn Off Comments', 'about-post-author' ); ?></b>
				</a>
				<hr>
				<a class="link" href="<?php echo esc_url('https://wordpress.org/plugins/hide-admin-navbar'); ?>" target="_blank">
					<span class="dashicons dashicons-admin-plugins"></span> <b><?php echo esc_html__( 'Hide Admin Navbar', 'about-post-author' ); ?></b>
				</a>
				<hr>
				<a class="link" href="<?php echo esc_url('https://wordpress.org/plugins/hide-thumbnails'); ?>" target="_blank">
					<span class="dashicons dashicons-admin-plugins"></span> <b><?php echo esc_html__( 'Hide Thumbnails', 'about-post-author' ); ?></b>
				</a>
			</div>
			<h3 class="aside-title"><?php echo esc_attr_e( 'About Author Meta', 'about-post-author' ); ?></h3>
			<a class="link" href="https://www.buymeacoffee.com/mehrazmorshed" target="_blank">
				<button class="button button-primary btn"><?php echo esc_attr_e( 'Donate To This Plugin', 'about-post-author' ); ?></button>
			</a>
		</div>
	</div>
	<?php
}

// update style from settings
function about_post_author_update_style() {
	?>
	<style type="text/css">

.about-post-author-section {
	background: <?php echo esc_attr( get_option( 'about-post-author-background-color' ) ); ?> !important;
}
.about-post-author-name a {
	<?php echo esc_attr( get_option( 'about-post-author-text-color' ) ); ?> !important;
}
.about-post-author-details {
	color: <?php echo esc_attr( get_option( 'about-post-author-text-color' ) ); ?> !important;
}

	</style>
	<?php
}
add_action( 'wp_head', 'about_post_author_update_style' );

// registering activation hook
function about_post_author_activation() {

	add_option( 'about_post_author_activation_redirect', true );
}
register_activation_hook( __FILE__, 'about_post_author_activation' );

// redirect on activation
function about_post_author_redirect() {

	if( get_option( 'about_post_author_activation_redirect', false ) ) {

		delete_option( 'about_post_author_activation_redirect' );

		if ( !isset( $_GET['active-multi'] ) ) {

			wp_safe_redirect( admin_url( 'admin.php?page=about-post-author' ) );
			exit;
		}
	}
}
add_action( 'admin_init', 'about_post_author_redirect' );
