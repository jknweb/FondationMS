<?php
/**
 * Section : présentation de la fondation + mot du fondateur.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_tabs = array_filter(
	array(
		'mission'    => array( __( 'Mission', 'fondation-ms' ), fms_mod( 'about_mission' ) ),
		'vision'     => array( __( 'Vision', 'fondation-ms' ), fms_mod( 'about_vision' ) ),
		'historique' => array( __( 'Historique', 'fondation-ms' ), fms_mod( 'about_history' ) ),
	),
	function ( $tab ) {
		return '' !== trim( (string) $tab[1] );
	}
);
$fms_photo   = fms_image_url( fms_mod( 'founder_photo' ), 'molendo.webp', 'fms-portrait' );
$fms_message = fms_mod( 'founder_message' );
?>
<section class="section" id="mission">
	<div class="container split split--founder">
		<div class="split__content reveal">
			<?php if ( fms_mod( 'about_eyebrow' ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( fms_mod( 'about_eyebrow' ) ); ?></p>
			<?php endif; ?>
			<?php if ( fms_mod( 'about_title' ) ) : ?>
				<h2 class="section__title"><?php echo esc_html( fms_mod( 'about_title' ) ); ?></h2>
			<?php endif; ?>
			<?php echo wp_kses_post( wpautop( esc_html( fms_mod( 'about_text' ) ) ) ); ?>

			<?php if ( $fms_tabs ) : ?>
				<div class="tabs" data-tabs>
					<div class="tabs__list" role="tablist">
						<?php $fms_first = true; ?>
						<?php foreach ( $fms_tabs as $fms_key => $fms_tab ) : ?>
							<button type="button" role="tab" class="tabs__tab" id="tab-<?php echo esc_attr( $fms_key ); ?>" aria-controls="panel-<?php echo esc_attr( $fms_key ); ?>" aria-selected="<?php echo $fms_first ? 'true' : 'false'; ?>"><?php echo esc_html( $fms_tab[0] ); ?></button>
							<?php $fms_first = false; ?>
						<?php endforeach; ?>
					</div>
					<?php $fms_first = true; ?>
					<?php foreach ( $fms_tabs as $fms_key => $fms_tab ) : ?>
						<div class="tabs__panel" role="tabpanel" id="panel-<?php echo esc_attr( $fms_key ); ?>" aria-labelledby="tab-<?php echo esc_attr( $fms_key ); ?>" <?php echo $fms_first ? '' : 'hidden'; ?>>
							<?php echo wp_kses_post( wpautop( esc_html( $fms_tab[1] ) ) ); ?>
						</div>
						<?php $fms_first = false; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<ul class="values">
				<?php for ( $fms_i = 1; $fms_i <= 3; $fms_i++ ) : ?>
					<?php if ( ! fms_mod( "value{$fms_i}_title" ) ) { continue; } ?>
					<li>
						<span class="values__icon"><?php fms_the_icon( fms_mod( "value{$fms_i}_icon" ) ); ?></span>
						<div><strong><?php echo esc_html( fms_mod( "value{$fms_i}_title" ) ); ?></strong><p><?php echo esc_html( fms_mod( "value{$fms_i}_text" ) ); ?></p></div>
					</li>
				<?php endfor; ?>
			</ul>
		</div>

		<?php if ( $fms_message || fms_mod( 'founder_name' ) ) : ?>
			<aside class="founder reveal" aria-labelledby="founder-title">
				<?php if ( $fms_photo ) : ?>
					<figure class="founder__photo">
						<img src="<?php echo esc_url( $fms_photo ); ?>" alt="<?php echo esc_attr( fms_mod( 'founder_name' ) ); ?>" loading="lazy" width="600" height="800">
					</figure>
				<?php endif; ?>
				<div class="founder__body">
					<h3 id="founder-title" class="eyebrow"><?php echo esc_html( fms_mod( 'founder_label' ) ); ?></h3>
					<?php if ( $fms_message ) : ?>
						<blockquote class="founder__quote"><?php echo wp_kses_post( wpautop( esc_html( $fms_message ) ) ); ?></blockquote>
					<?php endif; ?>
					<?php if ( fms_mod( 'founder_link' ) ) : ?>
						<p><a class="link-arrow link-arrow--light" href="<?php echo esc_url( fms_link( fms_mod( 'founder_link' ) ) ); ?>"><?php esc_html_e( 'Lire le message complet', 'fondation-ms' ); ?></a></p>
					<?php endif; ?>
					<p class="founder__sign"><strong><?php echo esc_html( fms_mod( 'founder_name' ) ); ?></strong><span><?php echo esc_html( fms_mod( 'founder_role' ) ); ?></span></p>
				</div>
			</aside>
		<?php endif; ?>
	</div>
	<?php fms_edit_link( 'fondation' ); ?>
</section>
