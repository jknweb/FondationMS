<?php
/**
 * Section : projets phares (type de contenu « ms_projet »).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_projets = new WP_Query(
	array(
		'post_type'      => 'ms_projet',
		'posts_per_page' => max( 1, absint( fms_mod( 'projets_count' ) ) ),
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'no_found_rows'  => true,
	)
);
if ( ! $fms_projets->have_posts() ) {
	return;
}
?>
<section class="section" id="projets">
	<div class="container">
		<?php fms_section_head( fms_mod( 'projets_eyebrow' ), fms_mod( 'projets_title' ), fms_mod( 'projets_intro' ) ); ?>
		<div class="projects">
			<?php
			while ( $fms_projets->have_posts() ) {
				$fms_projets->the_post();
				get_template_part( 'template-parts/content/card', 'projet' );
			}
			wp_reset_postdata();
			?>
		</div>
		<?php if ( get_post_type_archive_link( 'ms_projet' ) ) : ?>
			<p class="section__more"><a class="link-arrow" href="<?php echo esc_url( get_post_type_archive_link( 'ms_projet' ) ); ?>"><?php esc_html_e( 'Tous les projets', 'fondation-ms' ); ?></a></p>
		<?php endif; ?>
	</div>
	<?php fms_edit_link( 'projets' ); ?>
</section>
