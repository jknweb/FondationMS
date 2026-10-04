<?php
/**
 * Section : s'engager (deux cartes).
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_bg = array( 'teal', 'coral' );
?>
<section class="section section--tint" id="engager">
	<div class="container">
		<?php fms_section_head( fms_mod( 'engage_eyebrow' ), fms_mod( 'engage_title' ) ); ?>
		<div class="engage engage--2">
			<?php for ( $fms_i = 1; $fms_i <= 2; $fms_i++ ) : ?>
				<?php if ( ! fms_mod( "engage{$fms_i}_title" ) ) { continue; } ?>
				<article class="engage__item reveal">
					<span class="card__icon card__icon--<?php echo esc_attr( $fms_bg[ $fms_i - 1 ] ); ?>"><?php fms_the_icon( fms_mod( "engage{$fms_i}_icon" ) ); ?></span>
					<h3><?php echo esc_html( fms_mod( "engage{$fms_i}_title" ) ); ?></h3>
					<p><?php echo esc_html( fms_mod( "engage{$fms_i}_text" ) ); ?></p>
					<?php if ( fms_mod( "engage{$fms_i}_label" ) && fms_mod( "engage{$fms_i}_url" ) ) : ?>
						<a href="<?php echo esc_url( fms_link( fms_mod( "engage{$fms_i}_url" ) ) ); ?>" class="btn <?php echo 1 === $fms_i ? 'btn--primary' : 'btn--accent'; ?>"><?php echo esc_html( fms_mod( "engage{$fms_i}_label" ) ); ?></a>
					<?php endif; ?>
				</article>
			<?php endfor; ?>
		</div>
	</div>
	<?php fms_edit_link( 'engager' ); ?>
</section>
