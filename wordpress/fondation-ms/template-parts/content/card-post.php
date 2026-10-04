<?php
/**
 * Carte d'une actualité (fil d'actualité et archives).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_index  = isset( $args['index'] ) ? (int) $args['index'] : 0;
$fms_cats   = array_filter(
	get_the_category(),
	function ( $c ) {
		return ! in_array( $c->slug, array( 'actualites', 'la-une', 'uncategorized', 'non-classe' ), true );
	}
);
$fms_cat    = $fms_cats ? reset( $fms_cats ) : null;
$fms_source = get_post_meta( get_the_ID(), '_fms_source', true );
?>
<article <?php post_class( 'news-card reveal' ); ?>>
	<a class="news-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'fms-card', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="news-card__placeholder news-card__placeholder--<?php echo (int) ( $fms_index % 3 ); ?>"><?php fms_the_icon( 'calendar' ); ?></span>
		<?php endif; ?>
		<?php if ( $fms_cat ) : ?>
			<span class="tag"><?php echo esc_html( $fms_cat->name ); ?></span>
		<?php endif; ?>
	</a>
	<div class="news-card__body">
		<time class="news-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php fms_the_icon( 'calendar' ); ?><?php echo esc_html( fms_post_label() ); ?></time>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
		<?php if ( $fms_source ) : ?>
			<p class="news-card__source"><?php fms_the_icon( 'radio' ); ?><?php echo esc_html( $fms_source ); ?></p>
		<?php endif; ?>
		<a class="link-arrow news-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Lire la suite', 'fondation-ms' ); ?><span class="screen-reader-text"> : <?php the_title(); ?></span></a>
	</div>
</article>
