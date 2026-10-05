<?php
/**
 * Réglages du thème dans Apparence › Personnaliser › « Fondation MS ».
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Nettoie une case à cocher.
 *
 * @param mixed $value Valeur.
 * @return bool
 */
function fms_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Nettoie un lien : URL complète, ancre (#section) ou lien e-mail/téléphone.
 *
 * @param string $value Valeur.
 * @return string
 */
function fms_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( 0 === strpos( $value, '#' ) ) {
		return '#' . sanitize_html_class( substr( $value, 1 ) );
	}
	return esc_url_raw( $value, array( 'http', 'https', 'mailto', 'tel' ) );
}

/**
 * Nettoie un choix de liste selon les options du contrôle.
 *
 * @param string               $value   Valeur.
 * @param WP_Customize_Setting $setting Réglage.
 * @return string
 */
function fms_sanitize_choice( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	$choices = $control ? $control->choices : array();
	return array_key_exists( $value, $choices ) ? $value : $setting->default;
}

/**
 * Nettoie un nombre (chiffres clés) : chiffres, espaces, virgule, point.
 *
 * @param string $value Valeur.
 * @return string
 */
function fms_sanitize_number_text( $value ) {
	return preg_replace( '/[^0-9]/', '', (string) $value );
}

/**
 * Enregistre les panneaux, sections et réglages.
 *
 * @param WP_Customize_Manager $wp_customize Gestionnaire du Personnalisateur.
 */
function fms_customize_register( $wp_customize ) {
	$d = fms_defaults();

	/**
	 * Ajoute un réglage et son contrôle en une ligne.
	 *
	 * @param string $id      Identifiant (theme_mod).
	 * @param string $section Section.
	 * @param string $label   Libellé.
	 * @param string $type    text|textarea|url|link|checkbox|select|number|email|color|image|html.
	 * @param array  $extra   Options supplémentaires (description, choices, input_attrs).
	 */
	$add = function ( $id, $section, $label, $type = 'text', $extra = array() ) use ( $wp_customize, $d ) {
		$sanitizers = array(
			'text'     => 'sanitize_text_field',
			'textarea' => 'sanitize_textarea_field',
			'html'     => 'wp_kses_post',
			'url'      => 'esc_url_raw',
			'link'     => 'fms_sanitize_link',
			'email'    => 'sanitize_email',
			'checkbox' => 'fms_sanitize_checkbox',
			'select'   => 'fms_sanitize_choice',
			'number'   => 'absint',
			'stat'     => 'fms_sanitize_number_text',
			'color'    => 'sanitize_hex_color',
			'image'    => 'absint',
		);
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $d[ $id ] ) ? $d[ $id ] : '',
				'sanitize_callback' => $sanitizers[ $type ],
				'transport'         => 'refresh',
			)
		);
		$args = array_merge(
			array(
				'label'   => $label,
				'section' => $section,
			),
			$extra
		);
		if ( 'color' === $type ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, $args ) );
		} elseif ( 'image' === $type ) {
			$args['mime_type'] = 'image';
			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $id, $args ) );
		} else {
			$map          = array( 'html' => 'textarea', 'link' => 'text', 'stat' => 'text' );
			$args['type'] = isset( $map[ $type ] ) ? $map[ $type ] : $type;
			$wp_customize->add_control( $id, $args );
		}
	};

	$link_help = __( 'Adresse complète (https://…), ancre vers une section de l\'accueil (#projets, #actualites, #radio, #galerie, #contact…) ou mailto:adresse@exemple.org.', 'fondation-ms' );

	$wp_customize->add_panel(
		'fms_panel',
		array(
			'title'       => __( 'Fondation MS — Contenu du site', 'fondation-ms' ),
			'description' => __( 'Modifiez ici les textes, images, boutons et réglages de la page d\'accueil. Les actualités, projets, domaines, photos et émissions de radio se gèrent dans les menus correspondants du tableau de bord.', 'fondation-ms' ),
			'priority'    => 20,
		)
	);
	$section = function ( $id, $title, $description = '' ) use ( $wp_customize ) {
		$wp_customize->add_section(
			'fms_' . $id,
			array(
				'title'       => $title,
				'description' => $description,
				'panel'       => 'fms_panel',
			)
		);
	};

	// Couleurs et en-tête.
	$section( 'identite', __( 'Couleurs et bouton d\'en-tête', 'fondation-ms' ), __( 'Le logo, le nom et l\'icône du site se règlent dans « Identité du site ».', 'fondation-ms' ) );
	$add( 'color_primary', 'fms_identite', __( 'Couleur principale (vert)', 'fondation-ms' ), 'color' );
	$add( 'color_accent', 'fms_identite', __( 'Couleur des boutons (rouge)', 'fondation-ms' ), 'color' );
	$add( 'color_gold', 'fms_identite', __( 'Couleur de mise en valeur (jaune)', 'fondation-ms' ), 'color' );
	$add( 'header_cta_label', 'fms_identite', __( 'Bouton de l\'en-tête — texte', 'fondation-ms' ), 'text', array( 'description' => __( 'Laisser vide pour masquer le bouton.', 'fondation-ms' ) ) );
	$add( 'header_cta_url', 'fms_identite', __( 'Bouton de l\'en-tête — lien', 'fondation-ms' ), 'link', array( 'description' => $link_help ) );

	// Coordonnées.
	$section( 'contact', __( 'Coordonnées et réseaux sociaux', 'fondation-ms' ), __( 'Affichées dans le pied de page. Laissez un champ vide pour le masquer.', 'fondation-ms' ) );
	$add( 'contact_email', 'fms_contact', __( 'Adresse e-mail', 'fondation-ms' ), 'email' );
	$add( 'contact_phone', 'fms_contact', __( 'Téléphone', 'fondation-ms' ), 'text' );
	$add( 'contact_addresses', 'fms_contact', __( 'Adresses / implantations', 'fondation-ms' ), 'textarea', array( 'description' => __( 'Une adresse par ligne.', 'fondation-ms' ) ) );
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'x' => 'X (Twitter)', 'whatsapp' => 'WhatsApp', 'tiktok' => 'TikTok' ) as $key => $label ) {
		/* translators: %s: réseau social. */
		$add( 'social_' . $key, 'fms_contact', sprintf( __( 'Lien %s', 'fondation-ms' ), $label ), 'url' );
	}

	// Bannière.
	$section( 'hero', __( 'Bannière d\'accueil', 'fondation-ms' ), __( 'L\'encadré blanc affiche automatiquement le dernier article de la catégorie « La une ».', 'fondation-ms' ) );
	$add( 'hero_eyebrow', 'fms_hero', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'hero_title_before', 'fms_hero', __( 'Titre — début', 'fondation-ms' ) );
	$add( 'hero_title_highlight', 'fms_hero', __( 'Titre — mots en couleur', 'fondation-ms' ), 'text', array( 'description' => __( 'Affichés en jaune et en italique.', 'fondation-ms' ) ) );
	$add( 'hero_title_after', 'fms_hero', __( 'Titre — fin', 'fondation-ms' ) );
	$add( 'hero_text', 'fms_hero', __( 'Texte', 'fondation-ms' ), 'textarea' );
	$add( 'hero_image', 'fms_hero', __( 'Image de fond', 'fondation-ms' ), 'image', array( 'description' => __( 'Par défaut : portrait du fondateur. Grande image conseillée (1600 px de large).', 'fondation-ms' ) ) );
	$add( 'hero_blur', 'fms_hero', __( 'Flou de l\'image (0 à 20)', 'fondation-ms' ), 'number', array( 'input_attrs' => array( 'min' => 0, 'max' => 20 ) ) );
	$add( 'hero_btn1_label', 'fms_hero', __( 'Bouton 1 — texte', 'fondation-ms' ) );
	$add( 'hero_btn1_url', 'fms_hero', __( 'Bouton 1 — lien', 'fondation-ms' ), 'link', array( 'description' => $link_help ) );
	$add( 'hero_btn2_label', 'fms_hero', __( 'Bouton 2 — texte', 'fondation-ms' ) );
	$add( 'hero_btn2_url', 'fms_hero', __( 'Bouton 2 — lien', 'fondation-ms' ), 'link' );
	$add( 'hero_points', 'fms_hero', __( 'Points clés sous les boutons', 'fondation-ms' ), 'textarea', array( 'description' => __( 'Un point par ligne.', 'fondation-ms' ) ) );
	$add( 'hero_card_title', 'fms_hero', __( 'Encadré « La une » — titre', 'fondation-ms' ) );
	$add( 'hero_card_link', 'fms_hero', __( 'Encadré « La une » — texte du lien', 'fondation-ms' ) );

	// Chiffres.
	$section( 'chiffres', __( 'Chiffres clés', 'fondation-ms' ), __( 'Cinq emplacements. Laissez le nombre vide pour masquer un chiffre.', 'fondation-ms' ) );
	for ( $i = 1; $i <= 5; $i++ ) {
		/* translators: %d: numéro du chiffre. */
		$add( "stat{$i}_number", 'fms_chiffres', sprintf( __( 'Chiffre %d — nombre', 'fondation-ms' ), $i ), 'stat', array( 'description' => __( 'Chiffres uniquement, ex. 5000.', 'fondation-ms' ) ) );
		$add( "stat{$i}_prefix", 'fms_chiffres', sprintf( __( 'Chiffre %d — signe devant (ex. +)', 'fondation-ms' ), $i ) );
		$add( "stat{$i}_label", 'fms_chiffres', sprintf( __( 'Chiffre %d — libellé', 'fondation-ms' ), $i ) );
		$add( "stat{$i}_color", 'fms_chiffres', sprintf( __( 'Chiffre %d — couleur', 'fondation-ms' ), $i ), 'select', array( 'choices' => fms_color_choices() ) );
	}

	// La fondation.
	$section( 'fondation', __( 'La fondation et mot du fondateur', 'fondation-ms' ) );
	$add( 'about_eyebrow', 'fms_fondation', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'about_title', 'fms_fondation', __( 'Titre', 'fondation-ms' ) );
	$add( 'about_text', 'fms_fondation', __( 'Présentation', 'fondation-ms' ), 'textarea' );
	$add( 'about_mission', 'fms_fondation', __( 'Mission (facultatif)', 'fondation-ms' ), 'textarea', array( 'description' => __( 'Mission, vision et historique s\'affichent sous forme d\'onglets s\'ils sont remplis.', 'fondation-ms' ) ) );
	$add( 'about_vision', 'fms_fondation', __( 'Vision (facultatif)', 'fondation-ms' ), 'textarea' );
	$add( 'about_history', 'fms_fondation', __( 'Historique (facultatif)', 'fondation-ms' ), 'textarea' );
	for ( $i = 1; $i <= 3; $i++ ) {
		/* translators: %d: numéro de la valeur. */
		$add( "value{$i}_title", 'fms_fondation', sprintf( __( 'Valeur %d — titre', 'fondation-ms' ), $i ), 'text', array( 'description' => 1 === $i ? __( 'Laissez le titre vide pour masquer une valeur.', 'fondation-ms' ) : '' ) );
		$add( "value{$i}_text", 'fms_fondation', sprintf( __( 'Valeur %d — texte', 'fondation-ms' ), $i ) );
		$add( "value{$i}_icon", 'fms_fondation', sprintf( __( 'Valeur %d — icône', 'fondation-ms' ), $i ), 'select', array( 'choices' => fms_icon_choices() ) );
	}
	$add( 'founder_label', 'fms_fondation', __( 'Mot du fondateur — titre de l\'encadré', 'fondation-ms' ) );
	$add( 'founder_photo', 'fms_fondation', __( 'Mot du fondateur — photo', 'fondation-ms' ), 'image', array( 'description' => __( 'Format portrait conseillé.', 'fondation-ms' ) ) );
	$add( 'founder_message', 'fms_fondation', __( 'Mot du fondateur — message', 'fondation-ms' ), 'textarea', array( 'description' => __( 'Laissez une ligne vide pour créer un nouveau paragraphe.', 'fondation-ms' ) ) );
	$add( 'founder_name', 'fms_fondation', __( 'Mot du fondateur — nom', 'fondation-ms' ) );
	$add( 'founder_role', 'fms_fondation', __( 'Mot du fondateur — fonction', 'fondation-ms' ) );
	$add( 'founder_link', 'fms_fondation', __( 'Lien « Lire le message complet » (facultatif)', 'fondation-ms' ), 'link', array( 'description' => __( 'Ex. lien vers une page contenant le message intégral.', 'fondation-ms' ) ) );

	// Titres des sections à contenu dynamique.
	$section( 'domaines', __( 'Domaines d\'intervention', 'fondation-ms' ), __( 'Les cartes se gèrent dans le menu « Domaines d\'intervention » du tableau de bord.', 'fondation-ms' ) );
	$add( 'domaines_eyebrow', 'fms_domaines', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'domaines_title', 'fms_domaines', __( 'Titre', 'fondation-ms' ) );
	$add( 'domaines_intro', 'fms_domaines', __( 'Introduction', 'fondation-ms' ), 'textarea' );

	$section( 'projets', __( 'Projets phares', 'fondation-ms' ), __( 'Les projets se gèrent dans le menu « Projets phares » du tableau de bord.', 'fondation-ms' ) );
	$add( 'projets_eyebrow', 'fms_projets', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'projets_title', 'fms_projets', __( 'Titre', 'fondation-ms' ) );
	$add( 'projets_intro', 'fms_projets', __( 'Introduction', 'fondation-ms' ), 'textarea' );
	$add( 'projets_count', 'fms_projets', __( 'Nombre de projets affichés', 'fondation-ms' ), 'number', array( 'input_attrs' => array( 'min' => 1, 'max' => 12 ) ) );

	$section( 'actualites', __( 'Fil d\'actualité', 'fondation-ms' ), __( 'Affiche les derniers articles de la catégorie « Actualités » (Articles › Ajouter).', 'fondation-ms' ) );
	$add( 'news_eyebrow', 'fms_actualites', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'news_title', 'fms_actualites', __( 'Titre', 'fondation-ms' ) );
	$add( 'news_count', 'fms_actualites', __( 'Nombre d\'articles affichés', 'fondation-ms' ), 'number', array( 'description' => __( '12 = 3 lignes de 4.', 'fondation-ms' ), 'input_attrs' => array( 'min' => 4, 'max' => 24, 'step' => 4 ) ) );
	$add( 'news_all_label', 'fms_actualites', __( 'Texte du lien vers toutes les actualités', 'fondation-ms' ) );

	$section( 'radio', __( 'Galerie et radio', 'fondation-ms' ), __( 'Photos : menu « Galerie photo ». Programme : menu « Programme radio ».', 'fondation-ms' ) );
	$add( 'gallery_eyebrow', 'fms_radio', __( 'Galerie — sur-titre', 'fondation-ms' ) );
	$add( 'gallery_title', 'fms_radio', __( 'Galerie — titre', 'fondation-ms' ) );
	$add( 'radio_logo', 'fms_radio', __( 'Radio — logo', 'fondation-ms' ), 'image', array( 'description' => __( 'Remplace l\'icône, le sur-titre et le nom de la radio. Le nom reste utilisé comme texte alternatif. Format paysage conseillé (PNG à fond transparent ou JPG).', 'fondation-ms' ) ) );
	$add( 'radio_kicker', 'fms_radio', __( 'Radio — sur-titre (si pas de logo)', 'fondation-ms' ) );
	$add( 'radio_name', 'fms_radio', __( 'Radio — nom', 'fondation-ms' ) );
	$add( 'radio_url', 'fms_radio', __( 'Radio — adresse du site', 'fondation-ms' ), 'url', array( 'description' => __( 'Le bouton est masqué tant que ce champ est vide.', 'fondation-ms' ) ) );
	$add( 'radio_button', 'fms_radio', __( 'Radio — texte du bouton', 'fondation-ms' ) );
	$add( 'radio_note', 'fms_radio', __( 'Radio — note sous le programme (facultatif)', 'fondation-ms' ) );
	$add( 'radio_empty', 'fms_radio', __( 'Radio — message si aucun programme', 'fondation-ms' ) );

	$section( 'engager', __( 'S\'engager', 'fondation-ms' ) );
	$add( 'engage_eyebrow', 'fms_engager', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'engage_title', 'fms_engager', __( 'Titre', 'fondation-ms' ) );
	for ( $i = 1; $i <= 2; $i++ ) {
		/* translators: %d: numéro de la carte. */
		$add( "engage{$i}_title", 'fms_engager', sprintf( __( 'Carte %d — titre', 'fondation-ms' ), $i ) );
		$add( "engage{$i}_text", 'fms_engager', sprintf( __( 'Carte %d — texte', 'fondation-ms' ), $i ), 'textarea' );
		$add( "engage{$i}_icon", 'fms_engager', sprintf( __( 'Carte %d — icône', 'fondation-ms' ), $i ), 'select', array( 'choices' => fms_icon_choices() ) );
		$add( "engage{$i}_label", 'fms_engager', sprintf( __( 'Carte %d — texte du bouton', 'fondation-ms' ), $i ) );
		$add( "engage{$i}_url", 'fms_engager', sprintf( __( 'Carte %d — lien du bouton', 'fondation-ms' ), $i ), 'link', array( 'description' => $link_help ) );
	}

	$section( 'diaporama', __( 'Diaporama photo', 'fondation-ms' ), __( 'Les photos viennent du menu « Galerie photo » et défilent dans un ordre aléatoire.', 'fondation-ms' ) );
	$add( 'slideshow_eyebrow', 'fms_diaporama', __( 'Sur-titre', 'fondation-ms' ) );
	$add( 'slideshow_title', 'fms_diaporama', __( 'Titre', 'fondation-ms' ) );
	$add( 'slideshow_delay', 'fms_diaporama', __( 'Durée d\'affichage de chaque photo (secondes)', 'fondation-ms' ), 'number', array( 'input_attrs' => array( 'min' => 2, 'max' => 30 ) ) );

	$section( 'footer', __( 'Pied de page', 'fondation-ms' ), __( 'Les liens des colonnes se gèrent dans Apparence › Menus (emplacements « Pied de page »).', 'fondation-ms' ) );
	$add( 'footer_text', 'fms_footer', __( 'Texte de présentation', 'fondation-ms' ), 'textarea' );
	$add( 'footer_copyright', 'fms_footer', __( 'Mention de copyright', 'fondation-ms' ), 'text', array( 'description' => __( 'L\'année est ajoutée automatiquement.', 'fondation-ms' ) ) );

	// Ordre et affichage des sections.
	$section( 'sections', __( 'Ordre et affichage des sections', 'fondation-ms' ), __( 'Décochez une section pour la masquer. Les sections s\'affichent de la position la plus petite à la plus grande.', 'fondation-ms' ) );
	foreach ( fms_sections() as $id => $label ) {
		/* translators: %s: nom de la section. */
		$add( 'section_' . $id . '_show', 'fms_sections', sprintf( __( 'Afficher : %s', 'fondation-ms' ), $label ), 'checkbox' );
		/* translators: %s: nom de la section. */
		$add( 'section_' . $id . '_order', 'fms_sections', sprintf( __( 'Position : %s', 'fondation-ms' ), $label ), 'number', array( 'input_attrs' => array( 'min' => 1, 'max' => 20 ) ) );
	}
}
add_action( 'customize_register', 'fms_customize_register' );

/**
 * Sections de l'accueil dans l'ordre choisi, sans les sections masquées.
 *
 * @return string[]
 */
function fms_ordered_sections() {
	$list = array();
	foreach ( array_keys( fms_sections() ) as $index => $id ) {
		if ( fms_mod( 'section_' . $id . '_show' ) ) {
			$list[ $id ] = array( absint( fms_mod( 'section_' . $id . '_order' ) ), $index );
		}
	}
	uasort(
		$list,
		function ( $a, $b ) {
			return $a[0] === $b[0] ? $a[1] - $b[1] : $a[0] - $b[0];
		}
	);
	return array_keys( $list );
}
