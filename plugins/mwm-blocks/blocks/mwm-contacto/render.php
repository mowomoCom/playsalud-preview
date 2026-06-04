<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'              => 'Contacto',
	'title'                => 'Solicita informacion para tu institucion.',
	'titlePartThin'        => '',
	'titlePartBold'        => '',
	'titleAccent'          => '',
	'lead'                 => 'Cuentanos que necesitas y te contactamos en 1 o 2 dias laborables.',
	'features'             => array(
		array(
			'icon'  => 'sun',
			'title' => 'Respuesta en 48 h',
			'text'  => 'Te contestamos dentro de los dos primeros dias laborables.',
		),
		array(
			'icon'  => 'message',
			'title' => 'Demo personalizada',
			'text'  => 'Si encajamos, te mostramos como PlaySalud se adapta a tu centro.',
		),
		array(
			'icon'  => 'check',
			'title' => 'Sin compromiso',
			'text'  => 'El primer contacto es gratuito. Sin venta agresiva.',
		),
	),
	'contactFormShortcode' => '',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$section_id = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'contacto';
$contact_form_shortcode = trim( (string) $attributes['contactFormShortcode'] );
$legacy_title = trim( wp_strip_all_tags( (string) $attributes['title'] ) );
$title_part_thin = trim( wp_strip_all_tags( (string) $attributes['titlePartThin'] ) );
$title_part_bold = trim( wp_strip_all_tags( (string) $attributes['titlePartBold'] ) );
$title_accent = trim( wp_strip_all_tags( (string) $attributes['titleAccent'] ) );

if ( '' === $title_part_thin && '' === $title_part_bold && '' === $title_accent ) {
	if ( 'Solicita informacion para tu institucion.' === $legacy_title ) {
		$title_part_thin = 'Solicita informacion';
		$title_part_bold = 'para tu';
		$title_accent    = 'institucion.';
	} else {
		$title_part_thin = $legacy_title;
	}
}

$has_segmented_title = '' !== $title_part_thin || '' !== $title_part_bold || '' !== $title_accent;
$feature_defaults = $defaults['features'];
$features = isset( $attributes['features'] ) && is_array( $attributes['features'] ) ? $attributes['features'] : array();
if ( empty( $features ) ) {
	$features = $feature_defaults;
}

$feature_icon_svg = array(
	'sun'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M12 2v6M12 22v-6M4.93 4.93l4.24 4.24M14.83 14.83l4.24 4.24M2 12h6M22 12h-6M4.93 19.07l4.24-4.24M14.83 9.17l4.24-4.24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
	'message' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>',
	'check'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'clock'   => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.5"/><path d="M12 7.5v5l3 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'user'    => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8.5" r="3.5" stroke="currentColor" stroke-width="1.5"/><path d="M5 19c0-3 2.6-5 7-5s7 2 7 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
);

$allowed_svg_tags = array(
	'svg'    => array(
		'width'            => true,
		'height'           => true,
		'viewbox'          => true,
		'fill'             => true,
		'xmlns'            => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'aria-hidden'      => true,
		'focusable'        => true,
		'role'             => true,
	),
	'path'   => array(
		'd'                => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
	),
	'circle' => array(
		'cx'               => true,
		'cy'               => true,
		'r'                => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
	),
);
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-contacto',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container mwm-contacto__grid">
		<div class="mwm-contacto__side">
			<p class="mwm-eyebrow mwm-eyebrow-contacto"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h2 class="section-title mwm-contacto__title">
				<?php if ( $has_segmented_title ) : ?>
					<?php if ( '' !== $title_part_thin ) : ?>
						<span class="t-thin"><?php echo esc_html( $title_part_thin . ' ' ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $title_part_bold ) : ?>
						<span class="t-bold"><?php echo esc_html( $title_part_bold ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $title_accent ) : ?>
						<span class="accent"><?php echo esc_html( ' ' . $title_accent ); ?></span>
					<?php endif; ?>
				<?php else : ?>
					<?php echo esc_html( $legacy_title ); ?>
				<?php endif; ?>
			</h2>
			<p class="section-subtitle"><?php echo wp_kses_post( $attributes['lead'] ); ?></p>

			<div class="contacto-features">
				<?php foreach ( $features as $feature ) : ?>
					<?php
					$feature = wp_parse_args(
						is_array( $feature ) ? $feature : array(),
						array(
							'icon'  => 'sun',
							'title' => '',
							'text'  => '',
						)
					);
					$feature_icon = isset( $feature_icon_svg[ $feature['icon'] ] ) ? $feature_icon_svg[ $feature['icon'] ] : $feature_icon_svg['sun'];
					?>
					<div class="contacto-feature">
						<div class="contacto-feature-icon">
							<?php echo wp_kses( $feature_icon, $allowed_svg_tags ); ?>
						</div>
						<div>
							<?php if ( '' !== trim( (string) $feature['title'] ) ) : ?>
								<div class="contacto-feature-title"><?php echo esc_html( $feature['title'] ); ?></div>
							<?php endif; ?>
							<?php if ( '' !== trim( (string) $feature['text'] ) ) : ?>
								<div class="contacto-feature-text"><?php echo esc_html( $feature['text'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="mwm-form-card mwm-contacto__form">
			<?php if ( '' !== $contact_form_shortcode ) : ?>
				<?php echo do_shortcode( $contact_form_shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<p class="mwm-contacto__shortcode-empty">Configura el shortcode de Contact Form 7 en los ajustes del bloque.</p>
			<?php endif; ?>
		</div>
	</div>
</section>
