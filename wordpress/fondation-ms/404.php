<?php
/**
 * Page introuvable.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/page-hero', null, array( 'title' => __( 'Page introuvable', 'fondation-ms' ) ) );
?>
<section class="section">
	<div class="container container--narrow entry-content">
		<p><?php esc_html_e( 'La page demandée n\'existe pas ou a été déplacée. Essayez une recherche ou revenez à l\'accueil.', 'fondation-ms' ); ?></p>
		<?php get_search_form(); ?>
		<p><a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Retour à l\'accueil', 'fondation-ms' ); ?></a></p>
	</div>
</section>
<?php
get_footer();
