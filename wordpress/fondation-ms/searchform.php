<?php
/**
 * Formulaire de recherche.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

$fms_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $fms_id ); ?>"><?php esc_html_e( 'Rechercher', 'fondation-ms' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $fms_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Rechercher…', 'fondation-ms' ); ?>">
	<button type="submit" class="btn btn--primary"><?php fms_the_icon( 'search' ); ?><span class="screen-reader-text"><?php esc_html_e( 'Rechercher', 'fondation-ms' ); ?></span></button>
</form>
