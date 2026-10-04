<?php
/**
 * Champs personnalisés, sans extension, avec des intitulés clairs en français.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Déclare les métadonnées (visibles dans l'API REST pour l'éditeur de blocs).
 */
function fms_register_meta() {
	$auth = function () {
		return current_user_can( 'edit_posts' );
	};
	$fields = array(
		'post'        => array( '_fms_label' => 'string', '_fms_source' => 'string' ),
		'ms_domaine'  => array( '_fms_icon' => 'string', '_fms_color' => 'string', '_fms_featured' => 'boolean', '_fms_items' => 'string', '_fms_box_label' => 'string', '_fms_box_text' => 'string' ),
		'ms_projet'   => array( '_fms_icon' => 'string', '_fms_color' => 'string' ),
		'ms_emission' => array( '_fms_start' => 'string', '_fms_end' => 'string' ),
	);
	foreach ( $fields as $type => $metas ) {
		foreach ( $metas as $key => $kind ) {
			register_post_meta(
				$type,
				$key,
				array(
					'type'          => $kind,
					'single'        => true,
					'show_in_rest'  => false,
					'auth_callback' => $auth,
				)
			);
		}
	}
}
add_action( 'init', 'fms_register_meta' );

/**
 * Ajoute les boîtes de champs dans l'éditeur.
 */
function fms_add_meta_boxes() {
	add_meta_box( 'fms_post_box', __( 'Affichage sur l\'accueil', 'fondation-ms' ), 'fms_post_box', 'post', 'side', 'default' );
	add_meta_box( 'fms_domaine_box', __( 'Réglages de la carte', 'fondation-ms' ), 'fms_domaine_box', 'ms_domaine', 'normal', 'high' );
	add_meta_box( 'fms_projet_box', __( 'Réglages de la carte', 'fondation-ms' ), 'fms_projet_box', 'ms_projet', 'side', 'default' );
	add_meta_box( 'fms_emission_box', __( 'Horaire de l\'émission', 'fondation-ms' ), 'fms_emission_box', 'ms_emission', 'normal', 'high' );
	add_meta_box( 'fms_photo_help', __( 'Comment ajouter une photo', 'fondation-ms' ), 'fms_photo_help', 'ms_photo', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'fms_add_meta_boxes' );

/**
 * Liste déroulante générique.
 *
 * @param string $name    Nom du champ.
 * @param array  $choices Choix.
 * @param string $value   Valeur actuelle.
 */
function fms_select( $name, $choices, $value ) {
	echo '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" class="widefat">';
	foreach ( $choices as $key => $label ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $value, $key, false ), esc_html( $label ) );
	}
	echo '</select>';
}

/**
 * Champs des articles (actualités).
 *
 * @param WP_Post $post Article.
 */
function fms_post_box( $post ) {
	wp_nonce_field( 'fms_save_meta', 'fms_meta_nonce' );
	?>
	<p>
		<label for="fms_label"><strong><?php esc_html_e( 'Étiquette de date (facultatif)', 'fondation-ms' ); ?></strong></label>
		<input type="text" class="widefat" id="fms_label" name="fms_label" value="<?php echo esc_attr( get_post_meta( $post->ID, '_fms_label', true ) ); ?>" placeholder="<?php esc_attr_e( 'Ex. Rentrée scolaire 2026-2027', 'fondation-ms' ); ?>">
		<span class="description"><?php esc_html_e( 'Remplace la date de publication sur les cartes. Laisser vide pour afficher la date.', 'fondation-ms' ); ?></span>
	</p>
	<p>
		<label for="fms_source"><strong><?php esc_html_e( 'Source (facultatif)', 'fondation-ms' ); ?></strong></label>
		<input type="text" class="widefat" id="fms_source" name="fms_source" value="<?php echo esc_attr( get_post_meta( $post->ID, '_fms_source', true ) ); ?>" placeholder="<?php esc_attr_e( 'Ex. Radio la Voix de la Mongala', 'fondation-ms' ); ?>">
	</p>
	<p class="description">
		<?php esc_html_e( 'Pour apparaître dans le fil d\'actualité de l\'accueil, cochez la catégorie « Actualités ». Pour l\'encadré de la bannière, cochez « La une ». L\'image mise en avant illustre la carte.', 'fondation-ms' ); ?>
	</p>
	<?php
}

/**
 * Champs des domaines d'intervention.
 *
 * @param WP_Post $post Domaine.
 */
function fms_domaine_box( $post ) {
	wp_nonce_field( 'fms_save_meta', 'fms_meta_nonce' );
	$icon  = get_post_meta( $post->ID, '_fms_icon', true );
	$color = get_post_meta( $post->ID, '_fms_color', true );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="fms_icon"><?php esc_html_e( 'Icône', 'fondation-ms' ); ?></label></th>
			<td><?php fms_select( 'fms_icon', fms_icon_choices(), $icon ? $icon : 'book' ); ?></td>
		</tr>
		<tr>
			<th><label for="fms_color"><?php esc_html_e( 'Couleur', 'fondation-ms' ); ?></label></th>
			<td><?php fms_select( 'fms_color', fms_color_choices(), $color ? $color : 'vert' ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Grande carte', 'fondation-ms' ); ?></th>
			<td>
				<label><input type="checkbox" name="fms_featured" value="1" <?php checked( (bool) get_post_meta( $post->ID, '_fms_featured', true ) ); ?>> <?php esc_html_e( 'Afficher ce domaine en grand, à gauche (un seul domaine conseillé).', 'fondation-ms' ); ?></label>
			</td>
		</tr>
		<tr>
			<th><label for="fms_items"><?php esc_html_e( 'Liste d\'actions', 'fondation-ms' ); ?></label></th>
			<td>
				<textarea class="widefat" rows="5" id="fms_items" name="fms_items" placeholder="<?php esc_attr_e( 'Fournitures scolaires | Distribution de fournitures aux élèves', 'fondation-ms' ); ?>"><?php echo esc_textarea( get_post_meta( $post->ID, '_fms_items', true ) ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Facultatif. Une action par ligne, au format : Titre | Description.', 'fondation-ms' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="fms_box_label"><?php esc_html_e( 'Encadré (facultatif)', 'fondation-ms' ); ?></label></th>
			<td>
				<input type="text" class="regular-text" id="fms_box_label" name="fms_box_label" value="<?php echo esc_attr( get_post_meta( $post->ID, '_fms_box_label', true ) ); ?>" placeholder="<?php esc_attr_e( 'Étiquette, ex. Opération', 'fondation-ms' ); ?>">
				<input type="text" class="regular-text" id="fms_box_text" name="fms_box_text" value="<?php echo esc_attr( get_post_meta( $post->ID, '_fms_box_text', true ) ); ?>" placeholder="<?php esc_attr_e( 'Texte, ex. « Pas une école sans banc »', 'fondation-ms' ); ?>">
			</td>
		</tr>
	</table>
	<p class="description"><?php esc_html_e( 'Le texte de l\'éditeur sert de description. L\'ordre d\'affichage se règle dans « Attributs › Ordre » (du plus petit au plus grand).', 'fondation-ms' ); ?></p>
	<?php
}

/**
 * Champs des projets phares.
 *
 * @param WP_Post $post Projet.
 */
function fms_projet_box( $post ) {
	wp_nonce_field( 'fms_save_meta', 'fms_meta_nonce' );
	$icon  = get_post_meta( $post->ID, '_fms_icon', true );
	$color = get_post_meta( $post->ID, '_fms_color', true );
	?>
	<p><label for="fms_icon"><strong><?php esc_html_e( 'Icône (si pas d\'image)', 'fondation-ms' ); ?></strong></label><?php fms_select( 'fms_icon', fms_icon_choices(), $icon ? $icon : 'building' ); ?></p>
	<p><label for="fms_color"><strong><?php esc_html_e( 'Couleur de fond (si pas d\'image)', 'fondation-ms' ); ?></strong></label><?php fms_select( 'fms_color', fms_color_choices(), $color ? $color : 'vert' ); ?></p>
	<p class="description"><?php esc_html_e( 'L\'image mise en avant illustre la carte. Le résumé (extrait) s\'affiche sur l\'accueil.', 'fondation-ms' ); ?></p>
	<?php
}

/**
 * Champs des émissions de radio.
 *
 * @param WP_Post $post Émission.
 */
function fms_emission_box( $post ) {
	wp_nonce_field( 'fms_save_meta', 'fms_meta_nonce' );
	$days = array_map( 'intval', array_filter( (array) get_post_meta( $post->ID, '_fms_days', true ) ) );
	?>
	<table class="form-table" role="presentation">
		<tr>
			<th><label for="fms_start"><?php esc_html_e( 'Début', 'fondation-ms' ); ?></label></th>
			<td><input type="time" id="fms_start" name="fms_start" value="<?php echo esc_attr( get_post_meta( $post->ID, '_fms_start', true ) ); ?>" required></td>
		</tr>
		<tr>
			<th><label for="fms_end"><?php esc_html_e( 'Fin', 'fondation-ms' ); ?></label></th>
			<td><input type="time" id="fms_end" name="fms_end" value="<?php echo esc_attr( get_post_meta( $post->ID, '_fms_end', true ) ); ?>" required></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Jours de diffusion', 'fondation-ms' ); ?></th>
			<td>
				<?php foreach ( fms_weekdays() as $num => $label ) : ?>
					<label style="margin-right:12px;display:inline-block"><input type="checkbox" name="fms_days[]" value="<?php echo esc_attr( $num ); ?>" <?php checked( in_array( $num, $days, true ) ); ?>> <?php echo esc_html( $label ); ?></label>
				<?php endforeach; ?>
				<p class="description"><?php esc_html_e( 'Ne cochez rien pour une émission diffusée tous les jours. Les horaires suivent le fuseau horaire défini dans Réglages › Général.', 'fondation-ms' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Aide pour les photos.
 */
function fms_photo_help() {
	echo '<p>' . esc_html__( '1. Saisissez la légende dans le titre. 2. Choisissez la photo dans « Image » (colonne de droite). 3. Publiez. La photo apparaît dans la galerie et dans le diaporama de l\'accueil. L\'ordre se règle dans « Attributs › Ordre ».', 'fondation-ms' ) . '</p>';
}

/**
 * Enregistre les champs, avec vérification du jeton de sécurité et des droits.
 *
 * @param int $post_id Identifiant du contenu.
 */
function fms_save_meta( $post_id ) {
	if ( ! isset( $_POST['fms_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fms_meta_nonce'] ) ), 'fms_save_meta' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array( 'fms_label', 'fms_source', 'fms_box_label', 'fms_box_text' );
	foreach ( $text_fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, '_' . $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
	if ( isset( $_POST['fms_items'] ) ) {
		update_post_meta( $post_id, '_fms_items', sanitize_textarea_field( wp_unslash( $_POST['fms_items'] ) ) );
	}
	if ( isset( $_POST['fms_icon'] ) ) {
		$icon = sanitize_key( wp_unslash( $_POST['fms_icon'] ) );
		update_post_meta( $post_id, '_fms_icon', array_key_exists( $icon, fms_icon_choices() ) ? $icon : 'book' );
	}
	if ( isset( $_POST['fms_color'] ) ) {
		$color = sanitize_key( wp_unslash( $_POST['fms_color'] ) );
		update_post_meta( $post_id, '_fms_color', array_key_exists( $color, fms_color_choices() ) ? $color : 'vert' );
	}

	$type = get_post_type( $post_id );
	if ( 'ms_domaine' === $type ) {
		update_post_meta( $post_id, '_fms_featured', isset( $_POST['fms_featured'] ) ? 1 : 0 );
	}
	if ( 'ms_emission' === $type ) {
		foreach ( array( 'fms_start', 'fms_end' ) as $field ) {
			$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
			update_post_meta( $post_id, '_' . $field, preg_match( '/^([01]\d|2[0-3]):[0-5]\d$/', $value ) ? $value : '' );
		}
		$days = isset( $_POST['fms_days'] ) ? array_map( 'absint', (array) wp_unslash( $_POST['fms_days'] ) ) : array();
		update_post_meta( $post_id, '_fms_days', array_values( array_intersect( $days, array_keys( fms_weekdays() ) ) ) );
	}
}
add_action( 'save_post', 'fms_save_meta' );
