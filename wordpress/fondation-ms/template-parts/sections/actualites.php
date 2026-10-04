<?php
/**
 * Section : fil d'actualité (articles de la catégorie « Actualités »).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_news = new WP_Query(
	array(
		'post_type'           => 'post',
		'category_name'       => 'actualites',
		'posts_per_page'      => max( 1, absint( fms_mod( 'news_count' ) ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
if ( ! $fms_news->have_posts() ) {
	return;
}
$fms_cat = get_category_by_slug( 'actualites' );
?>
<section class="impact" id="actualites">
	<div class="container">
		<?php fms_section_head( fms_mod( 'news_eyebrow' ), fms_mod( 'news_title' ), '', true ); ?>
		<div class="news-grid">
			<?php
			$fms_index = 0;
			while ( $fms_news->have_posts() ) {
				$fms_news->the_post();
				get_template_part( 'template-parts/content/card', 'post', array( 'index' => $fms_index++ ) );
			}
			wp_reset_postdata();
			?>
		</div>
		<?php if ( $fms_cat && fms_mod( 'news_all_label' ) ) : ?>
			<p class="section__more section__more--light"><a class="btn btn--ghost" href="<?php echo esc_url( get_category_link( $fms_cat ) ); ?>"><?php echo esc_html( fms_mod( 'news_all_label' ) ); ?></a></p>
		<?php endif; ?>
	</div>
	<?php fms_edit_link( 'actualites' ); ?>
</section>
