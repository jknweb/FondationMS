<?php
/**
 * Section : galerie photo (à gauche) + programme de la radio (à droite).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_photos = get_posts(
	array(
		'post_type'      => 'ms_photo',
		'posts_per_page' => 6,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
		'meta_key'       => '_thumbnail_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'no_found_rows'  => true,
	)
);
$fms_shows = fms_today_shows();
$fms_url   = fms_mod( 'radio_url' );
?>
<section class="section" id="radio">
	<div class="container media-split<?php echo $fms_photos ? '' : ' media-split--solo'; ?>">
		<?php if ( $fms_photos ) : ?>
			<div class="media-split__gallery reveal">
				<header class="media-split__head">
					<?php if ( fms_mod( 'gallery_eyebrow' ) ) : ?>
						<p class="eyebrow"><?php echo esc_html( fms_mod( 'gallery_eyebrow' ) ); ?></p>
					<?php endif; ?>
					<h2 class="section__title"><?php echo esc_html( fms_mod( 'gallery_title' ) ); ?></h2>
				</header>
				<div class="thumbs" data-n="<?php echo (int) count( $fms_photos ); ?>">
					<?php foreach ( $fms_photos as $fms_photo ) : ?>
						<?php $fms_caption = get_the_title( $fms_photo ); ?>
						<a class="thumb" href="<?php echo esc_url( get_the_post_thumbnail_url( $fms_photo, 'full' ) ); ?>" data-lightbox="galerie" data-caption="<?php echo esc_attr( $fms_caption ); ?>">
							<?php echo get_the_post_thumbnail( $fms_photo, 'fms-card', array( 'alt' => esc_attr( $fms_caption ), 'loading' => 'lazy' ) ); ?>
							<span class="thumb__zoom" aria-hidden="true">+</span>
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: légende. */ __( 'Agrandir : %s', 'fondation-ms' ), $fms_caption ) ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="radio reveal" aria-labelledby="radio-title">
			<div class="radio__head">
				<span class="radio__icon"><?php fms_the_icon( 'radio' ); ?></span>
				<div>
					<?php if ( fms_mod( 'radio_kicker' ) ) : ?>
						<p class="radio__kicker"><?php echo esc_html( fms_mod( 'radio_kicker' ) ); ?></p>
					<?php endif; ?>
					<h2 id="radio-title" class="radio__name"><?php echo esc_html( fms_mod( 'radio_name' ) ); ?></h2>
				</div>
			</div>

			<?php if ( $fms_shows ) : ?>
				<div class="radio__now" data-radio-now aria-live="polite"></div>
				<h3 class="radio__subtitle"><?php esc_html_e( 'Programme du jour', 'fondation-ms' ); ?></h3>
				<ol class="radio__list" data-radio-list>
					<?php foreach ( $fms_shows as $fms_show ) : ?>
						<?php
						$fms_start = get_post_meta( $fms_show->ID, '_fms_start', true );
						$fms_end   = get_post_meta( $fms_show->ID, '_fms_end', true );
						?>
						<li class="radio__item" data-start="<?php echo esc_attr( $fms_start ); ?>" data-end="<?php echo esc_attr( $fms_end ); ?>">
							<span class="radio__time"><?php echo esc_html( $fms_start . ' – ' . $fms_end ); ?></span>
							<span class="radio__show"><?php echo esc_html( get_the_title( $fms_show ) ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php else : ?>
				<p class="radio__empty"><?php echo esc_html( fms_mod( 'radio_empty' ) ); ?></p>
			<?php endif; ?>

			<?php if ( fms_mod( 'radio_note' ) ) : ?>
				<p class="radio__note"><?php echo esc_html( fms_mod( 'radio_note' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $fms_url ) : ?>
				<a href="<?php echo esc_url( $fms_url ); ?>" class="btn btn--accent btn--block" target="_blank" rel="noopener"><?php echo esc_html( fms_mod( 'radio_button' ) ); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<?php fms_edit_link( 'radio' ); ?>
</section>
