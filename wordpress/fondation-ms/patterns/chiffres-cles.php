<?php
/**
 * Title: Chiffres clés
 * Slug: fondation-ms/chiffres-cles
 * Categories: fondation-ms, columns
 * Keywords: chiffres, statistiques, impact
 * Description: Trois colonnes avec un grand chiffre et sa légende, pour présenter l'impact de la fondation.
 *
 * @package Fondation_MS
 */

$fms_chiffres = array(
	array( '+5000', __( 'membres bénéficiaires', 'fondation-ms' ) ),
	array( '+2000', __( 'familles accompagnées', 'fondation-ms' ) ),
	array( '+50', __( 'actions réalisées', 'fondation-ms' ) ),
);
?>
<!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"top":"48px","bottom":"48px"}}}} -->
<div class="wp-block-columns alignwide" style="padding-top:48px;padding-bottom:48px">
<?php foreach ( $fms_chiffres as $fms_chiffre ) : ?>
<!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","bottom":"24px","left":"24px","right":"24px"}},"border":{"radius":"18px"}},"backgroundColor":"vert-clair"} -->
<div class="wp-block-column has-vert-clair-background-color has-background" style="border-radius:18px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:paragraph {"align":"center","textColor":"vert","fontFamily":"fraunces","fontSize":"xx-large"} -->
<p class="has-text-align-center has-vert-color has-text-color has-fraunces-font-family has-xx-large-font-size"><strong><?php echo esc_html( $fms_chiffre[0] ); ?></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","textColor":"texte"} -->
<p class="has-text-align-center has-texte-color has-text-color"><?php echo esc_html( $fms_chiffre[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->
<?php endforeach; ?>
</div>
<!-- /wp:columns -->
