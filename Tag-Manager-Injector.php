<?php
/**
 * Plugin Name: Custom Google Tag Manager Injector
 * Description: Safely injects Google Tag Manager snippets into the site without third-party plugins.
 * Version:     1.0.0
 * Author:      Milo Lockhart
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// === CONFIGURATION === //
// Optional hard-coded fallback. If you want a hard-coded value, replace 'GTM_ID' below.
// Recommended: leave empty and set via the admin settings page added by this plugin.
define( 'MY_GTM_ID', 'GTM_ID' ); // Replace with your container ID if needed.

// Option name used to store the GTM ID in the WP options table.
define( 'TMI_OPTION_GTM_ID', 'tmi_gtm_id' );

// === GTM HEAD SNIPPET === //
/**
 * Return the effective GTM ID to use.
 * Priority:
 * 1) Value saved in options via admin settings
 * 2) Constant MY_GTM_ID if set and not the placeholder
 * 3) Empty string (nothing will be injected)
 *
 * @return string
 */
function tmi_get_gtm_id() {
	$id = trim( (string) get_option( TMI_OPTION_GTM_ID, '' ) );
	if ( ! empty( $id ) ) {
		return $id;
	}

	if ( defined( 'MY_GTM_ID' ) && MY_GTM_ID && 'GTM_ID' !== MY_GTM_ID ) {
		return MY_GTM_ID;
	}

	return '';
}


function custom_gtm_head_snippet() {
	$gtm_id = tmi_get_gtm_id();
	if ( empty( $gtm_id ) ) {
		return;
	}
	?>


<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');
</script>
<!-- End Google Tag Manager -->

<?php

}
add_action( 'wp_head', 'custom_gtm_head_snippet', 0 );

// === GTM BODY SNIPPET === //
function custom_gtm_body_snippet() {
	$gtm_id = tmi_get_gtm_id();
	if ( empty( $gtm_id ) ) {
		return;
	}
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript>
		<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe>
	</noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action( 'wp_body_open', 'custom_gtm_body_snippet' );

// === OPTIONAL: FALLBACK if theme lacks wp_body_open() === //
function custom_gtm_fallback_body_open() {
if ( ! did_action( 'wp_body_open' ) ) {
add_action( 'wp_footer', 'custom_gtm_body_snippet', 0 );
}
}
add_action( 'wp_footer', 'custom_gtm_fallback_body_open', -9999 );


// -----------------------------
// Admin settings: allow user to set GTM ID
// -----------------------------

/**
 * Sanitize GTM ID input from user. Accepts values like "GTM-XXXX" (alphanumeric and hyphens).
 * Returns empty string if invalid.
 *
 * @param string $input
 * @return string
 */
function tmi_sanitize_gtm_id( $input ) {
	$input = trim( (string) $input );
	$input = strtoupper( $input );

	// Allow GTM-xxxx where xxxx is alphanumeric and hyphens/underscores
	if ( preg_match( '/^GTM-[A-Z0-9_-]+$/', $input ) ) {
		return $input;
	}

	// If user entered nothing, allow empty to clear setting.
	if ( '' === $input ) {
		return '';
	}

	// Invalid input — do not save it.
	add_settings_error(
		TMI_OPTION_GTM_ID,
		'tmi_invalid_gtm',
		__( 'Invalid Google Tag Manager ID. Expected format like GTM-XXXXXX', 'tmi' ),
		'error'
	);
	return '';
}

function tmi_register_settings() {
	register_setting( 'tmi_settings_group', TMI_OPTION_GTM_ID, 'tmi_sanitize_gtm_id' );
	add_settings_section( 'tmi_main_section', __( 'Tag Manager Injector', 'tmi' ), '__return_false', 'tmi-settings' );
	add_settings_field( TMI_OPTION_GTM_ID, __( 'GTM Container ID', 'tmi' ), 'tmi_gtm_field_html', 'tmi-settings', 'tmi_main_section' );
}
add_action( 'admin_init', 'tmi_register_settings' );

function tmi_gtm_field_html() {
	$value = esc_attr( get_option( TMI_OPTION_GTM_ID, '' ) );
	?>
	<input type="text" name="<?php echo esc_attr( TMI_OPTION_GTM_ID ); ?>" value="<?php echo $value; ?>" placeholder="GTM-XXXXXX" />
	<p class="description">Enter your Google Tag Manager container ID (example: GTM-ABC1234). Leave empty to disable.</p>
	<?php
}

function tmi_add_settings_page() {
	add_options_page(
		__( 'Tag Manager Injector', 'tmi' ),
		__( 'Tag Manager Injector', 'tmi' ),
		'manage_options',
		'tmi-settings',
		'tmi_render_settings_page'
	);
}
add_action( 'admin_menu', 'tmi_add_settings_page' );

function tmi_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Tag Manager Injector', 'tmi' ); ?></h1>
		<?php settings_errors( TMI_OPTION_GTM_ID ); ?>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'tmi_settings_group' );
			do_settings_sections( 'tmi-settings' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}













?>

