<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="contacto" class="mwm-home-section mwm-contacto">
	<div class="mwm-container mwm-contacto__grid">
		<div>
			<p class="mwm-eyebrow">Contacto</p>
			<h2>Solicita informacion para tu institucion.</h2>
			<p>Cuentanos que necesitas y te contactamos en 1 o 2 dias laborables.</p>
		</div>
		<form class="mwm-form-card mwm-contacto__form" data-mwm-contact-form>
			<div class="mwm-form-row mwm-form-row--split">
				<label class="mwm-form-label">Nombre<input class="mwm-form-input" type="text" required></label>
				<label class="mwm-form-label">Email<input class="mwm-form-input" type="email" required></label>
			</div>
			<div class="mwm-form-row">
				<label class="mwm-form-label">Mensaje<textarea class="mwm-form-textarea"></textarea></label>
			</div>
			<button class="mwm-btn mwm-btn--primary mwm-btn--md" type="submit">Enviar consulta</button>
		</form>
	</div>
</section>
