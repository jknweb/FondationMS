<?php
/**
 * Import du contenu initial : reprend le contenu réel du site HTML d'origine
 * (domaines, projets phares, actualités, photos, menus) dans WordPress.
 *
 * Lancé une seule fois depuis Tableau de bord › Fondation MS. Aucun contenu
 * existant n'est modifié ni supprimé ; un élément déjà présent (même titre) est ignoré.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Copie une image du thème dans la médiathèque (une seule fois).
 *
 * @param string $file  Nom du fichier dans assets/images.
 * @param string $title Titre / texte alternatif.
 * @return int Identifiant de la pièce jointe (0 en cas d'échec).
 */
function fms_import_image( $file, $title ) {
	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'meta_key'       => '_fms_source_file', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $file, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	$path = FMS_DIR . '/assets/images/' . $file;
	if ( ! file_exists( $path ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( $file );
	if ( ! $tmp || ! copy( $path, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload(
		array(
			'name'     => $file,
			'tmp_name' => $tmp,
		),
		0,
		$title
	);
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $title );
	update_post_meta( $id, '_fms_source_file', $file );
	return (int) $id;
}

/**
 * Crée un contenu s'il n'existe pas déjà (recherche par titre et type).
 *
 * @param array $args Arguments de wp_insert_post() + 'meta', 'image', 'terms'.
 * @return int Identifiant du contenu (0 si ignoré).
 */
function fms_import_post( $args ) {
	$found = get_posts(
		array(
			'post_type'      => $args['post_type'],
			'title'          => $args['post_title'],
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $found ) {
		return 0;
	}
	$meta  = isset( $args['meta'] ) ? $args['meta'] : array();
	$image = isset( $args['image'] ) ? $args['image'] : '';
	$terms = isset( $args['terms'] ) ? $args['terms'] : array();
	unset( $args['meta'], $args['image'], $args['terms'] );

	$args = wp_parse_args(
		$args,
		array(
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
		)
	);
	$id = wp_insert_post( wp_slash( $args ), true );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}
	if ( $image ) {
		$attachment = fms_import_image( $image, $args['post_title'] );
		if ( $attachment ) {
			set_post_thumbnail( $id, $attachment );
		}
	}
	foreach ( $terms as $taxonomy => $slugs ) {
		$ids = array();
		foreach ( (array) $slugs as $slug => $name ) {
			$term = term_exists( $slug, $taxonomy );
			if ( ! $term ) {
				$term = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			}
			if ( ! is_wp_error( $term ) ) {
				$ids[] = (int) $term['term_id'];
			}
		}
		wp_set_object_terms( $id, $ids, $taxonomy );
	}
	return (int) $id;
}

/**
 * Bloc paragraphe pour l'éditeur.
 *
 * @param string $text Texte.
 * @return string
 */
function fms_p( $text ) {
	return "<!-- wp:paragraph -->\n<p>" . esc_html( $text ) . "</p>\n<!-- /wp:paragraph -->";
}

/**
 * Lance l'import. Renvoie le nombre d'éléments créés par type.
 *
 * @return array<string,int>
 */
function fms_run_import() {
	fms_activate();
	$count = array( 'domaines' => 0, 'projets' => 0, 'articles' => 0, 'photos' => 0, 'menus' => 0 );

	// Domaines d'intervention.
	$domaines = array(
		array( 'Éducation', 'book', 'vert', 1, 'Soutenir les élèves et les écoles pour que chaque enfant puisse apprendre dans de bonnes conditions.', "Gratuité de l'enseignement | Soutien à la gratuité de l'enseignement pour que chaque enfant puisse aller à l'école.\nFournitures scolaires | Distribution de fournitures aux élèves, notamment à chaque rentrée.\nInfrastructures scolaires | Initiatives pour améliorer les écoles, dont l'opération « Pas une école sans banc ».", 'Opération', '« Pas une école sans banc »' ),
		array( 'Santé', 'health', 'rouge', 0, 'Agir pour la santé et le bien-être des enfants, des familles et des communautés.', '', '', '' ),
		array( 'Jeunesse', 'ball', 'jaune', 0, 'Promouvoir l\'épanouissement de l\'enfant congolais et accompagner les jeunes vers leur avenir.', '', '', '' ),
		array( 'Social', 'heart', 'vert', 0, 'Être aux côtés des familles et des personnes vulnérables, au plus près des communautés.', '', '', '' ),
		array( 'Entrepreneuriat', 'spark', 'rouge', 0, 'Soutenir l\'autonomisation socio-économique des jeunes et des femmes.', '', '', '' ),
	);
	foreach ( $domaines as $order => $d ) {
		$count['domaines'] += fms_import_post(
			array(
				'post_type'    => 'ms_domaine',
				'post_title'   => $d[0],
				'post_content' => fms_p( $d[4] ),
				'menu_order'   => $order + 1,
				'meta'         => array(
					'_fms_icon'      => $d[1],
					'_fms_color'     => $d[2],
					'_fms_featured'  => $d[3],
					'_fms_items'     => $d[5],
					'_fms_box_label' => $d[6],
					'_fms_box_text'  => $d[7],
				),
			)
		) ? 1 : 0;
	}

	// Projets phares.
	$projets = array(
		array( 'Le stade Dominique Sakombi', 'Un équipement sportif pour la jeunesse : un lieu de rencontre, de compétition et d\'épanouissement.', '', 'ball', 'vert', array( 'sport-jeunesse' => 'Sport & jeunesse' ) ),
		array( 'Le marché central de Lisala', 'Un espace d\'échanges au cœur de la ville, au service des commerçants et de l\'activité économique locale.', 'marcheLisala.jpg', 'market', 'rouge', array( 'economie-locale' => 'Économie locale' ) ),
		array( 'Le pont Kaba', 'Un ouvrage offert par la Fondation pour relier les communautés et faciliter l\'accès aux écoles, aux marchés et aux services.', 'pont-kaba.jpg', 'bridge', 'jaune', array( 'mobilite' => 'Mobilité' ) ),
	);
	foreach ( $projets as $order => $p ) {
		$count['projets'] += fms_import_post(
			array(
				'post_type'    => 'ms_projet',
				'post_title'   => $p[0],
				'post_excerpt' => $p[1],
				'post_content' => fms_p( $p[1] ),
				'menu_order'   => $order + 1,
				'image'        => $p[2],
				'meta'         => array( '_fms_icon' => $p[3], '_fms_color' => $p[4] ),
				'terms'        => array( 'ms_projet_cat' => $p[5] ),
			)
		) ? 1 : 0;
	}

	// Actualités (articles). Dates inconnues : publiées aujourd'hui, à corriger dans chaque article.
	$actu  = array( 'actualites' => __( 'Actualités', 'fondation-ms' ) );
	$une   = array( 'la-une' => __( 'La une', 'fondation-ms' ) );
	$posts = array(
		array( 'Stade Dominique Sakombi : un équipement pour la jeunesse', 'Un équipement sportif dédié à la jeunesse.', '', $actu, '', '' ),
		array( 'Le marché central de Lisala', 'Un espace d\'échanges au service des commerçants et de l\'activité économique de la ville.', 'marcheLisala.jpg', $actu, '', '' ),
		array( 'Le pont Kaba, don de la Fondation', 'Un ouvrage offert par la Fondation pour relier les communautés et faciliter les déplacements.', 'pont-kaba.jpg', $actu, '', '' ),
		array( 'Opération « Pas une école sans banc »', 'Une initiative en faveur des infrastructures scolaires pour que chaque élève étudie dans de bonnes conditions.', '', $actu, '', '' ),
		array( 'Rentrée scolaire 2026-2027 : la Fondation aux côtés des élèves de Lisala', 'La Fondation a renouvelé ses actions de soutien aux élèves et aux écoles de Lisala, perpétuant son engagement éducatif.', '', $actu + $une, 'Rentrée scolaire 2026-2027', 'Radio la Voix de la Mongala' ),
	);
	$time = time() - count( $posts ) * MINUTE_IN_SECONDS;
	foreach ( $posts as $i => $p ) {
		$count['articles'] += fms_import_post(
			array(
				'post_type'    => 'post',
				'post_title'   => $p[0],
				'post_excerpt' => $p[1],
				'post_content' => fms_p( $p[1] ),
				'post_date'    => wp_date( 'Y-m-d H:i:s', $time + $i * MINUTE_IN_SECONDS ),
				'image'        => $p[2],
				'terms'        => array( 'category' => $p[3] ),
				'meta'         => array_filter( array( '_fms_label' => $p[4], '_fms_source' => $p[5] ) ),
			)
		) ? 1 : 0;
	}

	// Galerie photo.
	$photos = array(
		array( 'Molendo Sakombi, fondateur de la Fondation MS', 'mol.jpg' ),
		array( 'Le pont Kaba, don de la Fondation', 'pont-kaba.jpg' ),
		array( 'Le marché central de Lisala', 'marcheLisala.jpg' ),
		array( 'Molendo Sakombi', 'molendo.webp' ),
	);
	foreach ( $photos as $order => $p ) {
		$count['photos'] += fms_import_post(
			array(
				'post_type'  => 'ms_photo',
				'post_title' => $p[0],
				'menu_order' => $order + 1,
				'image'      => $p[1],
			)
		) ? 1 : 0;
	}

	// Photo du fondateur et image de la bannière dans la médiathèque.
	if ( ! get_theme_mod( 'founder_photo' ) ) {
		set_theme_mod( 'founder_photo', fms_import_image( 'molendo.webp', 'Molendo Sakombi' ) );
	}
	if ( ! get_theme_mod( 'hero_image' ) ) {
		set_theme_mod( 'hero_image', fms_import_image( 'molendo.webp', 'Molendo Sakombi' ) );
	}

	// Menus.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menus     = array(
		'primary'  => array(
			__( 'Menu principal', 'fondation-ms' ),
			array(
				'#mission'    => __( 'La fondation', 'fondation-ms' ),
				'#domaines'   => __( 'Domaines', 'fondation-ms' ),
				'#projets'    => __( 'Projets phares', 'fondation-ms' ),
				'#actualites' => __( 'Actualités', 'fondation-ms' ),
				'#radio'      => __( 'Radio', 'fondation-ms' ),
				'#galerie'    => __( 'Galerie', 'fondation-ms' ),
			),
		),
		'footer_1' => array(
			__( 'La fondation', 'fondation-ms' ),
			array(
				'#mission'    => __( 'Qui sommes-nous', 'fondation-ms' ),
				'#domaines'   => __( 'Domaines d\'intervention', 'fondation-ms' ),
				'#projets'    => __( 'Projets phares', 'fondation-ms' ),
				'#actualites' => __( 'Actualités', 'fondation-ms' ),
				'#galerie'    => __( 'Galerie', 'fondation-ms' ),
			),
		),
		'footer_2' => array(
			__( 'Agir', 'fondation-ms' ),
			array(
				'#engager' => __( 'Devenir bénévole', 'fondation-ms' ),
				'#contact' => __( 'Devenir partenaire', 'fondation-ms' ),
				'#radio'   => __( 'Radio Lisala', 'fondation-ms' ),
			),
		),
	);
	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) || wp_get_nav_menu_object( $menu[0] ) ) {
			continue;
		}
		$menu_id = wp_create_nav_menu( $menu[0] );
		if ( is_wp_error( $menu_id ) ) {
			continue;
		}
		foreach ( $menu[1] as $url => $label ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => $label,
					'menu-item-url'    => $url,
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);
		}
		$locations[ $location ] = $menu_id;
		++$count['menus'];
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	update_option( 'fms_imported', time() );
	return $count;
}
