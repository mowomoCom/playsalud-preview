<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'   => 'Capitulos de muestra',
	'title'     => 'Mira algunos capitulos completos.',
	'items'     => array(
		array(
			'title'    => 'Como cuidar tu herida postoperatoria',
			'imageId'  => 0,
			'imageAlt' => '',
		),
		array(
			'title'    => 'Preparacion para consulta preanestesica',
			'imageId'  => 0,
			'imageAlt' => '',
		),
		array(
			'title'    => 'Vivir con una ostomia',
			'imageId'  => 0,
			'imageAlt' => '',
		),
		array(
			'title'    => 'Entender el cancer colorrectal',
			'imageId'  => 0,
			'imageAlt' => '',
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
				<?php echo wp_kses_post( $attributes['title'] ); ?>
			</h2>
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
						$slide_alt   = isset( $item['imageAlt'] ) ? (string) $item['imageAlt'] : '';
						if ( '' === trim( $slide_alt ) ) {
							$slide_alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
						}
						if ( '' === trim( $slide_alt ) ) {
							$slide_alt = $slide_title;
						}
						?>
						<div class="swiper-slide">
							<figure class="mwm-muestra__slide-image">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $slide_alt ); ?>" />
							</figure>
							<article class="mwm-muestra__slide">
								<span><?php echo esc_html( $slide_title ); ?></span>
							</article>
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
