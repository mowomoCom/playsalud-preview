<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'PlayCare',
	'title'   => 'Modulos de cuidados en formato video.',
	'titleLight' => 'Modulos de cuidados,',
	'titleBold' => 'en formato video.',
	'sectionDescription' => 'Catalogo modular y escalable, basado en evidencia y revisado por profesionales.',
	'buttonUrl' => '#contacto',
	'buttonLabel' => 'Ver todos los modulos',
	'items'   => array(
		array(
			'imageId'      => 0,
			'imageUrl'     => '',
			'caption'      => '35 videos',
			'title'        => 'Cancer colorrectal',
			'description'  => 'Diagnostico, tratamiento, cirugia, recuperacion y vida posterior al proceso oncologico.',
		),
		array(
			'imageId'      => 0,
			'imageUrl'     => '',
			'caption'      => '42 videos',
			'title'        => 'RICA Especialidades',
			'description'  => 'Recuperacion intensificada adaptada a distintas areas medico-quirurgicas.',
		),
		array(
			'imageId'      => 0,
			'imageUrl'     => '',
			'caption'      => '20 videos',
			'title'        => 'RICA General',
			'description'  => 'Recuperacion intensificada, preparacion del paciente y papel activo en el proceso quirurgico.',
		),
		array(
			'imageId'      => 0,
			'imageUrl'     => '',
			'caption'      => '20 videos',
			'title'        => 'Incontinencia fecal',
			'description'  => 'Comprension del problema, habitos, suelo pelvico y opciones terapeuticas.',
		),
		array(
			'imageId'      => 0,
			'imageUrl'     => '',
			'caption'      => '15 videos',
			'title'        => 'Que saber de la anestesia',
			'description'  => 'Consulta preanestesica, medicacion y anestesia en quirofano para pacientes quirurgicos.',
		),
		array(
			'imageId'      => 0,
			'imageUrl'     => '',
			'caption'      => '10 videos',
			'title'        => 'Ostomias',
			'description'  => 'Preparacion, autocuidados, adaptacion y signos de alarma para pacientes ostomizados.',
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
$title_light = isset( $attributes['titleLight'] ) ? trim( (string) $attributes['titleLight'] ) : '';
$title_bold = isset( $attributes['titleBold'] ) ? trim( (string) $attributes['titleBold'] ) : '';
$title_legacy = isset( $attributes['title'] ) ? trim( (string) $attributes['title'] ) : '';
$section_description = isset( $attributes['sectionDescription'] ) ? trim( (string) $attributes['sectionDescription'] ) : '';
$button_url = isset( $attributes['buttonUrl'] ) ? trim( (string) $attributes['buttonUrl'] ) : '';
$button_label = isset( $attributes['buttonLabel'] ) ? trim( (string) $attributes['buttonLabel'] ) : '';
$has_split_title = '' !== $title_light || '' !== $title_bold;
$block_anchor = mwm_blocks_get_block_anchor( $block );
$attribute_anchor = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : '';
$section_id = '' !== $block_anchor ? $block_anchor : ( '' !== $attribute_anchor ? $attribute_anchor : 'modulos' );
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-modulos',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<p class="mwm-eyebrow mwm-eyebrow-modulos reveal"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2 class="mwm-modulos__section-title reveal">
			<?php if ( $has_split_title ) : ?>
				<span class="mwm-modulos__title-light"><?php echo esc_html( $title_light ); ?></span>
				<?php if ( '' !== $title_bold ) : ?>
					<br>
					<span class="mwm-modulos__title-bold"><?php echo esc_html( $title_bold ); ?></span>
				<?php endif; ?>
			<?php else : ?>
				<span class="mwm-modulos__title-bold"><?php echo esc_html( $title_legacy ); ?></span>
			<?php endif; ?>
		</h2>
		<?php if ( '' !== $section_description ) : ?>
			<p class="mwm-modulos__section-desc reveal"><?php echo esc_html( $section_description ); ?></p>
		<?php endif; ?>
		<div class="mwm-modulos__grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$item_data    = is_array( $item ) ? $item : array();
				$title        = isset( $item_data['title'] ) ? (string) $item_data['title'] : '';
				$image_url    = isset( $item_data['imageUrl'] ) ? (string) $item_data['imageUrl'] : '';
				$description  = isset( $item_data['description'] ) ? (string) $item_data['description'] : '';
				$legacy_meta  = isset( $item_data['meta'] ) ? (string) $item_data['meta'] : '';
				$caption      = isset( $item_data['caption'] ) && '' !== trim( (string) $item_data['caption'] )
					? (string) $item_data['caption']
					: $legacy_meta;
				?>
				<article class="mwm-modulos__card reveal">
					<div class="mwm-modulos__photo">
						<?php if ( '' !== $caption ) : ?>
							<span class="mwm-modulos__badge"><?php echo esc_html( $caption ); ?></span>
						<?php endif; ?>
						<?php if ( '' !== $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
						<?php else : ?>
							<div class="mwm-modulos__photo-placeholder"></div>
						<?php endif; ?>
					</div>
					<div class="mwm-modulos__body">
						<h3 class="mwm-modulos__title"><?php echo esc_html( $title ); ?></h3>
						<p class="mwm-modulos__desc"><?php echo esc_html( $description ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php if ( '' !== $button_label ) : ?>
			<div class="mwm-modulos__cta-row">
				<a class="mwm-btn mwm-btn--sky mwm-btn--lg" href="<?php echo esc_url( '' !== $button_url ? $button_url : '#' ); ?>">
					<?php echo esc_html( $button_label ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
