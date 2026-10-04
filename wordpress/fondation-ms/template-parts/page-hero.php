<?php
/**
 * Bandeau de titre des pages intérieures.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_title    = isset( $args['title'] ) ? $args['title'] : '';
$fms_subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$fms_eyebrow  = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
?>
<section class="page-hero">
	<div class="hero__bg" aria-hidden="true"></div>
	<div class="container">
		<?php if ( $fms_eyebrow ) : ?>
			<p class="eyebrow eyebrow--light"><?php echo esc_html( $fms_eyebrow ); ?></p>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $fms_title ); ?></h1>
		<?php if ( $fms_subtitle ) : ?>
			<div class="page-hero__subtitle"><?php echo wp_kses( $fms_subtitle, fms_kses_allowed() ); ?></div>
		<?php endif; ?>
	</div>
</section>
