<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'Plataforma sanitaria audiovisual',
	'title'   => 'Educacion sanitaria basada en evidencia para pacientes, profesionales e instituciones.',
	'lead'    => 'Videos breves, claros y revisados por profesionales. Listos para integrarse en hospitales, aseguradoras y universidades.',
	'imageUrl' => '',
	'imageAlt' => 'PlaySalud',
	'caption'  => '',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );

$image_url = ! empty( $attributes['imageUrl'] )
	? $attributes['imageUrl']
	: get_stylesheet_directory_uri() . '/assets/images/banner-real.jpg';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => 'banner',
		'class' => 'mwm-home-section mwm-banner',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="mwm-banner__strip">
		<figure class="mwm-banner__figure">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $attributes['imageAlt'] ); ?>">
			<?php if ( ! empty( $attributes['caption'] ) ) : ?>
				<figcaption class="mwm-banner__caption"><?php echo esc_html( $attributes['caption'] ); ?></figcaption>
			<?php endif; ?>
		</figure>
		<div class="mwm-container mwm-banner__inner">
			<p class="mwm-eyebrow">
				<span class="mwm-banner__eyebrow-dot"></span>
				<?php echo wp_kses_post( $attributes['eyebrow'] ); ?>
			</p>
			<h2 class="mwm-banner__title"><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
			<p class="mwm-banner__lead"><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
		</div>
	</div>
</section>
