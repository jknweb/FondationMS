<?php
/**
 * Modèle d'un article (actualité) ou d'un projet phare.
 *
 * @package Fondation_MS
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$fms_is_project = 'ms_projet' === get_post_type();
	$fms_terms      = $fms_is_project ? get_the_terms( get_the_ID(), 'ms_projet_cat' ) : get_the_category();
	$fms_source     = get_post_meta( get_the_ID(), '_fms_source', true );

	ob_start();
	?>
	<p class="entry-meta">
		<span><?php fms_the_icon( 'calendar' ); ?> <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( $fms_is_project ? get_the_date() : fms_post_label() ); ?></time></span>
		<span><?php fms_the_icon( 'user' ); ?> <?php the_author(); ?></span>
		<?php if ( $fms_terms && ! is_wp_error( $fms_terms ) ) : ?>
			<span><?php fms_the_icon( 'book' ); ?>
				<?php
				echo wp_kses_post(
					implode(
						', ',
						array_map(
							function ( $t ) {
								return '<a href="' . esc_url( get_term_link( $t ) ) . '">' . esc_html( $t->name ) . '</a>';
							},
							$fms_terms
						)
					)
				);
				?>
			</span>
		<?php endif; ?>
		<?php if ( $fms_source ) : ?>
			<span><?php fms_the_icon( 'radio' ); ?> <?php echo esc_html( $fms_source ); ?></span>
		<?php endif; ?>
	</p>
	<?php
	$fms_meta = ob_get_clean();

	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => $fms_is_project ? __( 'Projet phare', 'fondation-ms' ) : __( 'Actualité', 'fondation-ms' ),
			'title'    => get_the_title(),
			'subtitle' => $fms_meta,
		)
	);
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
		<div class="container container--narrow">
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="entry-image"><?php the_post_thumbnail( 'fms-wide' ); ?></figure>
			<?php endif; ?>
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages( array( 'before' => '<nav class="page-links">' . esc_html__( 'Pages :', 'fondation-ms' ), 'after' => '</nav>' ) );
				?>
			</div>
			<?php if ( ! $fms_is_project && get_the_tags() ) : ?>
				<p class="entry-tags"><?php the_tags( '', ' ' ); ?></p>
			<?php endif; ?>

			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="nav-label">' . esc_html__( 'Précédent', 'fondation-ms' ) . '</span><span class="nav-title">%title</span>',
					'next_text' => '<span class="nav-label">' . esc_html__( 'Suivant', 'fondation-ms' ) . '</span><span class="nav-title">%title</span>',
				)
			);
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
