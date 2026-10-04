<?php
/**
 * Fonctions utilitaires d'affichage.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lit un réglage du thème avec sa valeur par défaut.
 *
 * @param string $key Nom du réglage.
 * @return mixed
 */
function fms_mod( $key ) {
	$defaults = fms_defaults();
	return get_theme_mod( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
}

/**
 * Icônes disponibles (clé => libellé), utilisées dans les listes déroulantes de l'administration.
 *
 * @return array<string,string>
 */
function fms_icon_choices() {
	return array(
		'book'     => __( 'Livre (éducation)', 'fondation-ms' ),
		'health'   => __( 'Croix (santé)', 'fondation-ms' ),
		'ball'     => __( 'Ballon (jeunesse, sport)', 'fondation-ms' ),
		'heart'    => __( 'Cœur (social)', 'fondation-ms' ),
		'spark'    => __( 'Courbe (entrepreneuriat)', 'fondation-ms' ),
		'hands'    => __( 'Personnes (communauté)', 'fondation-ms' ),
		'building' => __( 'Bâtiment', 'fondation-ms' ),
		'market'   => __( 'Marché', 'fondation-ms' ),
		'bridge'   => __( 'Pont', 'fondation-ms' ),
		'shield'   => __( 'Bouclier', 'fondation-ms' ),
		'leaf'     => __( 'Feuille (durabilité)', 'fondation-ms' ),
		'eye'      => __( 'Œil (transparence)', 'fondation-ms' ),
		'calendar' => __( 'Calendrier', 'fondation-ms' ),
		'radio'    => __( 'Radio', 'fondation-ms' ),
		'pin'      => __( 'Lieu', 'fondation-ms' ),
	);
}

/**
 * Couleurs d'accent disponibles pour les cartes.
 *
 * @return array<string,string>
 */
function fms_color_choices() {
	return array(
		'vert'  => __( 'Vert', 'fondation-ms' ),
		'rouge' => __( 'Rouge', 'fondation-ms' ),
		'jaune' => __( 'Jaune', 'fondation-ms' ),
	);
}

/**
 * Renvoie le code HTML d'une icône du sprite SVG.
 *
 * @param string $name  Nom de l'icône (sans le préfixe « i- »).
 * @param string $class Classe CSS supplémentaire.
 * @return string
 */
function fms_icon( $name, $class = '' ) {
	return sprintf(
		'<svg class="icon %1$s" aria-hidden="true" focusable="false"><use href="#i-%2$s"></use></svg>',
		esc_attr( $class ),
		esc_attr( sanitize_key( $name ) )
	);
}

/**
 * Affiche une icône (sortie déjà échappée).
 *
 * @param string $name  Nom de l'icône.
 * @param string $class Classe CSS supplémentaire.
 */
function fms_the_icon( $name, $class = '' ) {
	echo fms_icon( $name, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- échappé dans fms_icon().
}

/**
 * Transforme un lien d'ancre (« #projets ») en lien absolu vers la page d'accueil
 * lorsqu'on n'est pas sur l'accueil, pour que les menus fonctionnent partout.
 *
 * @param string $url Adresse saisie.
 * @return string
 */
function fms_link( $url ) {
	$url = trim( (string) $url );
	if ( '' === $url ) {
		return '';
	}
	if ( 0 === strpos( $url, '#' ) && ! is_front_page() ) {
		return home_url( '/' ) . $url;
	}
	return $url;
}

/**
 * Découpe un texte en lignes non vides.
 *
 * @param string $text Texte multiligne.
 * @return string[]
 */
function fms_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) ) );
}

/**
 * URL d'une image : pièce jointe de la médiathèque si définie, sinon image fournie avec le thème.
 *
 * @param int    $attachment_id Identifiant de la pièce jointe.
 * @param string $fallback      Fichier dans assets/images.
 * @param string $size          Taille d'image.
 * @return string
 */
function fms_image_url( $attachment_id, $fallback = '', $size = 'full' ) {
	if ( $attachment_id ) {
		$src = wp_get_attachment_image_url( (int) $attachment_id, $size );
		if ( $src ) {
			return $src;
		}
	}
	return $fallback ? FMS_URI . '/assets/images/' . $fallback : '';
}

/**
 * Libellé de date d'une actualité : étiquette personnalisée si renseignée, sinon date de publication.
 *
 * @param int|WP_Post|null $post Article.
 * @return string
 */
function fms_post_label( $post = null ) {
	$post  = get_post( $post );
	$label = get_post_meta( $post->ID, '_fms_label', true );
	return $label ? $label : get_the_date( '', $post );
}

/**
 * Liste des réseaux sociaux renseignés (clé => [libellé, url]).
 *
 * @return array<string,array{0:string,1:string}>
 */
function fms_socials() {
	$networks = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'linkedin'  => 'LinkedIn',
		'x'         => 'X',
		'whatsapp'  => 'WhatsApp',
		'tiktok'    => 'TikTok',
	);
	$out = array();
	foreach ( $networks as $key => $label ) {
		$url = fms_mod( 'social_' . $key );
		if ( $url ) {
			$out[ $key ] = array( $label, $url );
		}
	}
	return $out;
}

/**
 * Menu de secours (liens vers les sections de l'accueil) quand aucun menu n'est assigné.
 *
 * @param array $args Arguments de wp_nav_menu().
 */
function fms_fallback_menu( $args = array() ) {
	$items = array(
		'#mission'    => __( 'La fondation', 'fondation-ms' ),
		'#domaines'   => __( 'Domaines', 'fondation-ms' ),
		'#projets'    => __( 'Projets phares', 'fondation-ms' ),
		'#actualites' => __( 'Actualités', 'fondation-ms' ),
		'#radio'      => __( 'Radio', 'fondation-ms' ),
		'#galerie'    => __( 'Galerie', 'fondation-ms' ),
	);
	$class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'nav__list';
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $items as $url => $label ) {
		printf( '<li class="menu-item"><a href="%1$s">%2$s</a></li>', esc_url( fms_link( $url ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * En-tête de section (sur-titre, titre, introduction).
 *
 * @param string $eyebrow Sur-titre.
 * @param string $title   Titre.
 * @param string $intro   Introduction.
 * @param bool   $light   Version claire (sur fond sombre).
 */
function fms_section_head( $eyebrow, $title, $intro = '', $light = false ) {
	if ( ! $eyebrow && ! $title && ! $intro ) {
		return;
	}
	?>
	<header class="section__head<?php echo $light ? ' section__head--light' : ''; ?> reveal">
		<?php if ( $eyebrow ) : ?>
			<p class="eyebrow<?php echo $light ? ' eyebrow--light' : ''; ?>"><?php echo esc_html( $eyebrow ); ?></p>
		<?php endif; ?>
		<?php if ( $title ) : ?>
			<h2 class="section__title"><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>
		<?php if ( $intro ) : ?>
			<p class="section__intro<?php echo $light ? ' section__intro--light' : ''; ?>"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Lien « Modifier » visible par les administrateurs, vers le réglage de la section dans le Personnalisateur.
 *
 * @param string $section Identifiant de la section du Personnalisateur.
 */
function fms_edit_link( $section ) {
	if ( ! current_user_can( 'edit_theme_options' ) || is_customize_preview() ) {
		return;
	}
	$url = add_query_arg(
		array(
			'autofocus[section]' => 'fms_' . $section,
			'url'                => rawurlencode( home_url( '/' ) ),
		),
		admin_url( 'customize.php' )
	);
	printf( '<a class="fms-edit" href="%1$s">%2$s</a>', esc_url( $url ), esc_html__( 'Modifier cette section', 'fondation-ms' ) );
}

/**
 * Balises autorisées pour du HTML contenant des icônes SVG du thème.
 *
 * @return array
 */
function fms_kses_allowed() {
	$allowed        = wp_kses_allowed_html( 'post' );
	$allowed['svg'] = array(
		'class'       => true,
		'aria-hidden' => true,
		'focusable'   => true,
	);
	$allowed['use'] = array( 'href' => true );
	return $allowed;
}
