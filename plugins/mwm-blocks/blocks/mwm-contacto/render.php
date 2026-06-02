<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'      => 'Contacto',
	'title'        => 'Solicita informacion para tu institucion.',
	'lead'         => 'Cuentanos que necesitas y te contactamos en 1 o 2 dias laborables.',
	'nameLabel'    => 'Nombre',
	'emailLabel'   => 'Email',
	'messageLabel' => 'Mensaje',
	'submitText'   => 'Enviar consulta',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
?>
<section id="contacto" class="mwm-home-section mwm-contacto">
	<div class="mwm-container mwm-contacto__grid">
		<div>
			<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
			<p><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
		</div>
		<form class="mwm-form-card mwm-contacto__form" data-mwm-contact-form>
			<div class="mwm-form-row mwm-form-row--split">
				<label class="mwm-form-label"><?php echo esc_html( $attributes['nameLabel'] ); ?><input class="mwm-form-input" type="text" required></label>
				<label class="mwm-form-label"><?php echo esc_html( $attributes['emailLabel'] ); ?><input class="mwm-form-input" type="email" required></label>
			</div>
			<div class="mwm-form-row">
				<label class="mwm-form-label"><?php echo esc_html( $attributes['messageLabel'] ); ?><textarea class="mwm-form-textarea"></textarea></label>
			</div>
			<button class="mwm-btn mwm-btn--primary mwm-btn--md" type="submit"><?php echo esc_html( $attributes['submitText'] ); ?></button>
		</form>
	</div>
</section>
