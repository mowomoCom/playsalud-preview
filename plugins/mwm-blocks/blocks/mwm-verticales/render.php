<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mwm_verticales_get_webp_url' ) ) {
	/**
	 * Return matching WebP URL when the file exists.
	 *
	 * @param string $image_url Source image URL.
	 * @return string
	 */
	function mwm_verticales_get_webp_url( $image_url ) {
		if ( empty( $image_url ) || ! is_string( $image_url ) ) {
			return '';
		}

		if ( preg_match( '/\.webp$/i', $image_url ) ) {
			return $image_url;
		}

		$upload_dir = wp_get_upload_dir();
		if ( empty( $upload_dir['baseurl'] ) || empty( $upload_dir['basedir'] ) ) {
			return '';
		}

		if ( 0 !== strpos( $image_url, $upload_dir['baseurl'] ) ) {
			return '';
		}

		$webp_url  = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $image_url );
		$file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $webp_url );

		if ( $webp_url && $file_path && file_exists( $file_path ) ) {
			return $webp_url;
		}

		return '';
	}
}

$defaults = array(
	'eyebrow'                    => 'Dos brazos, un lenguaje',
	'title'                      => 'PlayCare y PlayAcademy.',
	'subtitle'                   => 'Dos verticales complementarias. Mismo compromiso editorial. Misma exigencia visual.',
	'careTag'                    => 'PlayCare',
	'careTitle'                  => 'Videos de cuidados de salud por modulos.',
	'careText'                   => 'Contenido audiovisual organizado para instituciones que quieren ofrecer informacion visual a sus pacientes y profesionales.',
	'careImageId'                => 0,
	'careImageUrl'               => '',
	'careImageAlt'               => 'Videos de cuidados de salud por modulos',
	'careButtonText'             => 'Ver modulos',
	'careButtonUrl'              => '#modulos',
	'careSecondaryButtonText'    => 'Solicitar informacion',
	'careSecondaryButtonUrl'     => '#contacto',
	'academyTag'                 => 'PlayAcademy',
	'academyTitle'               => 'Aprendizaje visual y practico.',
	'academyText'                => 'Cursos acreditados para sanitarios. Abrimos con Entender la IA en Salud desde cero, disenado para asimilar sin abandono.',
	'academyImageId'             => 0,
	'academyImageUrl'            => '',
	'academyImageAlt'            => 'Aprendizaje visual y practico',
	'academyButtonText'          => 'Ver curso',
	'academyButtonUrl'           => '#curso',
	'academySecondaryButtonText' => 'Solicitar informacion',
	'academySecondaryButtonUrl'  => '#contacto',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );

$care_image_url = ! empty( $attributes['careImageUrl'] )
	? $attributes['careImageUrl']
	: get_stylesheet_directory_uri() . '/assets/images/vert-care-real.jpg';

$academy_image_url = ! empty( $attributes['academyImageUrl'] )
	? $attributes['academyImageUrl']
	: get_stylesheet_directory_uri() . '/assets/images/vert-academy-real.jpg';

$cards = array(
	array(
		'tag'            => $attributes['careTag'],
		'tagClass'       => 'is-care',
		'title'          => $attributes['careTitle'],
		'text'           => $attributes['careText'],
		'imageUrl'       => $care_image_url,
		'imageAlt'       => $attributes['careImageAlt'],
		'primaryText'    => $attributes['careButtonText'],
		'primaryUrl'     => $attributes['careButtonUrl'],
		'primaryClass'   => 'mwm-btn--sky',
		'secondaryText'  => $attributes['careSecondaryButtonText'],
		'secondaryUrl'   => $attributes['careSecondaryButtonUrl'],
	),
	array(
		'tag'            => $attributes['academyTag'],
		'tagClass'       => 'is-academy',
		'title'          => $attributes['academyTitle'],
		'text'           => $attributes['academyText'],
		'imageUrl'       => $academy_image_url,
		'imageAlt'       => $attributes['academyImageAlt'],
		'primaryText'    => $attributes['academyButtonText'],
		'primaryUrl'     => $attributes['academyButtonUrl'],
		'primaryClass'   => 'mwm-btn--primary',
		'secondaryText'  => $attributes['academySecondaryButtonText'],
		'secondaryUrl'   => $attributes['academySecondaryButtonUrl'],
	),
);

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => 'verticales',
		'class' => 'mwm-home-section mwm-verticales',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2 class="mwm-verticales__title"><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<p class="mwm-verticales__subtitle"><?php echo wp_kses_post( $attributes['subtitle'] ); ?></p>
		<div class="mwm-verticales__grid">
			<?php foreach ( $cards as $card ) : ?>
				<?php $webp_url = mwm_verticales_get_webp_url( $card['imageUrl'] ); ?>
				<article class="mwm-verticales__card">
					<div class="mwm-verticales__photo-wrap">
						<span class="mwm-verticales__photo-tag <?php echo esc_attr( $card['tagClass'] ); ?>">
							<?php echo esc_html( $card['tag'] ); ?>
						</span>
						<picture class="mwm-verticales__photo">
							<?php if ( ! empty( $webp_url ) ) : ?>
								<source srcset="<?php echo esc_url( $webp_url ); ?>" type="image/webp">
							<?php endif; ?>
							<img src="<?php echo esc_url( $card['imageUrl'] ); ?>" alt="<?php echo esc_attr( $card['imageAlt'] ); ?>" loading="lazy" decoding="async">
						</picture>
					</div>
					<div class="mwm-verticales__content">
						<h3 class="mwm-verticales__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
						<p class="mwm-verticales__card-desc"><?php echo wp_kses_post( $card['text'] ); ?></p>
						<div class="mwm-verticales__cta-row">
							<a href="<?php echo esc_url( $card['primaryUrl'] ); ?>" class="mwm-btn <?php echo esc_attr( $card['primaryClass'] ); ?> mwm-btn--md">
								<?php echo esc_html( $card['primaryText'] ); ?>
								<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
									<path d="M3 8h10m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</a>
							<a href="<?php echo esc_url( $card['secondaryUrl'] ); ?>" class="mwm-btn mwm-btn--ghost mwm-btn--md"><?php echo esc_html( $card['secondaryText'] ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
