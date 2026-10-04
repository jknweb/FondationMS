<?php
/**
 * Section : chiffres clés.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_colors = array( 'vert' => 'green', 'rouge' => 'red', 'jaune' => 'gold' );
$fms_stats  = array();
for ( $fms_i = 1; $fms_i <= 5; $fms_i++ ) {
	$fms_number = fms_mod( "stat{$fms_i}_number" );
	if ( '' === (string) $fms_number ) {
		continue;
	}
	$fms_color   = fms_mod( "stat{$fms_i}_color" );
	$fms_stats[] = array(
		'prefix' => fms_mod( "stat{$fms_i}_prefix" ),
		'number' => (int) $fms_number,
		'label'  => fms_mod( "stat{$fms_i}_label" ),
		'color'  => isset( $fms_colors[ $fms_color ] ) ? $fms_colors[ $fms_color ] : 'green',
	);
}
if ( ! $fms_stats ) {
	return;
}
?>
<section class="figures" id="chiffres" aria-label="<?php esc_attr_e( 'La fondation en chiffres', 'fondation-ms' ); ?>">
	<div class="container">
		<ul class="figures__grid" style="--cols:<?php echo (int) count( $fms_stats ); ?>">
			<?php foreach ( $fms_stats as $fms_stat ) : ?>
				<li class="figure figure--<?php echo esc_attr( $fms_stat['color'] ); ?>">
					<span class="figure__num"><?php echo esc_html( $fms_stat['prefix'] ); ?><span data-count="<?php echo esc_attr( $fms_stat['number'] ); ?>"><?php echo esc_html( number_format_i18n( $fms_stat['number'] ) ); ?></span></span>
					<span class="figure__label"><?php echo esc_html( $fms_stat['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php fms_edit_link( 'chiffres' ); ?>
	</div>
</section>
