<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Clamp a competency score into 0..100 (pure, unit-tested). */
function verifiable_portfolio_clamp_score( $score ) {
	$score = is_numeric( $score ) ? (int) round( $score ) : 0;
	return max( 0, min( 100, $score ) );
}

/** Overall readiness = rounded mean of clamped competency scores (pure). */
function verifiable_portfolio_overall( array $competencies ) {
	if ( empty( $competencies ) ) { return 0; }
	$sum = 0;
	foreach ( $competencies as $s ) { $sum += verifiable_portfolio_clamp_score( $s ); }
	return (int) round( $sum / count( $competencies ) );
}

/** Default demo profile — replaced by the `verifiable_portfolio_profile` filter. */
function verifiable_portfolio_default_profile() {
	return array(
		'name'         => 'Alex Rivera',
		'headline'     => 'Career-changer, now a job-ready software engineer.',
		'target_role'  => 'Software Engineer',
		'availability' => 'Available now',
		'location'     => 'Remote',
		'competencies' => array(
			'Core engineering'         => 78,
			'Cloud & infrastructure'   => 70,
			'Scripting & automation'   => 82,
			'Testing & quality'        => 75,
			'Communication'            => 80,
		),
		'projects' => array(
			array( 'title' => 'CI/CD pipeline', 'description' => 'Built an automated test-and-deploy pipeline for a containerised app.', 'link' => '' ),
			array( 'title' => 'REST API service', 'description' => 'Designed and shipped a documented, tested REST API.', 'link' => '' ),
		),
		'certifications' => array(
			array( 'name' => 'Example Cloud Practitioner', 'issuer' => 'Example Cloud' ),
		),
	);
}

/**
 * Resolve the profile to render. Supply real data via the filter; the default is a
 * safe demo so the plugin renders out of the box with no record-system coupling.
 *
 * @param string $id Optional profile identifier passed to the filter.
 */
function verifiable_portfolio_get_profile( $id = '' ) {
	$profile = verifiable_portfolio_default_profile();
	/**
	 * Filter the portfolio profile.
	 *
	 * @param array  $profile Default demo profile.
	 * @param string $id      Requested profile id (shortcode attribute).
	 */
	$profile = apply_filters( 'verifiable_portfolio_profile', $profile, $id );
	return wp_parse_args( $profile, verifiable_portfolio_default_profile() );
}
