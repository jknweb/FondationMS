<?php
/**
 * Configuration du thème : supports, menus, tailles d'images, scripts et styles.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Déclare les fonctionnalités prises en charge par le thème.
 */
function fms_setup() {
	load_theme_textdomain( 'fondation-ms', FMS_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 84,
			'width'       => 84,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Palette, polices et largeurs de l'éditeur de blocs : voir theme.json.
	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'fms-card', 720, 450, true );
	add_image_size( 'fms-portrait', 600, 800, true );
	add_image_size( 'fms-wide', 1600, 900, true );

	register_nav_menus(
		array(
			'primary'  => __( 'Menu principal (en-tête)', 'fondation-ms' ),
			'footer_1' => __( 'Pied de page — colonne 1', 'fondation-ms' ),
			'footer_2' => __( 'Pied de page — colonne 2', 'fondation-ms' ),
		)
	);
}
add_action( 'after_setup_theme', 'fms_setup' );

/**
 * Catégorie regroupant les motifs du thème (dossier patterns/).
 */
function fms_register_pattern_category() {
	register_block_pattern_category( 'fondation-ms', array( 'label' => __( 'Fondation MS', 'fondation-ms' ) ) );
}
add_action( 'init', 'fms_register_pattern_category' );

/**
 * Largeur du contenu.
 */
function fms_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fms_content_width', 820 );
}
add_action( 'after_setup_theme', 'fms_content_width', 0 );

/**
 * Charge les polices, feuilles de style et scripts.
 */
function fms_enqueue_assets() {
	wp_enqueue_style(
		'fms-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- version gérée par Google Fonts.
	);
	wp_enqueue_style( 'fms-main', FMS_URI . '/assets/css/main.css', array( 'fms-fonts' ), FMS_VERSION );
	wp_enqueue_style( 'fms-style', get_stylesheet_uri(), array( 'fms-main' ), FMS_VERSION );
	wp_add_inline_style( 'fms-main', fms_dynamic_css() );

	wp_enqueue_script( 'fms-main', FMS_URI . '/assets/js/main.js', array(), FMS_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	wp_localize_script(
		'fms-main',
		'FMS',
		array(
			// Décalage horaire du site (en minutes) pour indiquer l'émission en cours à l'heure de la radio.
			'tzOffset'      => (int) round( (float) get_option( 'gmt_offset', 0 ) * 60 ),
			'slideDelay'    => max( 2, absint( fms_mod( 'slideshow_delay' ) ) ) * 1000,
			'i18n'          => array(
				'openMenu'  => __( 'Ouvrir le menu', 'fondation-ms' ),
				'closeMenu' => __( 'Fermer le menu', 'fondation-ms' ),
				'close'     => __( 'Fermer', 'fondation-ms' ),
				'prev'      => __( 'Photo précédente', 'fondation-ms' ),
				'next'      => __( 'Photo suivante', 'fondation-ms' ),
				'onAir'     => __( 'À l\'antenne :', 'fondation-ms' ),
				'offAir'    => __( 'Aucune émission en ce moment', 'fondation-ms' ),
				'live'      => __( 'En cours', 'fondation-ms' ),
				'submenu'   => __( 'Afficher le sous-menu', 'fondation-ms' ),
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fms_enqueue_assets' );

/**
 * Préconnexion aux serveurs de polices.
 *
 * @param array  $urls          Liste d'URL.
 * @param string $relation_type Type de relation.
 * @return array
 */
function fms_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'fms_resource_hints', 10, 2 );

/**
 * Éclaircit ou assombrit une couleur hexadécimale.
 *
 * @param string $hex    Couleur (#rrggbb).
 * @param float  $amount Valeur entre -1 (plus sombre) et 1 (plus clair).
 * @return string
 */
function fms_shade( $hex, $amount ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) ) {
		return '#000000';
	}
	$out = '#';
	foreach ( str_split( $hex, 2 ) as $part ) {
		$c    = hexdec( $part );
		$c    = $amount < 0 ? $c * ( 1 + $amount ) : $c + ( 255 - $c ) * $amount;
		$out .= str_pad( dechex( (int) round( max( 0, min( 255, $c ) ) ) ), 2, '0', STR_PAD_LEFT );
	}
	return $out;
}

/**
 * Variables CSS générées à partir des couleurs choisies dans le Personnalisateur.
 *
 * @return string
 */
function fms_dynamic_css() {
	$primary = sanitize_hex_color( fms_mod( 'color_primary' ) ) ? sanitize_hex_color( fms_mod( 'color_primary' ) ) : '#0b5d2e';
	$accent  = sanitize_hex_color( fms_mod( 'color_accent' ) ) ? sanitize_hex_color( fms_mod( 'color_accent' ) ) : '#d7262e';
	$gold    = sanitize_hex_color( fms_mod( 'color_gold' ) ) ? sanitize_hex_color( fms_mod( 'color_gold' ) ) : '#f9c80e';

	$hero_url = fms_image_url( fms_mod( 'hero_image' ), 'molendo.webp', 'fms-wide' );
	$blur     = min( 20, absint( fms_mod( 'hero_blur' ) ) );

	return sprintf(
		':root{--primary:%1$s;--primary-600:%2$s;--primary-100:%3$s;--accent:%4$s;--accent-600:%5$s;--gold:%6$s;--teal:%7$s;--dark:%8$s;--hero-image:url("%9$s");--hero-blur:%10$dpx;}',
		$primary,
		fms_shade( $primary, 0.18 ),
		fms_shade( $primary, 0.9 ),
		$accent,
		fms_shade( $accent, -0.18 ),
		$gold,
		fms_shade( $primary, 0.35 ),
		fms_shade( $primary, -0.5 ),
		esc_url_raw( $hero_url ),
		$blur
	);
}

/**
 * Icône du site par défaut (si aucune « Icône du site » n'est définie dans Identité du site).
 */
function fms_default_favicon() {
	if ( ! has_site_icon() ) {
		printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( FMS_URI . '/assets/images/favicon.svg' ) );
	}
	printf( '<meta name="theme-color" content="%s">' . "\n", esc_attr( fms_mod( 'color_primary' ) ) );
}
add_action( 'wp_head', 'fms_default_favicon', 5 );

/**
 * Métadonnées de partage (Open Graph) minimales si aucune extension SEO ne s'en charge.
 */
function fms_open_graph() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
		return;
	}
	$title = wp_get_document_title();
	$desc  = is_singular() ? wp_strip_all_tags( get_the_excerpt() ) : get_bloginfo( 'description' );
	if ( is_front_page() || ! $desc ) {
		$desc = fms_mod( 'hero_text' );
	}
	$image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : FMS_URI . '/assets/images/pont-kaba.jpg';
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_trim_words( $desc, 30, '…' ) ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( wp_trim_words( $desc, 30, '…' ) ) );
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );
}
add_action( 'wp_head', 'fms_open_graph', 6 );

/**
 * Classe « no-js » retirée par le script : les animations ne masquent rien sans JavaScript.
 *
 * @param array $classes Classes du body.
 * @return array
 */
function fms_body_classes( $classes ) {
	$classes[] = 'no-js';
	if ( ! is_front_page() ) {
		$classes[] = 'is-inner';
	}
	return $classes;
}
add_filter( 'body_class', 'fms_body_classes' );

/**
 * Longueur des extraits.
 *
 * @return int
 */
function fms_excerpt_length() {
	return 24;
}
add_filter( 'excerpt_length', 'fms_excerpt_length' );

/**
 * Fin des extraits.
 *
 * @return string
 */
function fms_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'fms_excerpt_more' );

/**
 * Ajoute un bouton pour ouvrir les sous-menus sur mobile.
 *
 * @param string   $item_output Sortie HTML de l'élément.
 * @param WP_Post  $item        Élément de menu.
 * @param int      $depth       Profondeur.
 * @param stdClass $args        Arguments.
 * @return string
 */
function fms_submenu_toggle( $item_output, $item, $depth, $args ) {
	if ( isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$item_output .= '<button class="submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Afficher le sous-menu', 'fondation-ms' ) . '">' . fms_icon( 'chevron' ) . '</button>';
	}
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'fms_submenu_toggle', 10, 4 );

/**
 * Les liens d'ancre des menus pointent vers l'accueil depuis les autres pages.
 *
 * @param array $atts Attributs du lien.
 * @return array
 */
function fms_menu_anchor_links( $atts ) {
	if ( ! empty( $atts['href'] ) ) {
		$atts['href'] = fms_link( $atts['href'] );
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'fms_menu_anchor_links' );

/**
 * Titres d'archives sans préfixe (« Catégorie : », « Archives : »…).
 *
 * @return string
 */
function fms_archive_title_prefix() {
	return '';
}
add_filter( 'get_the_archive_title_prefix', 'fms_archive_title_prefix' );
