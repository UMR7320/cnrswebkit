<?php
/**
 * Template Name: CNRS WebKit list of events
 * Template Post Type: post, page
 *
 * The template for displaying a list of events
 *
 * @package Atos
 * @subpackage CNRS_Web_Kit
 * @since CNRS Web Kit 1.0
 * 
 */

// Translators: Template Name translation.
__('CNRS WebKit list of events', 'cnrswebkit');

global $cnrs_global_params;
$sidebar = $cnrs_global_params->field('liste_evenements_with_sidebar');

if (! $sidebar){
    add_filter( 'body_class', 'add_no_sidebar_class' );
}


get_header();
// TODO next line commented in V0.3! Is ajax useful?? 
// require_once( get_template_directory() . '/inc/ajax.php' );  
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header><!-- .entry-header -->
            <?php cnrswebkit_post_thumbnail(); ?>
            <div class="entry-content">
                <?php
                while (have_posts()) : the_post();
                    the_content();
                endwhile;
                $_SESSION['date_month'] = false;
                $evenement_data = new CnrswebkitPageItemsList('evenement');
                echo $evenement_data->get_html_filters();
                if ($evenement_data->has_items() ) {
                    echo $evenement_data->get_html_item_list();
                    echo '<div id="evenement_ajax_container"></div>';
                    ?>
                    <div class="moreEvents"><a page="1" target="#evenement_ajax_container">Afficher plus d'évènements</a></div>
                    <?php
                } else {
                    echo '<br/><p>'. __('There is currently no event in the present list', 'cnrswebkit') . '</p>';
                }
                display_bottom_partenaires();
                ?>
            </div><!-- .entry-content -->
        </article><!-- #post-## -->
    </main><!-- .site-main -->
<?php get_sidebar('content-bottom'); ?>
</div><!-- .content-area -->
<?php 
if ( $sidebar ){
    get_sidebar();
}

get_footer(); 


