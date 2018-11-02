<?php
    /* 
        single.php - individual posts
        page.php - individual pages
    */
    get_header();
      pageBanner();
    while(have_posts()){
        the_post();?>
        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <!-- Self explanatory what get_post_type_archive_link is, but to make it easier to grep if forgotten custom post types can be
                Modified. This makes it dynamic, by grabbing the static custom-post type name, and searching for any rewrites.
                -->
                <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"> <?php the_title(); ?></span></p>
            </div>
            <div class="generic-content">
                <?php the_content(); ?>
            </div>
            <?php 
                $mapLocation = get_field('map_location');
            ?>
            <div class="acf-map">
                <div class="marker" data-lat="<?php echo $mapLocation['lat'];?>" data-lng="<?php echo $mapLocation['lng'];?>">
                <h3><?php the_title(); ?></h3>
                <?php echo $mapLocation['address']; ?>
                </div>
            </div>

        <?php

        $relatedPrograms = new WP_Query(array(
            /* Setting -1 informs WordPress to give all posts that meet these conditions. */
            'posts_per_page' => -1,
            'post_type' => 'program',
            /* orderby key by default is set to post_date. 
              orderby title = sort by title in DESC by default
              orderby rand = randomize posts
            */
            'orderby' => 'title',
            /* if you choose title it will default to DESC (z to A), lets set it to ASC by 'order' => 'ASC' */
            'order' => 'ASC',
            /*since we are using dates, we don't want to show older posts that have taken place in the past. we use 'meta_query' to check if the
            custom post's custom field is greater than or equal to today. If it's not, we won't show it. */
            'meta_query' => array(
              array(
                  'key' => related_campus,
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"' /*the single quotes encapsulating double quotes is so that we grab a string of "12" instead
                  of any serialized data that may lead to a false positive */
              )
            )
        ));
        if($relatedPrograms->have_posts()){
        echo '<hr class="section-break">';
        // echo out html, strings, and concatinate it with a php function.
        echo '<h2 class="headline headline--medium">Programs Available at this Campus</h2>';
        echo '<ul class="min-list link-list">';
        while($relatedPrograms->have_posts()){
          $relatedPrograms->the_post(); ?>
          <li>
            <a href="<?php the_permalink();?>"><?php the_title();?></a>
          </li>
        <?php }
        echo '</ul>';
        } wp_reset_postdata(); ?>

        </div>
    <?php }
    get_footer();
?>