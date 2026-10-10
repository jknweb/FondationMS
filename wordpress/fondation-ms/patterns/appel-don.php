<?php
/**
 * Title: Appel à l'engagement
 * Slug: fondation-ms/appel-don
 * Categories: fondation-ms, call-to-action
 * Keywords: don, soutenir, engagement, bouton
 * Description: Bandeau vert avec un titre, un court texte et deux boutons (faire un don, devenir bénévole).
 *
 * @package Fondation_MS
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"64px","bottom":"64px","left":"24px","right":"24px"}}},"backgroundColor":"vert","textColor":"blanc","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-blanc-color has-vert-background-color has-text-color has-background" style="padding-top:64px;padding-right:24px;padding-bottom:64px;padding-left:24px"><!-- wp:heading {"textAlign":"center","textColor":"blanc","fontFamily":"fraunces","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-text-align-center has-blanc-color has-text-color has-fraunces-font-family has-x-large-font-size"><?php esc_html_e( 'Engagez-vous à nos côtés', 'fondation-ms' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size"><?php esc_html_e( 'Chaque geste compte : soutenez les actions de la Fondation MS.', 'fondation-ms' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"rouge","textColor":"blanc"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-blanc-color has-rouge-background-color has-text-color has-background wp-element-button"><?php esc_html_e( 'Faire un don', 'fondation-ms' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","textColor":"blanc"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-blanc-color has-text-color wp-element-button"><?php esc_html_e( 'Devenir bénévole', 'fondation-ms' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
