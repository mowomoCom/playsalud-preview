<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'          => 'Para instituciones',
	'title'            => 'Una plataforma para cada tipo de institucion.',
	'titlePartThin'    => '',
	'titlePartBold'    => '',
	'titleAccent'      => '',
	'subtitle'         => 'PlaySalud se despliega por modulos, packs tematicos, campus, intranet, QR, marca blanca o integracion en plataformas institucionales. Adaptamos la propuesta a tu organizacion.',
	'ctaPrimaryText'   => 'Hablar con el equipo comercial',
	'ctaPrimaryUrl'    => '#contacto',
	'ctaSecondaryText' => 'Solicitar propuesta B2B',
	'ctaSecondaryUrl'  => '#contacto',
	'items'            => array(
		array(
			'title'       => 'Hospitales y grupos hospitalarios',
			'description' => 'Estandariza informacion al paciente y forma a tus equipos. PlayCare por rutas clinicas + PlayAcademy para profesionales.',
			'iconUrl'     => '',
			'iconAlt'     => 'Hospitales y grupos hospitalarios',
		),
		array(
			'title'       => 'Aseguradoras',
			'description' => 'Acompana al asegurado y mejora la experiencia con modulos PlayCare por procesos frecuentes y bonos de acceso.',
			'iconUrl'     => '',
			'iconAlt'     => 'Aseguradoras',
		),
		array(
			'title'       => 'Universidades',
			'description' => 'Formacion visual, moderna y acreditable. PlayAcademy integrado en LMS, Moodle o campus institucional.',
			'iconUrl'     => '',
			'iconAlt'     => 'Universidades',
		),
		array(
			'title'       => 'Sociedades cientificas',
			'description' => 'Ofrece formacion y contenido de valor a tus socios. Cursos co-brandeados y modulos especializados.',
			'iconUrl'     => '',
			'iconAlt'     => 'Sociedades cientificas',
		),
		array(
			'title'       => 'Asociaciones de pacientes',
			'description' => 'Informacion fiable, clara y accesible. Modulos PlayCare abiertos o patrocinados para tus colectivos.',
			'iconUrl'     => '',
			'iconAlt'     => 'Asociaciones de pacientes',
		),
		array(
			'title'       => 'Industria sanitaria responsable',
			'description' => 'Patrocina educacion sanitaria con impacto. Licencia de modulos, becas, bonos o acceso abierto patrocinado.',
			'iconUrl'     => '',
			'iconAlt'     => 'Industria sanitaria responsable',
		),
	),
);

$raw_attributes = is_array( $attributes ) ? $attributes : array();
$attributes     = wp_parse_args( $raw_attributes, $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
$legacy_title = trim( wp_strip_all_tags( (string) $attributes['title'] ) );
$title_part_thin = trim( wp_strip_all_tags( (string) $attributes['titlePartThin'] ) );
$title_part_bold = trim( wp_strip_all_tags( (string) $attributes['titlePartBold'] ) );
$title_accent    = trim( wp_strip_all_tags( (string) $attributes['titleAccent'] ) );

if ( '' === $title_part_thin && '' === $title_part_bold && '' === $title_accent ) {
	if ( 'Una plataforma para cada tipo de institucion.' === $legacy_title ) {
		$title_part_thin = 'Una plataforma para';
		$title_part_bold = 'cada tipo de';
		$title_accent    = 'institucion.';
	} else {
		$title_part_thin = $legacy_title;
	}
}

$has_segmented_title = '' !== $title_part_thin || '' !== $title_part_bold || '' !== $title_accent;
$block_anchor = mwm_blocks_get_block_anchor( $block );
$attribute_anchor = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : '';
$section_id = '' !== $block_anchor ? $block_anchor : ( '' !== $attribute_anchor ? $attribute_anchor : 'instituciones' );
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-instituciones',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<div class="section-header reveal">
			<div class="mwm-eyebrow mwm-eyebrow-instituciones"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></div>
			<h2 class="section-title mwm-instituciones__title">
				<?php if ( $has_segmented_title ) : ?>
					<?php if ( '' !== $title_part_thin ) : ?>
						<span class="t-thin"><?php echo esc_html( $title_part_thin . ' ' ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $title_part_bold ) : ?>
						<span class="accent"><?php echo esc_html( $title_part_bold ); ?></span>
					<?php endif; ?>
					<?php if ( '' !== $title_accent ) : ?>
						<span class="accent"><?php echo esc_html( ' ' . $title_accent ); ?></span>
					<?php endif; ?>
				<?php else : ?>
					<?php echo esc_html( $legacy_title ); ?>
				<?php endif; ?>
			</h2>
			<p class="section-subtitle"><?php echo wp_kses_post( $attributes['subtitle'] ); ?></p>
		</div>

		<div class="inst-grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$item_title       = isset( $item['title'] ) ? (string) $item['title'] : '';
				$item_description = isset( $item['description'] ) ? (string) $item['description'] : '';
				$item_icon_url    = isset( $item['iconUrl'] ) ? (string) $item['iconUrl'] : '';
				$item_icon_alt    = isset( $item['iconAlt'] ) ? (string) $item['iconAlt'] : '';
				?>
				<article class="inst-card reveal">
					<div class="inst-icon">
						<?php if ( ! empty( $item_icon_url ) ) : ?>
							<img src="<?php echo esc_url( $item_icon_url ); ?>" alt="<?php echo esc_attr( $item_icon_alt ? $item_icon_alt : $item_title ); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
					<div>
						<h3 class="inst-title"><?php echo esc_html( $item_title ); ?></h3>
						<p class="inst-desc"><?php echo esc_html( $item_description ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="inst-cta-row">
			<?php if ( ! empty( $attributes['ctaPrimaryText'] ) ) : ?>
				<a href="<?php echo esc_url( $attributes['ctaPrimaryUrl'] ); ?>" class="mwm-btn mwm-btn--md mwm-btn--primary">
					<?php echo esc_html( $attributes['ctaPrimaryText'] ); ?>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><path d="M3 8h10m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $attributes['ctaSecondaryText'] ) ) : ?>
				<a href="<?php echo esc_url( $attributes['ctaSecondaryUrl'] ); ?>" class="mwm-btn mwm-btn--ghost mwm-btn--md">
					<?php echo esc_html( $attributes['ctaSecondaryText'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
