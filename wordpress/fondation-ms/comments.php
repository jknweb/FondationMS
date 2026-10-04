<?php
/**
 * Commentaires.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			/* translators: %d: nombre de commentaires. */
			printf( esc_html( _n( '%d commentaire', '%d commentaires', get_comments_number(), 'fondation-ms' ) ), (int) get_comments_number() );
			?>
		</h2>
		<ol class="comment-list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 48 ) ); ?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>
