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
}
add_action( 'customize_register', 'mwm_playsalud_customize_register' );
