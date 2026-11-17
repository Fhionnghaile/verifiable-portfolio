<?php
define( 'ABSPATH', __DIR__ );
if ( ! function_exists( 'apply_filters' ) ) { function apply_filters( $t, $v ) { return $v; } }
if ( ! function_exists( 'wp_parse_args' ) ) { function wp_parse_args( $a, $d ) { return array_merge( $d, (array) $a ); } }
require __DIR__ . '/../includes/profile.php';
$fail = 0; $n = 0;
function check( $name, $cond ) { global $fail, $n; $n++; echo ( $cond ? "  ok  " : "  FAIL" ) . " $name\n"; if ( ! $cond ) $GLOBALS['fail']++; }

check( 'clamp within range', verifiable_portfolio_clamp_score( 73 ) === 73 );
check( 'clamp high -> 100', verifiable_portfolio_clamp_score( 150 ) === 100 );
check( 'clamp negative -> 0', verifiable_portfolio_clamp_score( -5 ) === 0 );
check( 'clamp non-numeric -> 0', verifiable_portfolio_clamp_score( 'x' ) === 0 );
check( 'clamp rounds', verifiable_portfolio_clamp_score( 72.6 ) === 73 );
check( 'overall mean', verifiable_portfolio_overall( array( 'a' => 80, 'b' => 60 ) ) === 70 );
check( 'overall empty -> 0', verifiable_portfolio_overall( array() ) === 0 );
check( 'overall clamps members', verifiable_portfolio_overall( array( 'a' => 200, 'b' => 0 ) ) === 50 );
check( 'default profile has competencies', count( verifiable_portfolio_default_profile()['competencies'] ) >= 3 );

echo "\n$n checks, $fail failed\n";
exit( $fail ? 1 : 0 );
