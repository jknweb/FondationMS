<?php
/**
 * Carte d'un projet phare.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_terms = get_the_terms( get_the_ID(), 'ms_projet_cat' );
$fms_color = get_post_meta( get_the_ID(), '_fms_color', true );
$fms_icon  = get_post_meta( get_the_ID(), '_fms_icon', true );
$fms_map   = array( 'vert' => 1, 'rouge' => 2, 'jaune' => 3 );
?>
<article <?php post_class( 'project reveal' ); ?>>
	<a class="project__media project__media--<?php echo (int) ( $fms_map[ $fms_color ] ?? 1 ); ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'fms-card', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="project__icon"><?php fms_the_icon( $fms_icon ? $fms_icon : 'building' ); ?></span>
		<?php endif; ?>
		<?php if ( $fms_terms && ! is_wp_error( $fms_terms ) ) : ?>
			<span class="tag"><?php echo esc_html( $fms_terms[0]->name ); ?></span>
		<?php endif; ?>
	</a>
	<div class="project__body">
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo esc_html( get_the_excerpt() ); ?></p>
	</div>
</article>
