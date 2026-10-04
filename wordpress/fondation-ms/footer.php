<?php
/**
 * Pied de page du site.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_email     = fms_mod( 'contact_email' );
$fms_phone     = fms_mod( 'contact_phone' );
$fms_addresses = fms_lines( fms_mod( 'contact_addresses' ) );
?>
</main>

<footer class="footer" id="contact">
	<div class="container footer__grid">
		<div class="footer__brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo logo--light">
				<span class="logo__mark" aria-hidden="true">MS</span>
				<span class="logo__text"><?php bloginfo( 'name' ); ?></span>
			</a>
			<?php if ( fms_mod( 'footer_text' ) ) : ?>
				<p><?php echo esc_html( fms_mod( 'footer_text' ) ); ?></p>
			<?php endif; ?>
			<?php $fms_socials = fms_socials(); ?>
			<?php if ( $fms_socials ) : ?>
				<ul class="socials">
					<?php foreach ( $fms_socials as $fms_key => $fms_social ) : ?>
						<li><a href="<?php echo esc_url( $fms_social[1] ); ?>" aria-label="<?php echo esc_attr( $fms_social[0] ); ?>" target="_blank" rel="noopener"><?php fms_the_icon( $fms_key ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php foreach ( array( 'footer_1', 'footer_2' ) as $fms_location ) : ?>
			<?php if ( has_nav_menu( $fms_location ) ) : ?>
				<div>
					<h2 class="footer__title"><?php echo esc_html( wp_get_nav_menu_name( $fms_location ) ); ?></h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => $fms_location,
							'container'      => false,
							'depth'          => 1,
						)
					);
					?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>

		<div>
			<h2 class="footer__title"><?php esc_html_e( 'Contact', 'fondation-ms' ); ?></h2>
			<ul>
				<?php foreach ( $fms_addresses as $fms_address ) : ?>
					<li><?php echo esc_html( $fms_address ); ?></li>
				<?php endforeach; ?>
				<?php if ( $fms_email ) : ?>
					<li><a href="<?php echo esc_url( 'mailto:' . antispambot( $fms_email ) ); ?>"><?php echo esc_html( antispambot( $fms_email ) ); ?></a></li>
				<?php endif; ?>
				<?php if ( $fms_phone ) : ?>
					<li><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $fms_phone ) ); ?>"><?php echo esc_html( $fms_phone ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="container footer__bottom">
		<p>© <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( fms_mod( 'footer_copyright' ) ); ?></p>
		<?php if ( get_privacy_policy_url() ) : ?>
			<ul><li><?php the_privacy_policy_link(); ?></li></ul>
		<?php endif; ?>
	</div>
</footer>

<a href="#top" class="to-top" id="to-top" aria-label="<?php esc_attr_e( 'Revenir en haut', 'fondation-ms' ); ?>"><?php fms_the_icon( 'up' ); ?></a>

<?php wp_footer(); ?>
</body>
</html>
