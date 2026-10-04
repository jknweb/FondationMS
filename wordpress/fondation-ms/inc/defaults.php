<?php
/**
 * Valeurs par défaut des réglages du thème.
 *
 * Elles reprennent le contenu réel du site HTML d'origine : le site s'affiche
 * correctement dès l'activation, puis tout se modifie dans Apparence › Personnaliser.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Liste des sections de la page d'accueil (identifiant => libellé).
 *
 * @return array<string,string>
 */
function fms_sections() {
	return array(
		'hero'        => __( 'Bannière d\'accueil', 'fondation-ms' ),
		'chiffres'    => __( 'Chiffres clés', 'fondation-ms' ),
		'fondation'   => __( 'La fondation et mot du fondateur', 'fondation-ms' ),
		'domaines'    => __( 'Domaines d\'intervention', 'fondation-ms' ),
		'projets'     => __( 'Projets phares', 'fondation-ms' ),
		'actualites'  => __( 'Fil d\'actualité', 'fondation-ms' ),
		'radio'       => __( 'Galerie et radio', 'fondation-ms' ),
		'engager'     => __( 'S\'engager', 'fondation-ms' ),
		'diaporama'   => __( 'Diaporama photo', 'fondation-ms' ),
	);
}

/**
 * Valeurs par défaut de tous les réglages (theme_mod).
 *
 * @return array<string,mixed>
 */
function fms_defaults() {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$defaults = array(
		// Couleurs.
		'color_primary'          => '#0b5d2e',
		'color_accent'           => '#d7262e',
		'color_gold'             => '#f9c80e',

		// Coordonnées.
		'contact_email'          => '',
		'contact_phone'          => '',
		'contact_addresses'      => "Lisala, Mongala — RDC\nKinshasa — RDC",
		'social_facebook'        => '',
		'social_instagram'       => '',
		'social_youtube'         => '',
		'social_linkedin'        => '',
		'social_x'               => '',
		'social_whatsapp'        => '',
		'social_tiktok'          => '',

		// En-tête.
		'header_cta_label'       => __( 'Nous contacter', 'fondation-ms' ),
		'header_cta_url'         => '#contact',

		// Bannière.
		'hero_eyebrow'           => __( 'Lisala · Kinshasa — République Démocratique du Congo', 'fondation-ms' ),
		'hero_title_before'      => __( 'Chaque enfant congolais mérite', 'fondation-ms' ),
		'hero_title_highlight'   => __( 'une chance', 'fondation-ms' ),
		'hero_title_after'       => __( 'de construire son avenir.', 'fondation-ms' ),
		'hero_text'              => __( 'La Fondation MS agit pour l\'éducation, la santé, la jeunesse, le social et l\'entrepreneuriat, en bâtissant aussi les infrastructures qui font vivre les communautés.', 'fondation-ms' ),
		'hero_image'             => 0,
		'hero_blur'              => 5,
		'hero_btn1_label'        => __( 'Nos projets phares', 'fondation-ms' ),
		'hero_btn1_url'          => '#projets',
		'hero_btn2_label'        => __( 'Actualités', 'fondation-ms' ),
		'hero_btn2_url'          => '#actualites',
		'hero_points'            => "Éducation\nSanté\nJeunesse\nSocial\nEntrepreneuriat",
		'hero_card_title'        => __( 'À la une', 'fondation-ms' ),
		'hero_card_link'         => __( 'Voir toutes les actualités', 'fondation-ms' ),

		// Chiffres clés (5 emplacements).
		'stat1_prefix' => '+', 'stat1_number' => '5000', 'stat1_label' => __( 'membres bénéficiaires', 'fondation-ms' ), 'stat1_color' => 'vert',
		'stat2_prefix' => '+', 'stat2_number' => '2000', 'stat2_label' => __( 'familles accompagnées', 'fondation-ms' ), 'stat2_color' => 'rouge',
		'stat3_prefix' => '+', 'stat3_number' => '50',   'stat3_label' => __( 'actions réalisées', 'fondation-ms' ),     'stat3_color' => 'jaune',
		'stat4_prefix' => '+', 'stat4_number' => '5',    'stat4_label' => __( 'projets communautaires', 'fondation-ms' ), 'stat4_color' => 'vert',
		'stat5_prefix' => '',  'stat5_number' => '2',    'stat5_label' => __( 'zones d\'intervention', 'fondation-ms' ),  'stat5_color' => 'rouge',

		// La fondation.
		'about_eyebrow'          => __( 'La fondation', 'fondation-ms' ),
		'about_title'            => __( 'Agir là où l\'avenir se construit : à l\'école et dans la communauté.', 'fondation-ms' ),
		'about_text'             => __( 'Installée à Lisala, dans la province de la Mongala, et à Kinshasa, la Fondation MS intervient dans l\'éducation, la santé, la jeunesse, le social et l\'entrepreneuriat. Elle accompagne les élèves et les écoles, soutient les jeunes et les femmes dans leur indépendance économique, et porte des projets d\'infrastructures utiles à toute la population.', 'fondation-ms' ),
		'about_mission'          => '',
		'about_vision'           => '',
		'about_history'          => '',
		'value1_icon' => 'heart',  'value1_title' => __( 'L\'enfant au centre', 'fondation-ms' ), 'value1_text' => __( 'Son éducation et son épanouissement guident nos actions.', 'fondation-ms' ),
		'value2_icon' => 'hands',  'value2_title' => __( 'Proximité', 'fondation-ms' ),          'value2_text' => __( 'Une présence sur le terrain, à Lisala comme à Kinshasa.', 'fondation-ms' ),
		'value3_icon' => 'leaf',   'value3_title' => __( 'Durabilité', 'fondation-ms' ),         'value3_text' => __( 'Des réalisations concrètes qui servent les communautés dans la durée.', 'fondation-ms' ),
		'founder_label'          => __( 'Le mot du fondateur', 'fondation-ms' ),
		'founder_photo'          => 0,
		'founder_message'        => __( 'Le mot du fondateur sera publié ici.', 'fondation-ms' ),
		'founder_name'           => 'Molendo Sakombi',
		'founder_role'           => __( 'Fondateur', 'fondation-ms' ),
		'founder_link'           => '',

		// Domaines.
		'domaines_eyebrow'       => __( 'Domaines d\'intervention', 'fondation-ms' ),
		'domaines_title'         => __( 'Cinq domaines pour changer durablement les vies', 'fondation-ms' ),
		'domaines_intro'         => '',

		// Projets.
		'projets_eyebrow'        => __( 'Projets phares', 'fondation-ms' ),
		'projets_title'          => __( 'Des réalisations au service de toute la communauté', 'fondation-ms' ),
		'projets_intro'          => __( 'Sport, économie locale, mobilité : trois projets emblématiques de l\'engagement de la Fondation MS.', 'fondation-ms' ),
		'projets_count'          => 3,

		// Actualités.
		'news_eyebrow'           => __( 'Fil d\'actualité', 'fondation-ms' ),
		'news_title'             => __( 'Les dernières nouvelles de la fondation', 'fondation-ms' ),
		'news_count'             => 12,
		'news_all_label'         => __( 'Toutes les actualités', 'fondation-ms' ),

		// Galerie + radio.
		'gallery_eyebrow'        => __( 'Galerie', 'fondation-ms' ),
		'gallery_title'          => __( 'En images', 'fondation-ms' ),
		'radio_kicker'           => __( 'Partenaire média', 'fondation-ms' ),
		'radio_name'             => 'Radio Lisala',
		'radio_url'              => '',
		'radio_button'           => __( 'Visiter le site de la radio', 'fondation-ms' ),
		'radio_note'             => '',
		'radio_empty'            => __( 'Le programme de la radio sera publié ici prochainement.', 'fondation-ms' ),

		// S'engager.
		'engage_eyebrow'         => __( 'S\'engager', 'fondation-ms' ),
		'engage_title'           => __( 'Rejoignez l\'action de la Fondation MS', 'fondation-ms' ),
		'engage1_icon' => 'hands',    'engage1_title' => __( 'Devenir bénévole', 'fondation-ms' ),   'engage1_text' => __( 'Mettez votre temps et vos compétences au service des élèves, des écoles et des communautés, à Lisala ou à Kinshasa.', 'fondation-ms' ), 'engage1_label' => __( 'Je deviens bénévole', 'fondation-ms' ), 'engage1_url' => '#contact',
		'engage2_icon' => 'building', 'engage2_title' => __( 'Devenir partenaire', 'fondation-ms' ), 'engage2_text' => __( 'Institutions, entreprises, associations : construisons ensemble des projets utiles aux communautés.', 'fondation-ms' ), 'engage2_label' => __( 'Proposer un partenariat', 'fondation-ms' ), 'engage2_url' => '#contact',

		// Diaporama.
		'slideshow_eyebrow'      => __( 'Galerie', 'fondation-ms' ),
		'slideshow_title'        => __( 'La fondation sur le terrain', 'fondation-ms' ),
		'slideshow_delay'        => 5,

		// Pied de page.
		'footer_text'            => __( 'Éducation, santé, jeunesse, social et entrepreneuriat pour l\'avenir de l\'enfant congolais.', 'fondation-ms' ),
		'footer_copyright'       => __( 'Fondation MS. Tous droits réservés.', 'fondation-ms' ),
	);

	// Ordre et affichage des sections.
	$i = 1;
	foreach ( array_keys( fms_sections() ) as $id ) {
		$defaults[ 'section_' . $id . '_show' ]  = true;
		$defaults[ 'section_' . $id . '_order' ] = $i++;
	}

	return $defaults;
}
