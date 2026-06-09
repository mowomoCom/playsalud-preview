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

/**
 * Detecta y normaliza URLs de YouTube o Vimeo.
 *
 * @param string $url URL pegada en el editor.
 * @return array|null {
 *     @type string $provider youtube|vimeo
 *     @type string $id       ID del video.
 *     @type string $url      URL canonica para Fancybox.
 * }
 */
function mwm_blocks_parse_video_url( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return null;
	}

	if ( preg_match( '#(?:youtube\.com/(?:watch\?(?:[^&\s]+&)*v=|embed/|shorts/)|youtu\.be/)([a-zA-Z0-9_-]{11})#i', $url, $matches ) ) {
		$video_id = $matches[1];

		return array(
			'provider' => 'youtube',
			'id'       => $video_id,
			'url'      => 'https://www.youtube.com/watch?v=' . $video_id,
		);
	}

	if ( preg_match( '#(?:player\.)?vimeo\.com/(?:video/)?(\d+)#i', $url, $matches ) ) {
		$video_id = $matches[1];

		return array(
			'provider' => 'vimeo',
			'id'       => $video_id,
			'url'      => 'https://vimeo.com/' . $video_id,
		);
	}

	return null;
}

/**
 * Obtiene metadatos oEmbed de un video embebido (poster).
 *
 * @param array $video Datos devueltos por mwm_blocks_parse_video_url().
 * @return array{thumbnail:string}
 */
function mwm_blocks_fetch_embed_metadata( $video ) {
	if ( ! is_array( $video ) || empty( $video['provider'] ) || empty( $video['id'] ) || empty( $video['url'] ) ) {
		return array(
			'thumbnail' => '',
		);
	}

	$cache_key = 'mwm_embedmeta_v3_' . $video['provider'] . '_' . $video['id'];
	$cached    = get_transient( $cache_key );

	if ( false !== $cached && is_array( $cached ) ) {
		return $cached;
	}

	$metadata = array(
		'thumbnail' => '',
	);

	if ( 'youtube' === $video['provider'] ) {
		$metadata['thumbnail'] = 'https://img.youtube.com/vi/' . $video['id'] . '/hqdefault.jpg';
	} else {
		$oembed_url = 'https://vimeo.com/api/oembed.json?url=' . rawurlencode( (string) $video['url'] );

		$response = wp_remote_get(
			$oembed_url,
			array(
				'timeout' => 8,
				'headers' => array(
					'Accept' => 'application/json',
				),
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$body = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( is_array( $body ) && ! empty( $body['thumbnail_url'] ) ) {
				$metadata['thumbnail'] = (string) $body['thumbnail_url'];
			}
		}
	}

	if ( '' !== $metadata['thumbnail'] ) {
		set_transient( $cache_key, $metadata, WEEK_IN_SECONDS );
	}

	return $metadata;
}

/**
 * Obtiene la URL del poster de un video embebido.
 *
 * @param array $video Datos devueltos por mwm_blocks_parse_video_url().
 * @return string
 */
function mwm_blocks_fetch_embed_thumbnail( $video ) {
	$metadata = mwm_blocks_fetch_embed_metadata( $video );

	return isset( $metadata['thumbnail'] ) ? (string) $metadata['thumbnail'] : '';
}

/**
 * Genera el aria-label del boton play de muestra.
 *
 * @param string $title Titulo del slide.
 * @return string
 */
function mwm_blocks_get_muestra_play_aria_label( $title ) {
	$title = trim( (string) $title );

	if ( '' === $title ) {
		return 'Reproducir capitulo';
	}

	return 'Reproducir capitulo: ' . $title;
}

/**
 * Resuelve la fuente de video de un slide (subido o externo).
 *
 * @param array $item Item del bloque muestra.
 * @return array|null
 */
function mwm_blocks_resolve_muestra_video_source( $item ) {
	if ( ! is_array( $item ) ) {
		return null;
	}

	$video_file_id = isset( $item['videoFileId'] ) ? absint( $item['videoFileId'] ) : 0;

	if ( $video_file_id ) {
		$mime = get_post_mime_type( $video_file_id );

		if ( $mime && 0 === strpos( $mime, 'video/' ) ) {
			$file_url = wp_get_attachment_url( $video_file_id );

			if ( $file_url ) {
				return array(
					'type'          => 'html5',
					'url'           => $file_url,
					'fancybox_type' => 'html5video',
					'attachment_id' => $video_file_id,
				);
			}
		}
	}

	$external_url = isset( $item['videoUrl'] ) ? (string) $item['videoUrl'] : '';
	$embed        = mwm_blocks_parse_video_url( $external_url );

	if ( $embed ) {
		return array(
			'type'          => 'embed',
			'url'           => $embed['url'],
			'fancybox_type' => null,
			'embed'         => $embed,
		);
	}

	return null;
}

/**
 * Resuelve el poster de un slide (manual o automatico desde YouTube/Vimeo).
 *
 * @param array $item Item del bloque muestra.
 * @return array{url:string,alt:string}|null
 */
function mwm_blocks_get_muestra_slide_poster( $item ) {
	if ( ! is_array( $item ) ) {
		return null;
	}

	$title    = isset( $item['title'] ) ? (string) $item['title'] : '';
	$image_id = isset( $item['imageId'] ) ? absint( $item['imageId'] ) : 0;

	if ( $image_id ) {
		$attachment_url = wp_get_attachment_image_url( $image_id, 'full' );

		if ( $attachment_url ) {
			$alt = isset( $item['imageAlt'] ) ? trim( (string) $item['imageAlt'] ) : '';

			if ( '' === $alt ) {
				$alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			}

			if ( '' === $alt ) {
				$alt = $title;
			}

			return array(
				'url' => $attachment_url,
				'alt' => $alt,
			);
		}
	}

	$image_url = isset( $item['imageUrl'] ) ? trim( (string) $item['imageUrl'] ) : '';

	if ( '' !== $image_url ) {
		$alt = isset( $item['imageAlt'] ) ? trim( (string) $item['imageAlt'] ) : '';

		if ( '' === $alt ) {
			$alt = $title;
		}

		return array(
			'url' => $image_url,
			'alt' => $alt,
		);
	}

	$video_source = mwm_blocks_resolve_muestra_video_source( $item );

	if ( is_array( $video_source ) && 'embed' === $video_source['type'] && ! empty( $video_source['embed'] ) ) {
		$thumbnail = mwm_blocks_fetch_embed_thumbnail( $video_source['embed'] );

		if ( '' !== $thumbnail ) {
			return array(
				'url' => $thumbnail,
				'alt' => $title,
			);
		}
	}

	return null;
}
