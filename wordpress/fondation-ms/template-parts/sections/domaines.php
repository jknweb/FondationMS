<?php
/**
 * Section : domaines d'intervention (type de contenu « ms_domaine »).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_domaines = get_posts(
	array(
		'post_type'      => 'ms_domaine',
		'posts_per_page' => 20,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'no_found_rows'  => true,
	)
);
if ( ! $fms_domaines ) {
	return;
}
$fms_has_main = (bool) array_filter(
	$fms_domaines,
	function ( $d ) {
		return (bool) get_post_meta( $d->ID, '_fms_featured', true );
	}
);
$fms_icon_bg = array( 'vert' => 'teal', 'rouge' => 'coral', 'jaune' => 'gold' );
?>
<section class="section section--tint" id="domaines">
	<div class="container">
		<?php fms_section_head( fms_mod( 'domaines_eyebrow' ), fms_mod( 'domaines_title' ), fms_mod( 'domaines_intro' ) ); ?>

		<div class="domains<?php echo $fms_has_main ? '' : ' domains--even'; ?>">
			<?php foreach ( $fms_domaines as $fms_d ) : ?>
				<?php
				$fms_color    = get_post_meta( $fms_d->ID, '_fms_color', true );
				$fms_color    = $fms_color ? $fms_color : 'vert';
				$fms_featured = (bool) get_post_meta( $fms_d->ID, '_fms_featured', true );
				$fms_items    = fms_lines( get_post_meta( $fms_d->ID, '_fms_items', true ) );
				$fms_box      = get_post_meta( $fms_d->ID, '_fms_box_text', true );
				$fms_icon     = get_post_meta( $fms_d->ID, '_fms_icon', true );
				?>
				<article class="domain domain--<?php echo esc_attr( $fms_color ); ?><?php echo $fms_featured ? ' domain--main' : ''; ?> reveal">
					<div class="domain__head">
						<span class="card__icon card__icon--<?php echo esc_attr( $fms_icon_bg[ $fms_color ] ?? 'teal' ); ?>"><?php fms_the_icon( $fms_icon ? $fms_icon : 'book' ); ?></span>
						<h3><?php echo esc_html( get_the_title( $fms_d ) ); ?></h3>
					</div>
					<?php if ( $fms_items ) : ?>
						<ul class="domain__list">
							<?php foreach ( $fms_items as $fms_item ) : ?>
								<?php $fms_parts = array_map( 'trim', explode( '|', $fms_item, 2 ) ); ?>
								<li>
									<strong><?php echo esc_html( $fms_parts[0] ); ?></strong>
									<?php if ( ! empty( $fms_parts[1] ) ) : ?>
										<span><?php echo esc_html( $fms_parts[1] ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<div class="domain__text"><?php echo wp_kses_post( apply_filters( 'the_content', $fms_d->post_content ) ); ?></div>
					<?php endif; ?>
					<?php if ( $fms_box ) : ?>
						<div class="domain__highlight">
							<?php if ( get_post_meta( $fms_d->ID, '_fms_box_label', true ) ) : ?>
								<span class="tag tag--static"><?php echo esc_html( get_post_meta( $fms_d->ID, '_fms_box_label', true ) ); ?></span>
							<?php endif; ?>
							<p><?php echo esc_html( $fms_box ); ?></p>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
	<?php fms_edit_link( 'domaines' ); ?>
</section>
