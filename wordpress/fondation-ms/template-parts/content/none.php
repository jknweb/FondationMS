<?php
/**
 * Message quand aucun contenu n'est trouvé.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="empty-state">
	<p><?php esc_html_e( 'Aucun contenu à afficher pour le moment.', 'fondation-ms' ); ?></p>
	<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l\'accueil', 'fondation-ms' ); ?></a></p>
</div>
