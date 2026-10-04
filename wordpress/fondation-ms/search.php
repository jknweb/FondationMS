<?php
/**
 * Résultats de recherche.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		/* translators: %s: termes recherchés. */
		'title' => sprintf( __( 'Recherche : %s', 'fondation-ms' ), get_search_query() ),
	)
);
?>
<section class="section section--tint">
	<div class="container">
		<div class="search-again"><?php get_search_form(); ?></div>
		<?php if ( have_posts() ) : ?>
			<div class="news-grid news-grid--light">
				<?php
				$fms_index = 0;
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content/card', 'ms_projet' === get_post_type() ? 'projet' : 'post', array( 'index' => $fms_index++ ) );
				}
				?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
