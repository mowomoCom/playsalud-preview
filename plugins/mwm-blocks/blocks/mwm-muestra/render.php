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
			'title'         => 'Como cuidar tu herida postoperatoria',
			'time'          => '3:42',
			'tag'           => 'Cirugia · Nivel paciente',
			'playAriaLabel' => 'Reproducir capitulo: Como cuidar tu herida postoperatoria',
			'imageId'       => 0,
			'imageAlt'      => '',
		),
		array(
			'title'         => 'Preparacion para consulta preanestesica',
			'time'          => '4:18',
			'tag'           => 'Anestesia · Nivel paciente',
			'playAriaLabel' => 'Reproducir capitulo: Preparacion para la consulta preanestesica',
			'imageId'       => 0,
			'imageAlt'      => '',
		),
		array(
			'title'         => 'Vivir con una ostomia: primeros dias en casa',
			'time'          => '5:06',
			'tag'           => 'Ostomias · Nivel paciente',
			'playAriaLabel' => 'Reproducir capitulo: Vivir con una ostomia: primeros dias en casa',
			'imageId'       => 0,
			'imageAlt'      => '',
		),
		array(
			'title'         => 'Entender el cancer colorrectal: diagnostico y tratamiento',
			'time'          => '6:24',
			'tag'           => 'Cancer colorrectal · Nivel paciente',
			'playAriaLabel' => 'Reproducir capitulo: Entender el cancer colorrectal',
			'imageId'       => 0,
			'imageAlt'      => '',
		),
	),
	'prevLabel' => 'Anterior',
	'nextLabel' => 'Siguiente',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
$section_id = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'muestra';
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
		<div class="mwm-muestra__header">	
			<p class="mwm-eyebrow mwm-muestra__eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h2 class="mwm-muestra__title">
				<span class="mwm-muestra__title-light"><?php echo wp_kses_post( $attributes['titleLight'] ); ?></span>
				<span class="mwm-muestra__title-bold"><?php echo wp_kses_post( $attributes['titleBold'] ); ?></span>
			</h2>
			<p class="mwm-muestra__description"><?php echo wp_kses_post( $attributes['description'] ); ?></p>
		</div>
		<div class="mwm-muestra__carousel">
			<div id="<?php echo esc_attr( $swiper_id ); ?>" class="swiper mwm-muestra__swiper">
				<div class="swiper-wrapper">
					<?php foreach ( $items as $item ) : ?>
						<?php
						$image_id = isset( $item['imageId'] ) ? absint( $item['imageId'] ) : 0;
						if ( ! $image_id ) {
							continue;
						}

						$image_url = wp_get_attachment_image_url( $image_id, 'full' );
						if ( ! $image_url ) {
							continue;
						}

						$slide_title = isset( $item['title'] ) ? (string) $item['title'] : '';
						$slide_time  = isset( $item['time'] ) ? (string) $item['time'] : '';
						$slide_tag   = isset( $item['tag'] ) ? (string) $item['tag'] : '';
						$play_aria   = isset( $item['playAriaLabel'] ) ? (string) $item['playAriaLabel'] : '';
						$slide_alt   = isset( $item['imageAlt'] ) ? (string) $item['imageAlt'] : '';
						if ( '' === trim( $slide_alt ) ) {
							$slide_alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
						}
						if ( '' === trim( $slide_alt ) ) {
							$slide_alt = $slide_title;
						}
						?>
						<div class="swiper-slide">
							<div class="mwm-muestra__slide">
								<div class="mwm-muestra__player" role="button" tabindex="0" aria-label="<?php echo esc_attr( $play_aria ? $play_aria : 'Reproducir capitulo' ); ?>">
									<figure class="mwm-muestra__slide-image">
										<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $slide_alt ); ?>" />
									</figure>
									<div class="mwm-muestra__play">
										<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
									</div>
								</div>
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
				<div class="swiper-pagination mwm-muestra__pagination"></div>
			</div>
		</div>
	</div>
</section>
