<?php 
/* 
front-page.php will change how your home page looks, should you modify http://localhost/wp-admin/options-reading.php to change your static Home and 
    blog post page to specific pages you've created.
    When you do this, index.php in your theme folder will serve as your blogs file, wherever that may be.
*/
    get_header(); ?>

  <div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg')?>);"></div>
    <div class="page-banner__content container t-center c-white">
      <h1 class="headline headline--large">Welcome!!</h1>
      <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
      <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
      <a href="<?php echo get_post_type_archive_link('program'); ?>" class="btn btn--large btn--blue">Find Your Major</a>
    </div>
  </div>

  <div class="full-width-split group">
    <div class="full-width-split__one">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
        
        <?php
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
              )
            )
          ));

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
            get_template_part('template-parts/content', 'event');
          }
        ?>
                
        <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event') ?>" class="btn btn--blue">View All Events</a></p>

      </div>
    </div>
    <div class="full-width-split__two">
      <div class="full-width-split__inner">
        <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>

        <!-- The result of the following code will only be one, why? because in our instance we made a custom front-page, separating it from the
        blog posts page. By design, a Home page will only have itself. Duh. 
        < ?php 
            while(have_posts()){
                the_post(); ?>
                <li>< ?php the_title(); ? ></li>
           < ? php }
        ? >
        -->
        <?php
            /* 
                Custom queries are obviously computationally heavy, but this allows us to pull a few posts for our home page. Would probably want
                to leverage cache to avoid a lot of unnecessary heavy lifting. 
            */
            $homePagePosts = new WP_Query(array(
                'posts_per_page' => 2 /*,
                'category_name' => 'awards' */ /*,
                'post-type' => 'page' */ /* post-type defaults to post, but if you say page you'll get a list of pages. */
            )); 
            /* 
                In the while loop below we're using our homePagePosts variable and checking inside it for posts. without this, have_posts will
                be tied to the default query. same goes for the_posts
            */
            while($homePagePosts->have_posts()){
                $homePagePosts->the_post(); ?>
                <div class="event-summary">
                <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink();?>">
                    <span class="event-summary__month"><?php the_time('M');?></span>
                    <span class="event-summary__day"><?php the_time('d');?></span>  
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink();?>"><?php the_title();?></a></h5>
                    <p><?php if(has_excerpt()){ 
                                echo get_the_excerpt();
                             }else {
                                    echo wp_trim_words(get_the_content(), 18);
                             }  ?><a href="<?php the_permalink(); 
                    ?> <a href="<?php the_permalink();?>" class="nu gray">Read more</a></p>
                </div>
                </div>
                <!-- wp_reset_postdata is called to clean up after a custom query. -->
           <?php } wp_reset_postdata();
        ?>        
        <p class="t-center no-margin"><a href="<?php echo site_url('/blog'); ?>" class="btn btn--yellow">View All Blog Posts</a></p>
      </div>
    </div>
  </div>

  <div class="hero-slider">
  <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bus.jpg')?>);">
    <div class="hero-slider__interior container">
      <div class="hero-slider__overlay">
        <h2 class="headline headline--medium t-center">Free Transportation</h2>
        <p class="t-center">All students have free unlimited bus fare.</p>
        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
      </div>
    </div>
  </div>
  <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/apples.jpg')?>);">
    <div class="hero-slider__interior container">
      <div class="hero-slider__overlay">
        <h2 class="headline headline--medium t-center">An Apple a Day</h2>
        <p class="t-center">Our dentistry program recommends eating apples.</p>
        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
      </div>
    </div>
  </div>
  <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bread.jpg')?>);">
    <div class="hero-slider__interior container">
      <div class="hero-slider__overlay">
        <h2 class="headline headline--medium t-center">Free Food</h2>
        <p class="t-center">Fictional University offers lunch plans for those in need.</p>
        <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
      </div>
    </div>
  </div>
</div>


<?php    get_footer();

?>
