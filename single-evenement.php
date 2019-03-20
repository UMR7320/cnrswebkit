<?php
/**
 * The default template for displaying all single posts and attachments
 *
 * @package Atos
 * @subpackage CNRS_Web_Kit
 * @since CNRS Web Kit 1.0
 */

global $cnrs_global_params;
$sidebar = $cnrs_global_params->field('evenement_with_sidebar');

if (! $sidebar){
    add_filter( 'body_class', 'add_no_sidebar_class' );
}

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the single post content template.
			get_template_part( 'template-parts/content', 'evenement' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}

			if ( is_singular( 'attachment' ) ) {
				// Parent post navigation.
				the_post_navigation( array(
					'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'cnrswebkit' ),
				) );
			} elseif ( is_singular( 'post' ) ) {
				// Previous/next post navigation.
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'cnrswebkit' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Next post:', 'cnrswebkit' ) . '</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'cnrswebkit' ) . '</span> ' .
						'<span class="screen-reader-text">' . __( 'Previous post:', 'cnrswebkit' ) . '</span> ' .
						'<span class="post-title">%title</span>',
				) );
			}

			// End of the loop.
		endwhile;
		?>

	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->
<?php 
if ( $sidebar ){
    get_sidebar();
}

get_footer(); 