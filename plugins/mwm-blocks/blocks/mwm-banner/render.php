<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'Plataforma sanitaria audiovisual',
	'titleLines' => array(
		array(
			'text'      => 'Educacion sanitaria',
			'className' => 't-thin',
		),
		array(
			'text'      => 'basada en evidencia,',
			'className' => 't-bold accent',
		),
		array(
			'text'      => 'para pacientes, profesionales e instituciones.',
			'className' => 't-thin',
		),
	),
	'titleLineCount' => 3,
	'title'   => 'Educacion sanitaria basada en evidencia, para pacientes, profesionales e instituciones.',
	'lead'    => 'Videos breves, claros y revisados por profesionales. Listos para integrarse en hospitales, aseguradoras, universidades, sociedades cientificas y asociaciones de pacientes.',
	'imageUrl' => '',
	'imageAlt' => 'PlaySalud',
	'caption'  => '',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );

$block_anchor     = mwm_blocks_get_block_anchor( $block );
$attribute_anchor = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : '';
$section_id       = '' !== $block_anchor ? $block_anchor : ( '' !== $attribute_anchor ? $attribute_anchor : 'banner' );

$image_url = ! empty( $attributes['imageUrl'] )
	? $attributes['imageUrl']
	: get_stylesheet_directory_uri() . '/assets/images/banner-real.jpg';

$default_title_lines = $defaults['titleLines'];
$title_lines         = array();

if ( ! empty( $attributes['titleLines'] ) && is_array( $attributes['titleLines'] ) ) {
	$title_lines = $attributes['titleLines'];
} elseif ( ! empty( $attributes['title'] ) && is_string( $attributes['title'] ) ) {
	$legacy_lines = preg_split( '/(?<=[\.\!\?])\s+/', trim( preg_replace( '/\s+/', ' ', $attributes['title'] ) ) );
	if ( is_array( $legacy_lines ) ) {
		foreach ( $legacy_lines as $index => $line_text ) {
			if ( $index > 4 ) {
				break;
			}
			$fallback_class = isset( $default_title_lines[ $index ]['className'] ) ? $default_title_lines[ $index ]['className'] : 't-thin';
			$title_lines[]  = array(
				'text'      => $line_text,
				'className' => $fallback_class,
			);
		}
	}
}

if ( empty( $title_lines ) ) {
	$title_lines = $default_title_lines;
}

$line_count = isset( $attributes['titleLineCount'] ) ? (int) $attributes['titleLineCount'] : 0;
if ( $line_count < 1 ) {
	$line_count = count( $title_lines );
}
$line_count  = min( 5, max( 1, $line_count ) );
$title_lines = array_slice( $title_lines, 0, $line_count );

$normalized_lines = array();
foreach ( $title_lines as $index => $line ) {
	$default_line = isset( $default_title_lines[ $index ] ) ? $default_title_lines[ $index ] : end( $default_title_lines );
	$line_text    = isset( $line['text'] ) ? trim( (string) $line['text'] ) : $default_line['text'];
	$line_class   = isset( $line['className'] ) ? (string) $line['className'] : $default_line['className'];

	if ( '' === $line_text ) {
		continue;
	}

	$class_tokens = preg_split( '/\s+/', trim( $line_class ) );
	$class_tokens = is_array( $class_tokens ) ? $class_tokens : array();
	$class_tokens = array_filter(
		array_map(
			static function ( $token ) {
				$sanitized = sanitize_html_class( (string) $token );
				return '' !== $sanitized ? $sanitized : null;
			},
			$class_tokens
		)
	);

	$normalized_lines[] = array(
		'text'      => $line_text,
		'className' => ! empty( $class_tokens ) ? implode( ' ', $class_tokens ) : 't-thin',
	);
}

if ( empty( $normalized_lines ) ) {
	$normalized_lines[] = array(
		'text'      => $default_title_lines[0]['text'],
		'className' => $default_title_lines[0]['className'],
	);
}

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
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
			<h2 class="mwm-banner__title">
				<?php foreach ( $normalized_lines as $index => $line ) : ?>
					<span class="<?php echo esc_attr( $line['className'] ); ?>"><?php echo esc_html( $line['text'] ); ?></span>
					<?php if ( $index < count( $normalized_lines ) - 1 ) : ?>
						<br>
					<?php endif; ?>
				<?php endforeach; ?>
			</h2>
			<p class="mwm-banner__lead"><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
		</div>
	</div>
</section>
