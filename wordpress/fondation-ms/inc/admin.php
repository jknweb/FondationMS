<?php
/**
 * Page « Fondation MS » du tableau de bord : guide rapide et import du contenu initial.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajoute la page dans le menu d'administration.
 */
function fms_admin_menu() {
	add_menu_page(
		__( 'Fondation MS', 'fondation-ms' ),
		__( 'Fondation MS', 'fondation-ms' ),
		'edit_theme_options',
		'fms-accueil',
		'fms_admin_page',
		'dashicons-heart',
		3
	);
}
add_action( 'admin_menu', 'fms_admin_menu' );

/**
 * Traite le bouton d'import (jeton de sécurité + droits vérifiés).
 */
function fms_handle_import() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Vous n\'avez pas les droits nécessaires.', 'fondation-ms' ), 403 );
	}
	check_admin_referer( 'fms_import' );
	$count = fms_run_import();
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'     => 'fms-accueil',
				'imported' => rawurlencode( wp_json_encode( $count ) ),
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_fms_import', 'fms_handle_import' );

/**
 * Affiche la page d'accueil de l'administration du thème.
 */
function fms_admin_page() {
	$customize = admin_url( 'customize.php?autofocus[panel]=fms_panel' );
	$links     = array(
		array( $customize, __( 'Textes, images, couleurs, boutons, chiffres, mot du fondateur, ordre des sections', 'fondation-ms' ), __( 'Personnaliser l\'accueil', 'fondation-ms' ) ),
		array( admin_url( 'customize.php?autofocus[section]=title_tagline' ), __( 'Logo, nom du site, icône (favicon)', 'fondation-ms' ), __( 'Identité du site', 'fondation-ms' ) ),
		array( admin_url( 'nav-menus.php' ), __( 'Liens de l\'en-tête et du pied de page, sous-menus', 'fondation-ms' ), __( 'Menus', 'fondation-ms' ) ),
		array( admin_url( 'post-new.php' ), __( 'Cochez « Actualités » pour le fil d\'actualité, « La une » pour l\'encadré de la bannière', 'fondation-ms' ), __( 'Publier une actualité', 'fondation-ms' ) ),
		array( admin_url( 'edit.php?post_type=ms_projet' ), __( 'Titre, texte, image, catégorie, ordre', 'fondation-ms' ), __( 'Projets phares', 'fondation-ms' ) ),
		array( admin_url( 'edit.php?post_type=ms_domaine' ), __( 'Cartes de la section « Domaines d\'intervention »', 'fondation-ms' ), __( 'Domaines d\'intervention', 'fondation-ms' ) ),
		array( admin_url( 'edit.php?post_type=ms_photo' ), __( 'Photos de la galerie et du diaporama', 'fondation-ms' ), __( 'Galerie photo', 'fondation-ms' ) ),
		array( admin_url( 'edit.php?post_type=ms_emission' ), __( 'Émissions et horaires du programme du jour', 'fondation-ms' ), __( 'Programme radio', 'fondation-ms' ) ),
	);
	?>
	<div class="wrap fms-admin">
		<h1><?php esc_html_e( 'Fondation MS — Gérer le site', 'fondation-ms' ); ?></h1>

		<?php
		if ( isset( $_GET['imported'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple affichage.
			$count = json_decode( sanitize_text_field( wp_unslash( $_GET['imported'] ) ), true ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$count = is_array( $count ) ? array_map( 'absint', $count ) : array();
			?>
			<div class="notice notice-success"><p>
				<?php
				printf(
					/* translators: 1: domaines, 2: projets, 3: articles, 4: photos, 5: menus. */
					esc_html__( 'Import terminé : %1$d domaines, %2$d projets, %3$d actualités, %4$d photos et %5$d menus créés. Les éléments déjà présents ont été conservés.', 'fondation-ms' ),
					(int) ( $count['domaines'] ?? 0 ),
					(int) ( $count['projets'] ?? 0 ),
					(int) ( $count['articles'] ?? 0 ),
					(int) ( $count['photos'] ?? 0 ),
					(int) ( $count['menus'] ?? 0 )
				);
				?>
			</p></div>
		<?php endif; ?>

		<div class="card" style="max-width:820px">
			<h2><?php esc_html_e( '1. Importer le contenu de départ', 'fondation-ms' ); ?></h2>
			<p><?php esc_html_e( 'Crée les domaines d\'intervention, les projets phares, les premières actualités, les photos de la galerie et les menus à partir du contenu actuel du site. Rien n\'est supprimé : un élément qui existe déjà (même titre) est ignoré.', 'fondation-ms' ); ?></p>
			<?php if ( get_option( 'fms_imported' ) ) : ?>
				<p><em>
					<?php
					/* translators: %s: date. */
					printf( esc_html__( 'Dernier import : %s.', 'fondation-ms' ), esc_html( wp_date( get_option( 'date_format' ) . ' H:i', (int) get_option( 'fms_imported' ) ) ) );
					?>
				</em></p>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'fms_import' ); ?>
				<input type="hidden" name="action" value="fms_import">
				<?php submit_button( __( 'Importer le contenu de départ', 'fondation-ms' ), 'primary', 'submit', false ); ?>
			</form>
		</div>

		<div class="card" style="max-width:820px">
			<h2><?php esc_html_e( '2. Modifier le site', 'fondation-ms' ); ?></h2>
			<table class="widefat striped">
				<tbody>
				<?php foreach ( $links as $link ) : ?>
					<tr>
						<td style="width:240px"><a href="<?php echo esc_url( $link[0] ); ?>"><strong><?php echo esc_html( $link[2] ); ?></strong></a></td>
						<td><?php echo esc_html( $link[1] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="card" style="max-width:820px">
			<h2><?php esc_html_e( '3. À savoir', 'fondation-ms' ); ?></h2>
			<ul style="list-style:disc;padding-left:20px">
				<li><?php esc_html_e( 'Réglages › Général : vérifiez le fuseau horaire (Kinshasa) pour que l\'émission « En cours » soit juste.', 'fondation-ms' ); ?></li>
				<li><?php esc_html_e( 'Réglages › Lecture : la page d\'accueil du thème s\'affiche automatiquement. Pour lister toutes les actualités, ouvrez la catégorie « Actualités ».', 'fondation-ms' ); ?></li>
				<li><?php esc_html_e( 'Ordre des cartes (projets, domaines, photos) : champ « Ordre » dans le bloc « Attributs » de chaque élément.', 'fondation-ms' ); ?></li>
				<li><?php esc_html_e( 'Sur le site, quand vous êtes connecté, un lien « Modifier cette section » apparaît sur chaque section de l\'accueil.', 'fondation-ms' ); ?></li>
			</ul>
		</div>
	</div>
	<?php
}

/**
 * Message après activation du thème, tant que le contenu de départ n'a pas été importé.
 */
function fms_admin_notice() {
	if ( get_option( 'fms_imported' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'toplevel_page_fms-accueil' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p><strong>%1$s</strong> %2$s <a class="button button-primary" href="%3$s">%4$s</a></p></div>',
		esc_html__( 'Thème Fondation MS activé.', 'fondation-ms' ),
		esc_html__( 'Importez le contenu de départ pour retrouver le site complet.', 'fondation-ms' ),
		esc_url( admin_url( 'admin.php?page=fms-accueil' ) ),
		esc_html__( 'Commencer', 'fondation-ms' )
	);
}
add_action( 'admin_notices', 'fms_admin_notice' );
