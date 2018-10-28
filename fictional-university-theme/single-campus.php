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
        }
        // we need to reset postdata, otherwise the events section below will not show.
        // the reason behind this is simple, once we utilize our custom query, it hijacks the global ID effectively changing it.
        wp_reset_postdata();

          /* we will use $today within $homePageEvents...*/
          $today = date('Ymd');
          /* Querying our custom post type 'event' NOTE: When you create a new custom post type you need to resave the permalink structure, even
          if there are no changes. For performance reasons, WordPress only updates the Permalink structure at key moments. To resave the permalink
          structure go to your WordPress Dashboard > Settings > Permalink > Scroll down and Select 'save'.
          Also note that single.php will be used as a custom post type template until you create one yourself. If we want a custom template for 
          our custom post-type, we need a new php file named single-{custom-post-type-key-word}.php. For example in the case of events, this would 
          be single-event.php.
          */
          $homePageEvents = new WP_Query(array(
            /* Setting -1 informs WordPress to give all posts that meet these conditions. */
            'posts_per_page' => 2,
            'post_type' => 'event',
            /* orderby key by default is set to post_date. 
              orderby title = sort by title in DESC by default
              orderby rand = randomize posts
              meta_value = order by an extra or custom value. if you set this, you'll need 'meta_key' => 'some_value' before it.
              meta_value_num = a meta value that will have a number value. Since we're using date, we'll use this, but I don't believe it's 
              required
            */
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            /* if you choose title it will default to DESC (z to A), lets set it to ASC by 'order' => 'ASC' */
            'order' => 'ASC',
            /*since we are using dates, we don't want to show older posts that have taken place in the past. we use 'meta_query' to check if the
            custom post's custom field is greater than or equal to today. If it's not, we won't show it. */
            'meta_query' => array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                /* type doesn't have to be here, but lets be specific since we were with meta_value_num */
                'type' => 'numeric'
              ),
              // this is to make sure /programs/biology/ only shows events related to it.
              array(
                  'key' => related_programs,
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"' /*the single quotes encapsulating double quotes is so that we grab a string of "12" instead
                  of any serialized data that may lead to a false positive */
              )
            )
          ));
          if($homePageEvents->have_posts()){
          echo '<hr class="section-break">';
          // echo out html, strings, and concatinate it with a php function.
          echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events </h2>';

          while($homePageEvents->have_posts()){
            $homePageEvents->the_post();
            /* since we call this event summary div in many places, we are going to modularize it and place it in its own file in our subdirectory 
            template-parts. we can then call it with get_template_part that takes two args (second optional). 
            The first is the location, 
            second is to tell it to look for an additional file with the first arg-second arg. for example if... 
            get_template_part('template-parts/content', 'excerpt');
            this tells WordPress to look for content-excerpt in ./template-parts
            this might seem redundant, but if we were to make this more dynamic, for example... 
            get_template_part('template-parts/content', get_post_type());
            we suddenly have a dynamic function that will search for a file based on our post types (content-post, content-professor, content-event)
            */
            get_template_part('template-parts/content-event');
          }
          }
        ?>

        </div>
    <?php }
    get_footer();
?>