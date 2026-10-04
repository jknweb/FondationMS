<?php
/**
 * Page d'accueil : assemble les sections dans l'ordre choisi dans
 * Personnaliser › Fondation MS › Ordre et affichage des sections.
 *
 * Si une page statique est choisie comme page d'accueil et contient du texte,
 * ce contenu s'affiche après la bannière.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

get_header();

foreach ( fms_ordered_sections() as $fms_section ) {
	get_template_part( 'template-parts/sections/' . $fms_section );

	if ( 'hero' === $fms_section && 'page' === get_option( 'show_on_front' ) ) {
		while ( have_posts() ) {
			the_post();
			if ( '' !== trim( get_the_content() ) ) {
				echo '<section class="section"><div class="container container--narrow entry-content">';
				the_content();
				echo '</div></section>';
			}
		}
	}
}

get_footer();
