<?php
/**
 * Plugin Name:       GetFreeCarCheck Free Car Check
 * Plugin URI:        https://getfreecarcheck.co.uk/developers/widget
 * Description:       Add a free UK car check box (MOT history, mileage, ULEZ, tax and recalls by number plate) or a car reliability card to any page, post or widget area.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            GetFreeCarCheck
 * Author URI:        https://getfreecarcheck.co.uk
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       getfreecarcheck
 *
 * The boxes are iframes from getfreecarcheck.co.uk (sizes and markup in step with src/lib/embed.ts in the site repo).
 * No script is added to the host page. The credit link under a box is off until the site owner turns it on.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const GETFREECARCHECK_BASE = 'https://getfreecarcheck.co.uk';

/** Fixed sizes: each box fits any width from 280 to 420 px at this height. */
function getfreecarcheck_size( $kind ) {
	return 'reliability' === $kind ? array( 420, 510 ) : array( 420, 256 );
}

function getfreecarcheck_slug( $s ) {
	return trim( preg_replace( '/[^a-z0-9]+/', '-', strtolower( (string) $s ) ), '-' );
}

function getfreecarcheck_site_tag() {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	return substr( preg_replace( '/[^a-z0-9.-]+/', '-', strtolower( (string) $host ) ), 0, 60 );
}

/** The box: an iframe, plus the credit line when the owner has switched it on. */
function getfreecarcheck_box( $kind, $make = '', $model = '' ) {
	$kind = 'reliability' === $kind ? 'reliability' : 'check';
	$make = getfreecarcheck_slug( $make );
	$model = getfreecarcheck_slug( $model );
	if ( 'reliability' === $kind && ( '' === $make || '' === $model ) ) {
		return '';
	}
	list( $w, $h ) = getfreecarcheck_size( $kind );
	$path  = 'check' === $kind ? '/embed/check' : '/embed/reliability/' . $make . '/' . $model;
	$src   = add_query_arg( 'site', rawurlencode( getfreecarcheck_site_tag() ), GETFREECARCHECK_BASE . $path );
	$title = 'check' === $kind ? __( 'Free car check', 'getfreecarcheck' ) : __( 'Car reliability', 'getfreecarcheck' );
	$html  = sprintf(
		'<iframe src="%s" title="%s" loading="lazy" width="100%%" height="%d" frameborder="0" style="max-width:%dpx;border:0"></iframe>',
		esc_url( $src ),
		esc_attr( $title ),
		(int) $h,
		(int) $w
	);
	if ( get_option( 'getfreecarcheck_credit', '0' ) === '1' ) {
		$href  = 'check' === $kind ? GETFREECARCHECK_BASE . '/' : GETFREECARCHECK_BASE . '/cars/' . $make . '/' . $model;
		$label = 'check' === $kind ? __( 'Free car check by', 'getfreecarcheck' ) : __( 'Source:', 'getfreecarcheck' );
		$html .= sprintf( '<p style="margin:6px 0 0;font-size:13px">%s <a href="%s">GetFreeCarCheck</a></p>', esc_html( $label ), esc_url( $href ) );
	}
	return '<div class="getfreecarcheck-box">' . $html . '</div>';
}

/** [getfreecarcheck] and [getfreecarcheck_reliability make="ford" model="fiesta"] */
add_shortcode(
	'getfreecarcheck',
	function () {
		return getfreecarcheck_box( 'check' );
	}
);
add_shortcode(
	'getfreecarcheck_reliability',
	function ( $atts ) {
		$a = shortcode_atts( array( 'make' => '', 'model' => '' ), $atts, 'getfreecarcheck_reliability' );
		return getfreecarcheck_box( 'reliability', $a['make'], $a['model'] );
	}
);

/** A widget for sidebars and footers. */
class GetFreeCarCheck_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct( 'getfreecarcheck', __( 'Free Car Check (GetFreeCarCheck)', 'getfreecarcheck' ), array( 'description' => __( 'A free UK car check box, or one model\'s MOT reliability.', 'getfreecarcheck' ) ) );
	}

	public function widget( $args, $instance ) {
		$box = getfreecarcheck_box( $instance['kind'] ?? 'check', $instance['make'] ?? '', $instance['model'] ?? '' );
		if ( '' === $box ) {
			return;
		}
		echo $args['before_widget'] . $box . $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in getfreecarcheck_box().
	}

	public function form( $instance ) {
		$kind  = $instance['kind'] ?? 'check';
		$make  = $instance['make'] ?? '';
		$model = $instance['model'] ?? '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'kind' ) ); ?>"><?php esc_html_e( 'Box', 'getfreecarcheck' ); ?></label>
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'kind' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'kind' ) ); ?>">
				<option value="check" <?php selected( $kind, 'check' ); ?>><?php esc_html_e( 'Free car check', 'getfreecarcheck' ); ?></option>
				<option value="reliability" <?php selected( $kind, 'reliability' ); ?>><?php esc_html_e( 'Car reliability card', 'getfreecarcheck' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'make' ) ); ?>"><?php esc_html_e( 'Make (reliability card)', 'getfreecarcheck' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'make' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'make' ) ); ?>" value="<?php echo esc_attr( $make ); ?>" placeholder="Ford">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'model' ) ); ?>"><?php esc_html_e( 'Model (reliability card)', 'getfreecarcheck' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'model' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'model' ) ); ?>" value="<?php echo esc_attr( $model ); ?>" placeholder="Fiesta">
		</p>
		<?php
	}

	public function update( $new, $old ) {
		return array(
			'kind'  => 'reliability' === ( $new['kind'] ?? '' ) ? 'reliability' : 'check',
			'make'  => sanitize_text_field( $new['make'] ?? '' ),
			'model' => sanitize_text_field( $new['model'] ?? '' ),
		);
	}
}
add_action(
	'widgets_init',
	function () {
		register_widget( 'GetFreeCarCheck_Widget' );
	}
);

/** Settings → GetFreeCarCheck: how to use it, and the opt-in credit line. */
add_action(
	'admin_init',
	function () {
		register_setting(
			'getfreecarcheck',
			'getfreecarcheck_credit',
			array(
				'type'              => 'string',
				'default'           => '0',
				'sanitize_callback' => function ( $v ) {
					return '1' === $v ? '1' : '0';
				},
			)
		);
	}
);
add_action(
	'admin_menu',
	function () {
		add_options_page( 'GetFreeCarCheck', 'GetFreeCarCheck', 'manage_options', 'getfreecarcheck', 'getfreecarcheck_settings_page' );
	}
);
function getfreecarcheck_settings_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'GetFreeCarCheck Free Car Check', 'getfreecarcheck' ); ?></h1>
		<p><?php esc_html_e( 'Add a box with a shortcode, or from Appearance → Widgets:', 'getfreecarcheck' ); ?></p>
		<p><code>[getfreecarcheck]</code> <?php esc_html_e( 'a free car check box by number plate', 'getfreecarcheck' ); ?></p>
		<p><code>[getfreecarcheck_reliability make="ford" model="fiesta"]</code> <?php esc_html_e( 'one model\'s MOT reliability and common faults', 'getfreecarcheck' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'getfreecarcheck' ); ?>
			<label>
				<input type="checkbox" name="getfreecarcheck_credit" value="1" <?php checked( get_option( 'getfreecarcheck_credit', '0' ), '1' ); ?>>
				<?php esc_html_e( 'Show a small "GetFreeCarCheck" credit link under each box (optional, thank you if you do)', 'getfreecarcheck' ); ?>
			</label>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	function ( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=getfreecarcheck' ) ) . '">' . esc_html__( 'Settings', 'getfreecarcheck' ) . '</a>' );
		return $links;
	}
);
