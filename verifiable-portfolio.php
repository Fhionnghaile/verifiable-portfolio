<?php
/**
 * Plugin Name:       Verifiable Portfolio
 * Plugin URI:        https://example.com/plugins/verifiable-portfolio
 * Description:       A public, auto-generated portfolio / identity page rendered from a filterable profile (competencies, projects, certifications). Decoupled from any record system — supply data via the `verifiable_portfolio_profile` filter.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Pádraig Fhionnghaile
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       verifiable-portfolio
 *
 * Distilled and generalised from a production module for public release.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once __DIR__ . '/includes/profile.php';

add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'verifiable-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

/* Auto-create the /portfolio page (idempotent). */
add_action( 'admin_init', function () {
	if ( get_page_by_path( 'portfolio' ) ) { return; }
	wp_insert_post( array(
		'post_title'   => __( 'Portfolio', 'verifiable-portfolio' ),
		'post_name'    => 'portfolio',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '[verifiable_portfolio]',
	) );
} );

add_shortcode( 'verifiable_portfolio', function ( $atts ) {
	$atts    = shortcode_atts( array( 'id' => '' ), $atts, 'verifiable_portfolio' );
	$profile = verifiable_portfolio_get_profile( $atts['id'] );

	ob_start();
	?>
	<article class="vp" aria-label="<?php esc_attr_e( 'Portfolio', 'verifiable-portfolio' ); ?>">
		<header class="vp-head">
			<h1><?php echo esc_html( $profile['name'] ); ?></h1>
			<p class="vp-headline"><?php echo esc_html( $profile['headline'] ); ?></p>
			<p class="vp-meta">
				<span><?php echo esc_html( $profile['target_role'] ); ?></span>
				<span><?php echo esc_html( $profile['availability'] ); ?></span>
				<span><?php echo esc_html( $profile['location'] ); ?></span>
			</p>
		</header>

		<section aria-label="<?php esc_attr_e( 'Competencies', 'verifiable-portfolio' ); ?>">
			<h2><?php esc_html_e( 'Competencies', 'verifiable-portfolio' ); ?></h2>
			<ul class="vp-bars">
				<?php foreach ( $profile['competencies'] as $label => $score ) :
					$score = verifiable_portfolio_clamp_score( $score ); ?>
					<li>
						<span class="vp-label"><?php echo esc_html( $label ); ?></span>
						<span class="vp-bar" role="img"
							aria-label="<?php echo esc_attr( sprintf( '%s: %d of 100', $label, $score ) ); ?>">
							<span class="vp-fill" style="width:<?php echo esc_attr( $score ); ?>%"></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
			<p class="vp-overall">
				<?php printf( esc_html__( 'Overall readiness: %d / 100', 'verifiable-portfolio' ),
					verifiable_portfolio_overall( $profile['competencies'] ) ); ?>
			</p>
		</section>

		<section aria-label="<?php esc_attr_e( 'Projects', 'verifiable-portfolio' ); ?>">
			<h2><?php esc_html_e( 'Projects', 'verifiable-portfolio' ); ?></h2>
			<?php foreach ( $profile['projects'] as $p ) : ?>
				<div class="vp-project">
					<h3><?php echo esc_html( $p['title'] ); ?></h3>
					<p><?php echo esc_html( $p['description'] ); ?></p>
					<?php if ( ! empty( $p['link'] ) ) : ?>
						<p><a href="<?php echo esc_url( $p['link'] ); ?>" rel="nofollow noopener">
							<?php esc_html_e( 'View project', 'verifiable-portfolio' ); ?></a></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</section>

		<section aria-label="<?php esc_attr_e( 'Certifications', 'verifiable-portfolio' ); ?>">
			<h2><?php esc_html_e( 'Certifications', 'verifiable-portfolio' ); ?></h2>
			<ul>
				<?php foreach ( $profile['certifications'] as $c ) : ?>
					<li><?php echo esc_html( $c['name'] ); ?> — <?php echo esc_html( $c['issuer'] ); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>
	</article>
	<?php
	return ob_get_clean();
} );

add_action( 'wp_head', function () {
	echo "<style id='vp-css'>.vp{max-width:72ch;margin:0 auto;font-family:system-ui,sans-serif;line-height:1.6}"
	   . ".vp-meta span{display:inline-block;margin-right:1em;color:#55607a;font-size:.95em}"
	   . ".vp-bars{list-style:none;padding:0}.vp-bars li{display:flex;align-items:center;gap:12px;margin:.4em 0}"
	   . ".vp-label{flex:0 0 14em}.vp-bar{flex:1;background:#eee;border-radius:6px;height:12px;overflow:hidden}"
	   . ".vp-fill{display:block;height:100%;background:#1f9d6b}.vp-project{margin:1em 0;padding:.6em 0;border-bottom:1px solid #eee}</style>\n";
}, 99 );
