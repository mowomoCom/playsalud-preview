<?php
/**
 * Template Name: Página de textos
 *
 * @package mwm-playsalud
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php get_header(); ?>

<main id="main" class="site-main mwm-legal-page">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<header class="mwm-legal-page__header">
				<div class="mwm-container mwm-legal-page__header-inner">
					<h1 class="mwm-legal-page__title"><?php the_title(); ?></h1>
				</div>
			</header>

			<section class="mwm-legal-page__content">
				<div class="mwm-container mwm-legal-page__content-inner">
					<?php the_content(); ?>
				</div>
			</section>
		<?php endwhile; ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
