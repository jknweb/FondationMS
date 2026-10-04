<?php
/**
 * Section : diaporama photo en ordre aléatoire (photos de la « Galerie photo »).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_slides = get_posts(
	array(
		'post_type'      => 'ms_photo',
		'posts_per_page' => 30,
		'orderby'        => 'rand',
		'meta_key'       => '_thumbnail_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'no_found_rows'  => true,
	)
);
if ( ! $fms_slides ) {
	return;
}
?>
<section class="section section--dark" id="galerie">
	<div class="container">
		<?php fms_section_head( fms_mod( 'slideshow_eyebrow' ), fms_mod( 'slideshow_title' ), '', true ); ?>
		<div class="slideshow reveal" data-slideshow aria-roledescription="carrousel" aria-label="<?php esc_attr_e( 'Photos de la fondation', 'fondation-ms' ); ?>">
			<div class="slideshow__stage">
				<?php foreach ( $fms_slides as $fms_i => $fms_slide ) : ?>
					<figure class="slide<?php echo 0 === $fms_i ? ' is-active' : ''; ?>" aria-hidden="<?php echo 0 === $fms_i ? 'false' : 'true'; ?>" data-caption="<?php echo esc_attr( get_the_title( $fms_slide ) ); ?>">
						<?php echo get_the_post_thumbnail( $fms_slide, 'fms-wide', array( 'alt' => esc_attr( get_the_title( $fms_slide ) ), 'loading' => $fms_i > 1 ? 'lazy' : 'eager' ) ); ?>
					</figure>
				<?php endforeach; ?>
			</div>
			<p class="slideshow__caption" aria-live="polite"><?php echo esc_html( get_the_title( $fms_slides[0] ) ); ?></p>
			<?php if ( count( $fms_slides ) > 1 ) : ?>
				<button type="button" class="slideshow__btn slideshow__btn--prev" data-prev aria-label="<?php esc_attr_e( 'Photo précédente', 'fondation-ms' ); ?>"><?php fms_the_icon( 'up' ); ?></button>
				<button type="button" class="slideshow__btn slideshow__btn--next" data-next aria-label="<?php esc_attr_e( 'Photo suivante', 'fondation-ms' ); ?>"><?php fms_the_icon( 'up' ); ?></button>
				<div class="slideshow__dots">
					<?php foreach ( $fms_slides as $fms_i => $fms_slide ) : ?>
						<button type="button" class="dot<?php echo 0 === $fms_i ? ' is-active' : ''; ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: numéro. */ __( 'Photo %d', 'fondation-ms' ), $fms_i + 1 ) ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php fms_edit_link( 'diaporama' ); ?>
</section>
