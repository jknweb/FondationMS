<?php
/**
 * En-tête du site.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/icons' ); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Aller au contenu', 'fondation-ms' ); ?></a>

<header class="header" id="top">
	<div class="container header__inner">
		<?php if ( has_custom_logo() ) : ?>
			<div class="logo logo--custom"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" rel="home">
				<span class="logo__mark" aria-hidden="true">MS</span>
				<span class="logo__text"><?php bloginfo( 'name' ); ?></span>
			</a>
		<?php endif; ?>

		<nav class="nav" id="nav" aria-label="<?php esc_attr_e( 'Navigation principale', 'fondation-ms' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'fms_fallback_menu',
				)
			);
			$cta_label = fms_mod( 'header_cta_label' );
			$cta_url   = fms_link( fms_mod( 'header_cta_url' ) );
			if ( $cta_label && $cta_url ) :
				?>
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--accent nav__cta"><?php echo esc_html( $cta_label ); ?></a>
			<?php endif; ?>
		</nav>

		<button class="burger" id="burger" type="button" aria-label="<?php esc_attr_e( 'Ouvrir le menu', 'fondation-ms' ); ?>" aria-controls="nav" aria-expanded="false">
			<span></span><span></span><span></span>
		</button>
	</div>
</header>

<main id="main">
