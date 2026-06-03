<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mwm_about_default_title_lines' ) ) {
	function mwm_about_default_title_lines() {
		return array(
			array(
				'text'      => 'Un equipo serio,',
				'className' => 't-thin',
			),
			array(
				'text'      => 'con un',
				'className' => 't-bold',
			),
		);
	}
}

if ( ! function_exists( 'mwm_about_normalize_title_lines' ) ) {
	function mwm_about_normalize_title_lines( $title_lines, $legacy_title ) {
		$defaults = mwm_about_default_title_lines();
		$lines    = is_array( $title_lines ) ? $title_lines : array();

		if ( empty( $lines ) && ! empty( $legacy_title ) ) {
			$legacy_title = trim( wp_strip_all_tags( (string) $legacy_title ) );
			if ( '' !== $legacy_title ) {
				$lines = array(
					array(
						'text'      => $legacy_title,
						'className' => 't-thin',
					),
				);
			}
		}

		if ( empty( $lines ) ) {
			$lines = $defaults;
		}

		$normalized = array();
		foreach ( array_slice( $lines, 0, 5 ) as $index => $line ) {
			$fallback = isset( $defaults[ $index ] ) ? $defaults[ $index ] : $defaults[ count( $defaults ) - 1 ];
			$text     = isset( $line['text'] ) ? trim( wp_strip_all_tags( (string) $line['text'] ) ) : $fallback['text'];
			$class    = isset( $line['className'] ) ? (string) $line['className'] : $fallback['className'];
			$classes  = preg_split( '/\s+/', $class );

			$sanitized_classes = array();
			if ( is_array( $classes ) ) {
				foreach ( $classes as $single_class ) {
					$single_class = sanitize_html_class( $single_class );
					if ( '' !== $single_class ) {
						$sanitized_classes[] = $single_class;
					}
				}
			}

			if ( '' === $text ) {
				$text = $fallback['text'];
			}

			$normalized[] = array(
				'text'      => $text,
				'className' => implode( ' ', $sanitized_classes ),
			);
		}

		return $normalized;
	}
}

if ( ! function_exists( 'mwm_about_default_pillars' ) ) {
	function mwm_about_default_pillars() {
		return array(
			array(
				'label' => 'RIGOR',
				'claim' => 'Asesor clinico por video.',
			),
			array(
				'label' => 'TONO',
				'claim' => 'Ni frivolo, ni solemne.',
			),
			array(
				'label' => 'ALCANCE',
				'claim' => 'B2B institucional.',
			),
			array(
				'label' => 'MODELO',
				'claim' => 'Produccion con redaccion propia.',
			),
		);
	}
}

if ( ! function_exists( 'mwm_about_normalize_pillars' ) ) {
	function mwm_about_normalize_pillars( $pillars ) {
		$defaults = mwm_about_default_pillars();
		$list     = is_array( $pillars ) ? $pillars : array();

		if ( empty( $list ) ) {
			$list = $defaults;
		}

		$normalized = array();
		foreach ( array_slice( $list, 0, 8 ) as $index => $pillar ) {
			$fallback = isset( $defaults[ $index ] ) ? $defaults[ $index ] : array( 'label' => '', 'claim' => '' );
			$label    = isset( $pillar['label'] ) ? trim( wp_strip_all_tags( (string) $pillar['label'] ) ) : $fallback['label'];
			$claim    = isset( $pillar['claim'] ) ? trim( wp_strip_all_tags( (string) $pillar['claim'] ) ) : $fallback['claim'];

			$normalized[] = array(
				'label' => '' !== $label ? $label : $fallback['label'],
				'claim' => '' !== $claim ? $claim : $fallback['claim'],
			);
		}

		return $normalized;
	}
}

$raw_attributes = is_array( $attributes ) ? $attributes : array();

$defaults = array(
	'eyebrow'       => 'Quienes somos',
	'title'         => 'Un equipo serio con un lenguaje propio.',
	'titleLines'    => mwm_about_default_title_lines(),
	'titleLineCount'=> 2,
	'titleAccent'   => 'lenguaje propio.',
	'lead'          => 'PlaySalud nace de la conviccion de que la salud merece una voz audiovisual propia: rigurosa, humana y util.',
	'leadSecondary' => 'No producimos contenido viral. No hacemos pildoras motivacionales. Hacemos videos que un profesional firmaria sin incomodidad y que un paciente entiende sin sentirse infantilizado.',
	'visualTag'     => 'Manifiesto',
	'quote'         => 'Hacemos videos que un profesional',
	'quoteEmphasis' => 'firmaria sin incomodidad',
	'quoteSuffix'   => ', y que un paciente entiende sin sentirse infantilizado.',
	'signatureName' => 'Equipo editorial PlaySalud',
	'signatureRole' => 'Asesoria clinica - Produccion audiovisual',
	'stamp'         => 'Edicion 2026',
	'pillars'       => mwm_about_default_pillars(),
);

$attributes = wp_parse_args( $raw_attributes, $defaults );

$incoming_title_lines = isset( $raw_attributes['titleLines'] ) ? $raw_attributes['titleLines'] : array();
$title_lines          = mwm_about_normalize_title_lines( $incoming_title_lines, $attributes['title'] );
$requested_line_count = (int) $attributes['titleLineCount'];
$line_count          = max( 1, min( 5, $requested_line_count > 0 ? $requested_line_count : count( $title_lines ) ) );
$title_lines         = array_slice( $title_lines, 0, $line_count );
$title_accent        = trim( wp_strip_all_tags( (string) $attributes['titleAccent'] ) );
$pillars             = mwm_about_normalize_pillars( $attributes['pillars'] );

$quote_before   = trim( wp_strip_all_tags( (string) $attributes['quote'] ) );
$quote_emphasis = trim( wp_strip_all_tags( (string) $attributes['quoteEmphasis'] ) );
$quote_suffix   = trim( wp_strip_all_tags( (string) $attributes['quoteSuffix'] ) );

if (
	isset( $raw_attributes['quote'] ) &&
	! isset( $raw_attributes['quoteEmphasis'] ) &&
	! isset( $raw_attributes['quoteSuffix'] )
) {
	$quote_before   = trim( wp_strip_all_tags( (string) $raw_attributes['quote'] ) );
	$quote_emphasis = '';
	$quote_suffix   = '';
}

$section_id = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'about';
$wrapper    = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-about',
	)
);
?>
<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<div class="about-grid">
			<div class="reveal">
				<p class="mwm-eyebrow mwm-eyebrow-about"><?php echo esc_html( $attributes['eyebrow'] ); ?></p>
				<h2 class="about-title">
					<?php foreach ( $title_lines as $index => $line ) : ?>
						<span class="<?php echo esc_attr( $line['className'] ); ?>"><?php echo esc_html( $line['text'] ); ?></span>
						<?php if ( $index === count( $title_lines ) - 1 && '' !== $title_accent ) : ?>
							<span class="accent"><?php echo esc_html( ' ' . $title_accent ); ?></span>
						<?php endif; ?>
						<?php if ( $index < count( $title_lines ) - 1 ) : ?>
							<br>
						<?php endif; ?>
					<?php endforeach; ?>
				</h2>
				<p class="about-p"><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
				<p class="about-p"><?php echo wp_kses_post( $attributes['leadSecondary'] ); ?></p>
			</div>
			<div class="about-visual reveal">
				<span class="about-visual-tag"><?php echo esc_html( $attributes['visualTag'] ); ?></span>
				<svg class="about-quote-mark" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
					<path d="M9 7H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2H4v2h1a4 4 0 0 0 4-4V7zm12 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2h-1v2h1a4 4 0 0 0 4-4V7z"/>
				</svg>
				<p class="about-quote">
					<?php echo esc_html( $quote_before ); ?>
					<?php if ( '' !== $quote_emphasis ) : ?>
						<em><?php echo esc_html( $quote_emphasis ); ?></em>
					<?php endif; ?>
					<?php echo esc_html( $quote_suffix ); ?>
				</p>
				<div class="about-signature">
					<div class="about-signature-mark" aria-hidden="true">
						<svg width="18" height="18" viewBox="0 0 20 20" fill="none">
							<path d="M7 5.5l8 4.5-8 4.5V5.5z" fill="#ffffff"/>
						</svg>
					</div>
					<div>
						<div class="about-signature-name"><?php echo esc_html( $attributes['signatureName'] ); ?></div>
						<div class="about-signature-role"><?php echo esc_html( $attributes['signatureRole'] ); ?></div>
					</div>
				</div>
				<span class="about-stamp"><?php echo esc_html( $attributes['stamp'] ); ?></span>
			</div>
		</div>
		<div class="pillars reveal">
			<?php foreach ( $pillars as $pillar ) : ?>
				<div class="pillar">
					<div class="pillar-label"><?php echo esc_html( $pillar['label'] ); ?></div>
					<div class="pillar-claim"><?php echo esc_html( $pillar['claim'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
