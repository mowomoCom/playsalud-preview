<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'           => 'Plataforma de salud audiovisual',
	'titleLines'        => array(
		array(
			'text'      => 'Salud en video.',
			'className' => 't-bold',
		),
		array(
			'text'      => 'Seria cuando toca,',
			'className' => 't-thin accent',
		),
		array(
			'text'      => 'cercana cuando ayuda.',
			'className' => 't-thin',
		),
	),
	'titleLineCount'    => 3,
	'title'             => 'Salud en video. Seria cuando toca, cercana cuando ayuda.',
	'lead'              => 'PlaySalud transforma informacion clinica compleja en videos breves, claros y basados en evidencia para pacientes y profesionales.',
	'primaryText'       => 'Solicitar demo institucional',
	'primaryUrl'        => '#contacto',
	'secondaryText'     => 'Explorar PlayCare y PlayAcademy',
	'secondaryUrl'      => '#verticales',
	'trustText'         => 'Hospitales, sociedades cientificas y universidades nos avalan',
	'trustDotsCount'    => 3,
	'showTrust'         => true,
	'showFloatCare'     => true,
	'floatCareLabel'    => 'PlayCare',
	'floatCareValue'    => '300+',
	'floatCareSub'      => 'videos clinicos',
	'showFloatAcademy'  => true,
	'floatAcademyLabel' => 'PlayAcademy',
	'floatAcademyValue' => 'Curso I',
	'floatAcademySub'   => 'IA en Salud - 8 sem.',
	'showPhotoMeta'     => true,
	'photoMetaTag'      => 'Cirugia',
	'photoMetaTime'     => '3:42',
	'imageUrl'          => '',
	'imageAlt'          => 'PlaySalud',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );

$image_url = ! empty( $attributes['imageUrl'] )
	? $attributes['imageUrl']
	: get_stylesheet_directory_uri() . '/assets/images/hero-real.jpg';

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

$line_count = (int) $attributes['titleLineCount'];
if ( $line_count < 1 ) {
	$line_count = count( $title_lines );
}
$line_count  = min( 5, max( 1, $line_count ) );
$title_lines = array_slice( $title_lines, 0, $line_count );

$normalized_lines = array();
foreach ( $title_lines as $index => $line ) {
	$default_line = isset( $default_title_lines[ $index ] ) ? $default_title_lines[ $index ] : end( $default_title_lines );
	$line_text    = isset( $line['text'] ) ? (string) $line['text'] : $default_line['text'];
	$line_class   = isset( $line['className'] ) ? (string) $line['className'] : $default_line['className'];
	$normalized_lines[] = array(
		'text'      => $line_text,
		'className' => $line_class,
	);
}

$trust_dots_count = isset( $attributes['trustDotsCount'] ) ? (int) $attributes['trustDotsCount'] : 3;
$trust_dots_count = min( 5, max( 1, $trust_dots_count ) );

$show_trust         = ! isset( $attributes['showTrust'] ) ? true : (bool) $attributes['showTrust'];
$show_float_care    = ! isset( $attributes['showFloatCare'] ) ? true : (bool) $attributes['showFloatCare'];
$show_float_academy = ! isset( $attributes['showFloatAcademy'] ) ? true : (bool) $attributes['showFloatAcademy'];
$show_photo_meta    = ! isset( $attributes['showPhotoMeta'] ) ? true : (bool) $attributes['showPhotoMeta'];

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'mwm-home-section mwm-hero',
	)
);
?>
<section <?php echo $wrapper_attributes; ?>>
	<div class="mwm-container mwm-hero__inner">
		<div class="mwm-hero__content">
			<div class="mwm-hero__badge">
				<span class="mwm-hero__badge-dot"></span>
				<?php echo wp_kses_post( $attributes['eyebrow'] ); ?>
			</div>
			<h1 class="mwm-hero__title">
				<?php foreach ( $normalized_lines as $index => $line ) : ?>
					<span class="<?php echo esc_attr( $line['className'] ); ?>"><?php echo esc_html( $line['text'] ); ?></span>
					<?php if ( $index < count( $normalized_lines ) - 1 ) : ?>
						<br>
					<?php endif; ?>
				<?php endforeach; ?>
			</h1>
			<p class="mwm-hero__lead"><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
			<div class="mwm-hero__actions">
				<a href="<?php echo esc_url( $attributes['primaryUrl'] ); ?>" class="mwm-btn mwm-btn--primary mwm-btn--lg">
					<?php echo esc_html( $attributes['primaryText'] ); ?>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3 8h10m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</a>
				<a href="<?php echo esc_url( $attributes['secondaryUrl'] ); ?>" class="mwm-btn mwm-btn--ghost mwm-btn--lg"><?php echo esc_html( $attributes['secondaryText'] ); ?></a>
			</div>
			<?php if ( $show_trust ) : ?>
				<div class="mwm-hero__trust">
					<div class="mwm-hero__trust-dots" aria-hidden="true">
						<?php for ( $dot = 0; $dot < $trust_dots_count; $dot++ ) : ?>
							<span class="mwm-hero__trust-dot"></span>
						<?php endfor; ?>
					</div>
					<span><?php echo esc_html( $attributes['trustText'] ); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<div class="mwm-hero__visual">
			<?php if ( $show_float_care ) : ?>
				<div class="mwm-hero__float-card mwm-hero__float-care">
					<div class="mwm-hero__float-label"><?php echo esc_html( $attributes['floatCareLabel'] ); ?></div>
					<div class="mwm-hero__float-value"><?php echo esc_html( $attributes['floatCareValue'] ); ?></div>
					<div class="mwm-hero__float-sub"><?php echo esc_html( $attributes['floatCareSub'] ); ?></div>
				</div>
			<?php endif; ?>
			<div class="mwm-hero__photo-wrap">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $attributes['imageAlt'] ); ?>">
				<div class="mwm-hero__photo-overlay"></div>
				<?php if ( $show_photo_meta ) : ?>
					<div class="mwm-hero__photo-meta">
						<span class="mwm-hero__photo-meta-tag"><?php echo esc_html( $attributes['photoMetaTag'] ); ?></span>
						<span class="mwm-hero__photo-meta-dot"></span>
						<span class="mwm-hero__photo-meta-time"><?php echo esc_html( $attributes['photoMetaTime'] ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( $show_float_academy ) : ?>
				<div class="mwm-hero__float-card mwm-hero__float-academy">
					<div class="mwm-hero__float-label"><?php echo esc_html( $attributes['floatAcademyLabel'] ); ?></div>
					<div class="mwm-hero__float-value"><?php echo esc_html( $attributes['floatAcademyValue'] ); ?></div>
					<div class="mwm-hero__float-sub"><?php echo esc_html( $attributes['floatAcademySub'] ); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
