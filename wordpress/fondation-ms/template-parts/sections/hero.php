<?php
/**
 * Section : bannière d'accueil + encadré « La une ».
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_une = get_posts(
	array(
		'category_name'       => 'la-une',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$fms_points = fms_lines( fms_mod( 'hero_points' ) );
?>
<section class="hero">
	<div class="hero__bg" aria-hidden="true"></div>
	<div class="container hero__inner<?php echo $fms_une ? '' : ' hero__inner--solo'; ?>">
		<div class="hero__content reveal">
			<?php if ( fms_mod( 'hero_eyebrow' ) ) : ?>
				<p class="eyebrow eyebrow--light"><?php echo esc_html( fms_mod( 'hero_eyebrow' ) ); ?></p>
			<?php endif; ?>
			<h1 class="hero__title">
				<?php echo esc_html( fms_mod( 'hero_title_before' ) ); ?>
				<?php if ( fms_mod( 'hero_title_highlight' ) ) : ?>
					<em><?php echo esc_html( fms_mod( 'hero_title_highlight' ) ); ?></em>
				<?php endif; ?>
				<?php echo esc_html( fms_mod( 'hero_title_after' ) ); ?>
			</h1>
			<?php if ( fms_mod( 'hero_text' ) ) : ?>
				<p class="hero__lead"><?php echo esc_html( fms_mod( 'hero_text' ) ); ?></p>
			<?php endif; ?>
			<div class="hero__actions">
				<?php if ( fms_mod( 'hero_btn1_label' ) && fms_mod( 'hero_btn1_url' ) ) : ?>
					<a href="<?php echo esc_url( fms_link( fms_mod( 'hero_btn1_url' ) ) ); ?>" class="btn btn--accent btn--lg"><?php echo esc_html( fms_mod( 'hero_btn1_label' ) ); ?></a>
				<?php endif; ?>
				<?php if ( fms_mod( 'hero_btn2_label' ) && fms_mod( 'hero_btn2_url' ) ) : ?>
					<a href="<?php echo esc_url( fms_link( fms_mod( 'hero_btn2_url' ) ) ); ?>" class="btn btn--ghost btn--lg"><?php echo esc_html( fms_mod( 'hero_btn2_label' ) ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( $fms_points ) : ?>
				<ul class="hero__trust">
					<?php foreach ( $fms_points as $fms_point ) : ?>
						<li><?php fms_the_icon( 'check' ); ?> <?php echo esc_html( $fms_point ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php if ( $fms_une ) : ?>
			<?php
			$fms_post   = $fms_une[0];
			$fms_label  = get_post_meta( $fms_post->ID, '_fms_label', true );
			$fms_source = get_post_meta( $fms_post->ID, '_fms_source', true );
			?>
			<aside class="hero__card reveal" aria-label="<?php echo esc_attr( fms_mod( 'hero_card_title' ) ); ?>">
				<?php if ( fms_mod( 'hero_card_title' ) ) : ?>
					<p class="hero__card-title"><?php echo esc_html( fms_mod( 'hero_card_title' ) ); ?></p>
				<?php endif; ?>
				<span class="pill"><?php fms_the_icon( 'calendar' ); ?> <?php echo esc_html( $fms_label ? $fms_label : get_the_date( '', $fms_post ) ); ?></span>
				<h2 class="hero__card-heading"><a href="<?php echo esc_url( get_permalink( $fms_post ) ); ?>"><?php echo esc_html( get_the_title( $fms_post ) ); ?></a></h2>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt( $fms_post ), 28, '…' ) ); ?></p>
				<?php if ( $fms_source ) : ?>
					<p class="source"><?php fms_the_icon( 'radio' ); ?> <?php echo esc_html( sprintf( /* translators: %s: source. */ __( 'Source : %s', 'fondation-ms' ), $fms_source ) ); ?></p>
				<?php endif; ?>
				<?php if ( fms_mod( 'hero_card_link' ) ) : ?>
					<a href="<?php echo esc_url( fms_link( '#actualites' ) ); ?>" class="link-arrow"><?php echo esc_html( fms_mod( 'hero_card_link' ) ); ?></a>
				<?php endif; ?>
			</aside>
		<?php endif; ?>
	</div>
	<?php fms_edit_link( 'hero' ); ?>
</section>
