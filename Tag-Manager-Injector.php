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
define( 'MY_GTM_ID', 'GTM_ID' ); // Replace with your container ID if needed.

// === GTM HEAD SNIPPET === //
function custom_gtm_head_snippet() {
	if ( ! defined( 'MY_GTM_ID' ) || empty( MY_GTM_ID ) ) {
		return;
	}
	?>

```
<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( MY_GTM_ID ); ?>');
</script>
<!-- End Google Tag Manager -->

<?php

}
add_action( 'wp_head', 'custom_gtm_head_snippet', 0 );

// === GTM BODY SNIPPET === //
function custom_gtm_body_snippet() {
if ( ! defined( 'MY_GTM_ID' ) || empty( MY_GTM_ID ) ) {
return;
}
?> <!-- Google Tag Manager (noscript) --> <noscript> <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( MY_GTM_ID ); ?>"
 	height="0" width="0" style="display:none;visibility:hidden"></iframe> </noscript> <!-- End Google Tag Manager (noscript) -->
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













?>
