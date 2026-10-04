<?php
/**
 * Types de contenus personnalisés et taxonomies.
 *
 * - Domaines d'intervention (ms_domaine)
 * - Projets phares (ms_projet) + catégories de projets (ms_projet_cat)
 * - Photos de la galerie (ms_photo)
 * - Émissions de radio (ms_emission)
 *
 * Les actualités utilisent les Articles natifs de WordPress, rangés dans
 * les catégories « Actualités » et « La une ».
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Libellés standard d'un type de contenu.
 *
 * @param string $singular Nom au singulier.
 * @param string $plural   Nom au pluriel.
 * @param bool   $feminine Accord au féminin.
 * @return array
 */
function fms_cpt_labels( $singular, $plural, $feminine = false ) {
	$new = $feminine ? __( 'Nouvelle', 'fondation-ms' ) : __( 'Nouveau', 'fondation-ms' );
	return array(
		'name'                  => $plural,
		'singular_name'         => $singular,
		'menu_name'             => $plural,
		'all_items'             => $plural,
		'add_new'               => __( 'Ajouter', 'fondation-ms' ),
		'add_new_item'          => sprintf( '%1$s %2$s', $new, mb_strtolower( $singular ) ),
		'new_item'              => sprintf( '%1$s %2$s', $new, mb_strtolower( $singular ) ),
		'edit_item'             => sprintf( __( 'Modifier : %s', 'fondation-ms' ), mb_strtolower( $singular ) ),
		'view_item'             => __( 'Voir', 'fondation-ms' ),
		'search_items'          => __( 'Rechercher', 'fondation-ms' ),
		'not_found'             => __( 'Aucun élément trouvé.', 'fondation-ms' ),
		'not_found_in_trash'    => __( 'Aucun élément dans la corbeille.', 'fondation-ms' ),
		'featured_image'        => __( 'Image', 'fondation-ms' ),
		'set_featured_image'    => __( 'Choisir l\'image', 'fondation-ms' ),
		'remove_featured_image' => __( 'Retirer l\'image', 'fondation-ms' ),
		'use_featured_image'    => __( 'Utiliser comme image', 'fondation-ms' ),
	);
}

/**
 * Enregistre les types de contenus et taxonomies.
 */
function fms_register_post_types() {
	register_post_type(
		'ms_domaine',
		array(
			'labels'        => fms_cpt_labels( __( 'Domaine d\'intervention', 'fondation-ms' ), __( 'Domaines d\'intervention', 'fondation-ms' ) ),
			'description'   => __( 'Cartes de la section « Domaines d\'intervention » de l\'accueil.', 'fondation-ms' ),
			'public'        => false,
			'show_ui'       => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-screenoptions',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'page-attributes', 'revisions' ),
			'template'      => array( array( 'core/paragraph', array( 'placeholder' => __( 'Courte description du domaine (une ou deux phrases).', 'fondation-ms' ) ) ) ),
		)
	);

	register_post_type(
		'ms_projet',
		array(
			'labels'        => fms_cpt_labels( __( 'Projet phare', 'fondation-ms' ), __( 'Projets phares', 'fondation-ms' ) ),
			'description'   => __( 'Projets mis en avant sur l\'accueil, avec une page de détail.', 'fondation-ms' ),
			'public'        => true,
			'has_archive'   => true,
			'rewrite'       => array( 'slug' => 'projets' ),
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-building',
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'page-attributes', 'revisions' ),
		)
	);

	register_taxonomy(
		'ms_projet_cat',
		'ms_projet',
		array(
			'labels'            => array(
				'name'          => __( 'Catégories de projets', 'fondation-ms' ),
				'singular_name' => __( 'Catégorie de projet', 'fondation-ms' ),
				'menu_name'     => __( 'Catégories', 'fondation-ms' ),
				'add_new_item'  => __( 'Ajouter une catégorie', 'fondation-ms' ),
				'edit_item'     => __( 'Modifier la catégorie', 'fondation-ms' ),
				'all_items'     => __( 'Toutes les catégories', 'fondation-ms' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'categorie-projet' ),
		)
	);

	register_post_type(
		'ms_photo',
		array(
			'labels'        => fms_cpt_labels( __( 'Photo', 'fondation-ms' ), __( 'Galerie photo', 'fondation-ms' ), true ),
			'description'   => __( 'Photos de la galerie et du diaporama de l\'accueil. Le titre sert de légende.', 'fondation-ms' ),
			'public'        => false,
			'show_ui'       => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-format-gallery',
			'menu_position' => 23,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'ms_emission',
		array(
			'labels'        => fms_cpt_labels( __( 'Émission', 'fondation-ms' ), __( 'Programme radio', 'fondation-ms' ), true ),
			'description'   => __( 'Émissions affichées dans le programme du jour de la radio.', 'fondation-ms' ),
			'public'        => false,
			'show_ui'       => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-microphone',
			'menu_position' => 24,
			'supports'      => array( 'title' ),
		)
	);
}
add_action( 'init', 'fms_register_post_types' );

/**
 * Titre indicatif dans l'éditeur.
 *
 * @param string  $text Texte par défaut.
 * @param WP_Post $post Contenu.
 * @return string
 */
function fms_title_placeholder( $text, $post ) {
	switch ( $post->post_type ) {
		case 'ms_domaine':
			return __( 'Nom du domaine (ex. Éducation)', 'fondation-ms' );
		case 'ms_projet':
			return __( 'Nom du projet (ex. Le pont Kaba)', 'fondation-ms' );
		case 'ms_photo':
			return __( 'Légende de la photo', 'fondation-ms' );
		case 'ms_emission':
			return __( 'Nom de l\'émission', 'fondation-ms' );
	}
	return $text;
}
add_filter( 'enter_title_here', 'fms_title_placeholder', 10, 2 );

/**
 * Les photos et émissions utilisent l'éditeur classique (formulaire simple, sans blocs).
 *
 * @param bool   $use       Utiliser l'éditeur de blocs.
 * @param string $post_type Type de contenu.
 * @return bool
 */
function fms_block_editor_for( $use, $post_type ) {
	return in_array( $post_type, array( 'ms_photo', 'ms_emission' ), true ) ? false : $use;
}
add_filter( 'use_block_editor_for_post_type', 'fms_block_editor_for', 10, 2 );

/**
 * Colonnes d'administration : aperçu de la photo et horaires des émissions.
 */
function fms_admin_columns() {
	add_filter(
		'manage_ms_photo_posts_columns',
		function ( $cols ) {
			return array_slice( $cols, 0, 1, true ) + array( 'fms_thumb' => __( 'Photo', 'fondation-ms' ) ) + array_slice( $cols, 1, null, true );
		}
	);
	add_action(
		'manage_ms_photo_posts_custom_column',
		function ( $col, $post_id ) {
			if ( 'fms_thumb' === $col ) {
				echo get_the_post_thumbnail( $post_id, array( 80, 60 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		},
		10,
		2
	);
	add_filter(
		'manage_ms_emission_posts_columns',
		function ( $cols ) {
			unset( $cols['date'] );
			$cols['fms_hours'] = __( 'Horaire', 'fondation-ms' );
			$cols['fms_days']  = __( 'Jours', 'fondation-ms' );
			return $cols;
		}
	);
	add_action(
		'manage_ms_emission_posts_custom_column',
		function ( $col, $post_id ) {
			if ( 'fms_hours' === $col ) {
				echo esc_html( get_post_meta( $post_id, '_fms_start', true ) . ' – ' . get_post_meta( $post_id, '_fms_end', true ) );
			}
			if ( 'fms_days' === $col ) {
				$days = (array) get_post_meta( $post_id, '_fms_days', true );
				$all  = fms_weekdays();
				$days = array_filter( $days );
				echo esc_html( $days ? implode( ', ', array_intersect_key( $all, array_flip( $days ) ) ) : __( 'Tous les jours', 'fondation-ms' ) );
			}
		},
		10,
		2
	);
}
add_action( 'admin_init', 'fms_admin_columns' );

/**
 * Jours de la semaine (numéro ISO => libellé).
 *
 * @return array<int,string>
 */
function fms_weekdays() {
	return array(
		1 => __( 'Lundi', 'fondation-ms' ),
		2 => __( 'Mardi', 'fondation-ms' ),
		3 => __( 'Mercredi', 'fondation-ms' ),
		4 => __( 'Jeudi', 'fondation-ms' ),
		5 => __( 'Vendredi', 'fondation-ms' ),
		6 => __( 'Samedi', 'fondation-ms' ),
		7 => __( 'Dimanche', 'fondation-ms' ),
	);
}

/**
 * Émissions du jour (fuseau horaire du site), triées par heure de début.
 *
 * @return WP_Post[]
 */
function fms_today_shows() {
	$today = (int) wp_date( 'N' );
	$shows = get_posts(
		array(
			'post_type'      => 'ms_emission',
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'no_found_rows'  => true,
		)
	);
	$shows = array_filter(
		$shows,
		function ( $show ) use ( $today ) {
			$days = array_filter( (array) get_post_meta( $show->ID, '_fms_days', true ) );
			return empty( $days ) || in_array( $today, array_map( 'intval', $days ), true );
		}
	);
	usort(
		$shows,
		function ( $a, $b ) {
			return strcmp( (string) get_post_meta( $a->ID, '_fms_start', true ), (string) get_post_meta( $b->ID, '_fms_start', true ) );
		}
	);
	return $shows;
}

/**
 * Crée les catégories d'actualités et rafraîchit les permaliens à l'activation du thème.
 */
function fms_activate() {
	fms_register_post_types();
	if ( ! term_exists( 'actualites', 'category' ) ) {
		wp_insert_term( __( 'Actualités', 'fondation-ms' ), 'category', array( 'slug' => 'actualites' ) );
	}
	if ( ! term_exists( 'la-une', 'category' ) ) {
		wp_insert_term( __( 'La une', 'fondation-ms' ), 'category', array( 'slug' => 'la-une', 'description' => __( 'L\'article le plus récent de cette catégorie s\'affiche dans l\'encadré blanc de la bannière d\'accueil.', 'fondation-ms' ) ) );
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'fms_activate' );
