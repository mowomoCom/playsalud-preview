<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
'eyebrow'     => 'Capitulos de muestra',
'titleLight'  => 'Mira algunos',
'titleBold'   => 'capitulos completos.',
'description' => 'Videos reales del catalogo PlayCare, sin filtros ni edicion para la demo.',
	'items'     => array(
		array(
			'title'    => 'Como cuidar tu herida postoperatoria',
			'time'     => '',
			'tag'      => 'Cirugia · Nivel paciente',
			'imageId'      => 0,
			'imageAlt'     => '',
			'imageUrl'     => '',
			'videoFileId'  => 0,
			'videoFileUrl' => '',
			'videoUrl'     => '',
		),
		array(
			'title'    => 'Preparacion para consulta preanestesica',
			'time'     => '',
			'tag'      => 'Anestesia · Nivel paciente',
			'imageId'      => 0,
			'imageAlt'     => '',
			'imageUrl'     => '',
			'videoFileId'  => 0,
			'videoFileUrl' => '',
			'videoUrl'     => '',
		),
		array(
			'title'    => 'Vivir con una ostomia: primeros dias en casa',
			'time'     => '',
			'tag'      => 'Ostomias · Nivel paciente',
			'imageId'      => 0,
			'imageAlt'     => '',
			'imageUrl'     => '',
			'videoFileId'  => 0,
			'videoFileUrl' => '',
			'videoUrl'     => '',
		),
		array(
			'title'    => 'Entender el cancer colorrectal: diagnostico y tratamiento',
			'time'     => '',
			'tag'      => 'Cancer colorrectal · Nivel paciente',
			'imageId'      => 0,
			'imageAlt'     => '',
			'imageUrl'     => '',
			'videoFileId'  => 0,
			'videoFileUrl' => '',
			'videoUrl'     => '',
		),
	),
	'prevLabel' => 'Anterior',
	'nextLabel' => 'Siguiente',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
$block_anchor = mwm_blocks_get_block_anchor( $block );
$attribute_anchor = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : '';
$section_id = '' !== $block_anchor ? $block_anchor : ( '' !== $attribute_anchor ? $attribute_anchor : 'muestra' );
$swiper_id  = wp_unique_id( 'mwm-muestra-swiper-' );
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-muestra',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<div class="mwm-muestra__header reveal">	
			<p class="mwm-eyebrow mwm-muestra__eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h2 class="mwm-muestra__title">
				<span class="mwm-muestra__title-light"><?php echo wp_kses_post( $attributes['titleLight'] ); ?></span>
				<span class="mwm-muestra__title-bold"><?php echo wp_kses_post( $attributes['titleBold'] ); ?></span>
			</h2>
			<p class="mwm-muestra__description"><?php echo wp_kses_post( $attributes['description'] ); ?></p>
		</div>
		<div class="mwm-muestra__carousel reveal">
			<div id="<?php echo esc_attr( $swiper_id ); ?>" class="swiper mwm-muestra__swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $items as $item ) : ?>
						<?php
						$video_source = mwm_blocks_resolve_muestra_video_source( $item );

						if ( ! is_array( $video_source ) || empty( $video_source['url'] ) ) {
							continue;
						}

						$poster = mwm_blocks_get_muestra_slide_poster( $item );

						if ( ! is_array( $poster ) || empty( $poster['url'] ) ) {
							continue;
						}

						$image_url   = (string) $poster['url'];
						$slide_alt   = (string) $poster['alt'];
						$slide_title = isset( $item['title'] ) ? (string) $item['title'] : '';
						$slide_time  = isset( $item['time'] ) ? (string) $item['time'] : '';
						$slide_tag   = isset( $item['tag'] ) ? (string) $item['tag'] : '';
						$play_aria   = mwm_blocks_get_muestra_play_aria_label( $slide_title );
						?>
						<div class="swiper-slide">
							<div class="mwm-muestra__slide">
								<a
									class="mwm-muestra__player mwm-muestra__player-link"
									href="<?php echo esc_url( $video_source['url'] ); ?>"
									data-fancybox="mwm-muestra-video"
									<?php if ( ! empty( $video_source['fancybox_type'] ) ) : ?>
										data-type="<?php echo esc_attr( $video_source['fancybox_type'] ); ?>"
									<?php endif; ?>
									aria-label="<?php echo esc_attr( $play_aria ); ?>"
								>
									<figure class="mwm-muestra__slide-image">
										<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $slide_alt ); ?>" />
									</figure>
									<div class="mwm-muestra__play">
										<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
									</div>
								</a>
								<div class="mwm-muestra__meta-row">
									<span class="mwm-muestra__video-title"><?php echo esc_html( $slide_title ); ?></span>
									<span class="mwm-muestra__meta-dot"></span>
									<span class="mwm-muestra__time"><?php echo esc_html( $slide_time ); ?></span>
									<span class="mwm-muestra__meta-dot"></span>
									<span class="mwm-muestra__tag"><?php echo esc_html( $slide_tag ); ?></span>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="swiper-pagination mwm-muestra__pagination"></div>
			</div>
			<button
				id="<?php echo esc_attr( $swiper_id . '-prev' ); ?>"
				type="button"
				class="swiper-button-prev mwm-muestra__arrow mwm-muestra__arrow--prev"
				aria-label="<?php echo esc_attr( $attributes['prevLabel'] ); ?>"
				aria-controls="<?php echo esc_attr( $swiper_id ); ?>"
			></button>
			<button
				id="<?php echo esc_attr( $swiper_id . '-next' ); ?>"
				type="button"
				class="swiper-button-next mwm-muestra__arrow mwm-muestra__arrow--next"
				aria-label="<?php echo esc_attr( $attributes['nextLabel'] ); ?>"
				aria-controls="<?php echo esc_attr( $swiper_id ); ?>"
			></button>
		</div>
	</div>
</section>
