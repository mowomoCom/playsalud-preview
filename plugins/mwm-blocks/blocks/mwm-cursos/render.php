<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$normalize_item = static function ( $item ) {
	$safe_item   = is_array( $item ) ? $item : array();
	$legacy_meta = isset( $safe_item['meta'] ) ? (string) $safe_item['meta'] : '';
	$chips       = array();

	if ( isset( $safe_item['chips'] ) && is_array( $safe_item['chips'] ) ) {
		foreach ( $safe_item['chips'] as $chip ) {
			$chip_text = trim( (string) $chip );
			if ( '' !== $chip_text ) {
				$chips[] = $chip_text;
			}
		}
	} elseif ( '' !== $legacy_meta ) {
		$chips = array_values(
			array_filter(
				array_map(
					'trim',
					explode( ',', $legacy_meta )
				)
			)
		);
	}

	return array(
		'status'      => isset( $safe_item['status'] ) ? (string) $safe_item['status'] : '',
		'tag'         => isset( $safe_item['tag'] ) ? (string) $safe_item['tag'] : '',
		'title'       => isset( $safe_item['title'] ) ? (string) $safe_item['title'] : '',
		'description' => isset( $safe_item['description'] ) ? (string) $safe_item['description'] : $legacy_meta,
		'chips'       => $chips,
		'imageUrl'    => isset( $safe_item['imageUrl'] ) ? (string) $safe_item['imageUrl'] : '',
		'imageAlt'    => isset( $safe_item['imageAlt'] ) ? (string) $safe_item['imageAlt'] : '',
		'ctaLabel'    => isset( $safe_item['ctaLabel'] ) ? (string) $safe_item['ctaLabel'] : '',
		'ctaUrl'      => isset( $safe_item['ctaUrl'] ) ? (string) $safe_item['ctaUrl'] : '#',
		'buttonStyle' => isset( $safe_item['buttonStyle'] ) ? (string) $safe_item['buttonStyle'] : 'ghost',
		'featured'    => ! empty( $safe_item['featured'] ),
		'upcoming'    => ! empty( $safe_item['upcoming'] ),
		'showPlay'    => array_key_exists( 'showPlay', $safe_item ) ? ! empty( $safe_item['showPlay'] ) : empty( $safe_item['upcoming'] ),
	);
};

$defaults = array(
	'eyebrow'           => 'PlayAcademy',
	'titleLight'        => 'Itinerario formativo en',
	'titleBold'         => 'IA aplicada a la salud.',
	'sectionDescription' => 'Tres cursos diseniados para profesionales sanitarios, sin necesidad de programar. El Curso I se lanza en septiembre de 2026. Los Cursos II y III llegan despues.',
	'footerButtonLabel' => 'Hablar con el equipo formativo',
	'footerButtonUrl'   => '#contacto',
	'items'             => array(
		array(
			'status'      => 'Lanzamiento sept-2026',
			'tag'         => 'Curso I · Inicial',
			'title'       => 'Entender la inteligencia artificial en salud desde cero.',
			'description' => 'Itinerario introductorio para profesionales sanitarios LOPS sin conocimientos previos de programacion. Comprender la IA, valorar herramientas, identificar riesgos y participar en proyectos con criterio clinico.',
			'chips'       => array( '56 h lectivas', '10 modulos', '3 meses', 'E-learning tutorizado', 'Acreditacion en proceso' ),
			'imageUrl'    => 'assets/academy-ia-salud.png',
			'imageAlt'    => 'Curso I - Entender la IA en salud desde cero',
			'ctaLabel'    => 'Solicita informacion del curso',
			'ctaUrl'      => '#contacto',
			'buttonStyle' => 'primary',
			'featured' => true,
			'upcoming' => false,
			'showPlay' => true,
		),
		array(
			'status'      => 'Proximamente',
			'tag'         => 'Curso II · Intermedio',
			'title'       => 'IA generativa aplicada para profesionales sanitarios.',
			'description' => 'Uso practico de IA generativa para ahorrar tiempo, comunicar mejor, enseniar mejor y trabajar con mas orden, siempre con supervision clinica y respeto a la privacidad.',
			'chips'       => array( 'Formato audiovisual', 'Practico aplicado' ),
			'imageUrl'    => 'assets/vert-academy-real.jpg',
			'imageAlt'    => 'Curso II - IA generativa aplicada',
			'ctaLabel'    => 'Avisame cuando abra',
			'ctaUrl'      => '#contacto',
			'buttonStyle' => 'ghost',
			'featured' => false,
			'upcoming' => true,
			'showPlay' => false,
		),
		array(
			'status'      => 'Proximamente',
			'tag'         => 'Curso III · Intermedio-Avanzado',
			'title'       => 'IA practica por perfiles y procesos sanitarios.',
			'description' => 'Aplicacion real de IA en consulta, hospitalizacion, quirofano, urgencias, cuidados, gestion, docencia e innovacion, por perfiles y con casos reales.',
			'chips'       => array( 'Por perfiles', 'Casos reales' ),
			'imageUrl'    => 'assets/academy-ia-salud.png',
			'imageAlt'    => 'Curso III - IA practica por perfiles y procesos',
			'ctaLabel'    => 'Avisame cuando abra',
			'ctaUrl'      => '#contacto',
			'buttonStyle' => 'ghost',
			'featured' => false,
			'upcoming' => true,
			'showPlay' => false,
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : array();
$block_anchor = mwm_blocks_get_block_anchor( $block );
$attribute_anchor = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : '';
$section_id = '' !== $block_anchor ? $block_anchor : ( '' !== $attribute_anchor ? $attribute_anchor : 'curso' );
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-cursos',
	)
);

if ( empty( $items ) ) {
	$items = $defaults['items'];
}

$normalized_items = array_map( $normalize_item, $items );
$footer_url   = ! empty( $attributes['footerButtonUrl'] ) ? (string) $attributes['footerButtonUrl'] : '#';
$footer_label = isset( $attributes['footerButtonLabel'] ) ? (string) $attributes['footerButtonLabel'] : '';
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<div class="mwm-cursos__header reveal">
			<p class="mwm-eyebrow mwm-cursos__eyebrow mwm-cursos__eyebrow--academy"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h2 class="mwm-cursos__section-title">
				<span class="mwm-cursos__title-light"><?php echo wp_kses_post( $attributes['titleLight'] ); ?></span><br>
				<span class="mwm-cursos__title-bold"><?php echo wp_kses_post( $attributes['titleBold'] ); ?></span>
			</h2>
			<?php if ( ! empty( $attributes['sectionDescription'] ) ) : ?>
				<p class="mwm-cursos__section-desc"><?php echo wp_kses_post( $attributes['sectionDescription'] ); ?></p>
			<?php endif; ?>
		</div>
		<div class="mwm-cursos__grid">
			<?php foreach ( $normalized_items as $item ) : ?>
				<?php
				$card_classes = 'mwm-cursos__card';
				if ( $item['featured'] ) {
					$card_classes .= ' mwm-cursos__card--featured';
				}
				if ( $item['upcoming'] ) {
					$card_classes .= ' mwm-cursos__card--upcoming';
				}

				$button_class = 'mwm-btn mwm-btn--md ';
				$button_class .= 'primary' === $item['buttonStyle'] ? 'mwm-btn--primary' : 'mwm-btn--ghost';
				?>
				<article class="<?php echo esc_attr( $card_classes . ' reveal' ); ?>">
					<div class="mwm-cursos__media">
						<?php if ( ! empty( $item['status'] ) ) : ?>
							<span class="mwm-cursos__status<?php echo $item['upcoming'] ? ' is-upcoming' : ''; ?>"><?php echo esc_html( $item['status'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $item['imageUrl'] ) ) : ?>
							<img src="<?php echo esc_url( $item['imageUrl'] ); ?>" alt="<?php echo esc_attr( $item['imageAlt'] ); ?>">
						<?php else : ?>
							<div class="mwm-cursos__media-placeholder"><?php esc_html_e( 'Sin imagen', 'mwm-blocks' ); ?></div>
						<?php endif; ?>
						<?php if ( $item['showPlay'] ) : ?>
							<div class="mwm-cursos__play" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="currentColor">
									<path d="M8 5v14l11-7z" />
								</svg>
							</div>
						<?php endif; ?>
					</div>
					<div class="mwm-cursos__body">
						<?php if ( ! empty( $item['tag'] ) ) : ?>
							<span class="mwm-cursos__tag"><?php echo esc_html( $item['tag'] ); ?></span>
						<?php endif; ?>
						<h3 class="mwm-cursos__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<?php if ( ! empty( $item['description'] ) ) : ?>
							<p class="mwm-cursos__desc"><?php echo esc_html( $item['description'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $item['chips'] ) ) : ?>
							<div class="mwm-cursos__meta">
								<?php foreach ( $item['chips'] as $chip ) : ?>
									<span class="mwm-cursos__chip"><?php echo esc_html( $chip ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $item['ctaLabel'] ) ) : ?>
							<div class="mwm-cursos__cta">
								<a href="<?php echo esc_url( $item['ctaUrl'] ); ?>" class="<?php echo esc_attr( $button_class ); ?>">
									<?php echo esc_html( $item['ctaLabel'] ); ?>
									<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
										<path d="M3 8h10m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php if ( ! empty( $footer_label ) ) : ?>
			<div class="mwm-cursos__footer-cta">
				<a href="<?php echo esc_url( $footer_url ); ?>" class="mwm-btn mwm-btn--sky mwm-btn--md">
					<?php echo esc_html( $footer_label ); ?>
					<svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3 8h10m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
