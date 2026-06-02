<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'PlayAcademy',
	'title'   => 'Itinerario formativo en IA aplicada a la salud.',
	'items'   => array(
		array(
			'title'    => 'Curso I: Entender la IA en salud desde cero.',
			'meta'     => '56 horas lectivas, 10 modulos, 3 meses.',
			'featured' => true,
		),
		array(
			'title'    => 'Curso II: IA generativa aplicada.',
			'meta'     => 'Proximamente.',
			'featured' => false,
		),
		array(
			'title'    => 'Curso III: IA por perfiles y procesos.',
			'meta'     => 'Proximamente.',
			'featured' => false,
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
?>
<section id="curso" class="mwm-home-section mwm-cursos">
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<div class="mwm-cursos__grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="mwm-cursos__card<?php echo ! empty( $item['featured'] ) ? ' mwm-cursos__card--featured' : ''; ?>">
					<h3><?php echo esc_html( isset( $item['title'] ) ? (string) $item['title'] : '' ); ?></h3>
					<p><?php echo esc_html( isset( $item['meta'] ) ? (string) $item['meta'] : '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
