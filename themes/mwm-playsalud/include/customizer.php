<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
}
add_action( 'customize_register', 'mwm_playsalud_customize_register' );
