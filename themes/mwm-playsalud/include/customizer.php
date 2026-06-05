<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mwm_playsalud_sanitize_social_links( $input ) {
	$decoded_input = array();

	if ( is_array( $input ) ) {
		$decoded_input = $input;
	} elseif ( is_string( $input ) && '' !== $input ) {
		$decoded_input = json_decode( wp_unslash( $input ), true );
	}

	if ( ! is_array( $decoded_input ) ) {
		return wp_json_encode( array() );
	}

	$sanitized_items = array();

	foreach ( $decoded_input as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$image_id = isset( $item['image_id'] ) ? absint( $item['image_id'] ) : 0;
		$text     = isset( $item['text'] ) ? sanitize_text_field( $item['text'] ) : '';
		$text     = trim( $text );

		if ( 0 === $image_id && '' === $text ) {
			continue;
		}

		$sanitized_items[] = array(
			'image_id' => $image_id,
			'text'     => $text,
		);
	}

	return wp_json_encode( $sanitized_items );
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'MWM_Playsalud_Social_Repeater_Control' ) ) {
	class MWM_Playsalud_Social_Repeater_Control extends WP_Customize_Control {
		public $type = 'mwm_social_repeater';

		public function render_content() {
			$raw_items = json_decode( (string) $this->value(), true );
			$items     = is_array( $raw_items ) ? $raw_items : array();
			?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<input type="hidden" class="mwm-social-repeater__value" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />

			<div class="mwm-social-repeater__items">
				<?php foreach ( $items as $item ) : ?>
					<?php
					$image_id      = isset( $item['image_id'] ) ? absint( $item['image_id'] ) : 0;
					$text          = isset( $item['text'] ) ? sanitize_text_field( $item['text'] ) : '';
					$has_image     = $image_id > 0;
					$preview_image = $has_image ? wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'class' => 'mwm-social-repeater__preview-image' ) ) : '';
					?>
					<div class="mwm-social-repeater__item">
						<div class="mwm-social-repeater__preview <?php echo $has_image ? 'has-image' : ''; ?>">
							<?php echo $preview_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

						<input type="hidden" class="mwm-social-repeater__image-id" value="<?php echo esc_attr( $image_id ); ?>" />

						<div class="mwm-social-repeater__buttons">
							<button type="button" class="button mwm-social-repeater__select-image"><?php esc_html_e( 'Seleccionar imagen', 'mwm-playsalud' ); ?></button>
							<button type="button" class="button-link mwm-social-repeater__remove-image"><?php esc_html_e( 'Quitar imagen', 'mwm-playsalud' ); ?></button>
						</div>

						<input type="text" class="widefat mwm-social-repeater__text" value="<?php echo esc_attr( $text ); ?>" placeholder="<?php esc_attr_e( 'Texto de la red social', 'mwm-playsalud' ); ?>" />

						<button type="button" class="button-link-delete mwm-social-repeater__remove-row"><?php esc_html_e( 'Eliminar', 'mwm-playsalud' ); ?></button>
					</div>
				<?php endforeach; ?>
			</div>

			<button type="button" class="button button-secondary mwm-social-repeater__add-row"><?php esc_html_e( 'Agregar red social', 'mwm-playsalud' ); ?></button>
			<?php
		}
	}
}

function mwm_playsalud_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'mwm_playsalud_header_section',
		array(
			'title'    => __( 'Header', 'mwm-playsalud' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_header_button_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_header_button_title',
		array(
			'label'   => __( 'Titulo del boton', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_header_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_header_button_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_header_button_link',
		array(
			'label'   => __( 'Enlace del boton', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_header_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_section(
		'mwm_playsalud_footer_section',
		array(
			'title'    => __( 'Footer', 'mwm-playsalud' ),
			'priority' => 31,
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_footer_description',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_footer_description',
		array(
			'label'   => __( 'Descripcion', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_footer_section',
			'type'    => 'textarea',
		)
	);

	$text_fields = array(
		'mwm_playsalud_footer_menu_title_1' => __( 'Primer titulo del menu', 'mwm-playsalud' ),
		'mwm_playsalud_footer_menu_title_2' => __( 'Segundo titulo del menu', 'mwm-playsalud' ),
		'mwm_playsalud_footer_email'        => __( 'Correo electronico', 'mwm-playsalud' ),
		'mwm_playsalud_footer_link'         => __( 'Enlace', 'mwm-playsalud' ),
		'mwm_playsalud_footer_link_title'   => __( 'Titulo del enlace', 'mwm-playsalud' ),
		'mwm_playsalud_footer_menu_title_3' => __( 'Tercer titulo del menu', 'mwm-playsalud' ),
		'mwm_playsalud_footer_copyright'    => __( 'Copyright', 'mwm-playsalud' ),
	);

	foreach ( $text_fields as $setting_id => $label ) {
		$wp_customize->add_setting(
			$setting_id,
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			$setting_id,
			array(
				'label'   => $label,
				'section' => 'mwm_playsalud_footer_section',
				'type'    => 'text',
			)
		);
	}

	$wp_customize->add_section(
		'mwm_playsalud_404_section',
		array(
			'title'    => __( '404', 'mwm-playsalud' ),
			'priority' => 32,
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_code',
		array(
			'default'           => '404',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_code',
		array(
			'label'   => __( 'Texto H1 (numero de error)', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_title',
		array(
			'default'           => __( 'Ups, pagina no encontrada', 'mwm-playsalud' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_title',
		array(
			'label'   => __( 'Titulo H2', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_description',
		array(
			'default'           => __( 'Lo sentimos, la direccion que buscas no existe o ha sido movida. Verifica la URL o regresa al inicio para seguir comprando.', 'mwm-playsalud' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_description',
		array(
			'label'   => __( 'Parrafo', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_home_button_text',
		array(
			'default'           => __( 'Ir al inicio', 'mwm-playsalud' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_home_button_text',
		array(
			'label'   => __( 'Texto boton Ir al inicio', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_home_button_url',
		array(
			'default'           => home_url( '/' ),
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_home_button_url',
		array(
			'label'   => __( 'Enlace boton Ir al inicio', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_support_button_text',
		array(
			'default'           => __( 'Contactar Soporte', 'mwm-playsalud' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_support_button_text',
		array(
			'label'   => __( 'Texto boton Contactar soporte', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_404_support_button_url',
		array(
			'default'           => '#contacto',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'mwm_playsalud_404_support_button_url',
		array(
			'label'   => __( 'Enlace boton Contactar soporte', 'mwm-playsalud' ),
			'section' => 'mwm_playsalud_404_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_section(
		'mwm_playsalud_social_section',
		array(
			'title'    => __( 'Redes Sociales', 'mwm-playsalud' ),
			'priority' => 33,
		)
	);

	$wp_customize->add_setting(
		'mwm_playsalud_social_links',
		array(
			'default'           => '[]',
			'sanitize_callback' => 'mwm_playsalud_sanitize_social_links',
		)
	);

	$wp_customize->add_control(
		new MWM_Playsalud_Social_Repeater_Control(
			$wp_customize,
			'mwm_playsalud_social_links',
			array(
				'label'       => __( 'Listado de redes sociales', 'mwm-playsalud' ),
				'description' => __( 'Agrega elementos con imagen y texto. Puedes crear tantos como necesites.', 'mwm-playsalud' ),
				'section'     => 'mwm_playsalud_social_section',
			)
		)
	);
}
add_action( 'customize_register', 'mwm_playsalud_customize_register' );
