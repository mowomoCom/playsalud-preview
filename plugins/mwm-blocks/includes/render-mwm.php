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

/**
 * Obtiene el anchor del bloque de forma compatible (WP_Block o array).
 *
 * @param mixed $block Contexto de bloque recibido por render callback.
 * @return string
 */
function mwm_blocks_get_block_anchor( $block ) {
	$anchor = '';

	if ( is_array( $block ) && isset( $block['anchor'] ) ) {
		$anchor = $block['anchor'];
	} elseif ( $block instanceof WP_Block ) {
		if ( isset( $block->parsed_block['attrs']['anchor'] ) ) {
			$anchor = $block->parsed_block['attrs']['anchor'];
		} elseif ( isset( $block->attributes['anchor'] ) ) {
			$anchor = $block->attributes['anchor'];
		}
	} elseif ( is_object( $block ) && isset( $block->anchor ) ) {
		$anchor = $block->anchor;
	}

	if ( '' === trim( (string) $anchor ) ) {
		return '';
	}

	return sanitize_title( (string) $anchor );
}
