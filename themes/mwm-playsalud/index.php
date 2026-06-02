<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="mwm-main">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			if ( is_front_page() && '' === trim( get_the_content() ) ) {
				echo do_blocks(
					'<!-- wp:mwm/hero /-->' .
					'<!-- wp:mwm/stats /-->' .
					'<!-- wp:mwm/banner /-->' .
					'<!-- wp:mwm/verticales /-->' .
					'<!-- wp:mwm/modulos /-->' .
					'<!-- wp:mwm/muestra /-->' .
					'<!-- wp:mwm/cursos /-->' .
					'<!-- wp:mwm/about /-->' .
					'<!-- wp:mwm/instituciones /-->' .
					'<!-- wp:mwm/contacto /-->'
				);
			} else {
				the_content();
			}
		endwhile;
		?>
	<?php else : ?>
		<?php esc_html_e( 'No hay contenido disponible.', 'mwm-playsalud' ); ?>
	<?php endif; ?>
</main>
<?php
get_footer();
