<?php
/**
 * Fallback template — redirige vers la homepage
 */
if ( is_home() || is_front_page() ) {
    get_template_part( 'front-page' );
} else {
    get_header();
    ?>
    <main style="padding: 120px 40px; text-align:center;">
      <h1><?php the_title(); ?></h1>
      <?php the_content(); ?>
    </main>
    <?php
    get_footer();
}
