<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helpers compartidos para render de bloques MWM.
 * Se deja preparado para evolucionar callbacks PHP centralizados.
 */
function mwm_blocks_escape_multiline( $text ) {
	return nl2br( esc_html( (string) $text ) );
}
