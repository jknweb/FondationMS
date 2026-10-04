<?php
/**
 * Modèle de secours : liste des articles (page des articles, et tout cas non couvert).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part(
	'template-parts/page-hero',
	null,
	array(
		'title' => is_home() && get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Actualités', 'fondation-ms' ),
	)
);
?>
<section class="section section--tint">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="<?php echo is_post_type_archive( 'ms_projet' ) || is_tax( 'ms_projet_cat' ) ? 'projects' : 'news-grid news-grid--light'; ?>">
				<?php
				$fms_index = 0;
				while ( have_posts() ) {
					the_post();
					if ( 'ms_projet' === get_post_type() ) {
						get_template_part( 'template-parts/content/card', 'projet' );
					} else {
						get_template_part( 'template-parts/content/card', 'post', array( 'index' => $fms_index++ ) );
					}
				}
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'prev_text' => __( 'Précédent', 'fondation-ms' ),
					'next_text' => __( 'Suivant', 'fondation-ms' ),
				)
			);
			?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content/none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
