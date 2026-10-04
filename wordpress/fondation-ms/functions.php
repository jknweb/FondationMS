<?php
/**
 * Fondation MS — fonctions du thème.
 *
 * Ce fichier charge les modules du thème. Chaque fonctionnalité vit dans
 * son propre fichier du dossier /inc pour rester lisible et maintenable.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

define( 'FMS_VERSION', '1.0.0' );
define( 'FMS_DIR', get_template_directory() );
define( 'FMS_URI', get_template_directory_uri() );

require_once FMS_DIR . '/inc/defaults.php';     // Valeurs par défaut (contenu actuel du site).
require_once FMS_DIR . '/inc/helpers.php';      // Fonctions utilitaires d'affichage.
require_once FMS_DIR . '/inc/setup.php';        // Supports du thème, menus, scripts et styles.
require_once FMS_DIR . '/inc/post-types.php';   // Types de contenus : domaines, projets, photos, émissions.
require_once FMS_DIR . '/inc/meta-boxes.php';   // Champs personnalisés (sans extension).
require_once FMS_DIR . '/inc/customizer.php';   // Réglages du thème dans « Personnaliser ».
require_once FMS_DIR . '/inc/importer.php';     // Import du contenu initial.
require_once FMS_DIR . '/inc/admin.php';        // Page d'aide « Fondation MS » dans le tableau de bord.
