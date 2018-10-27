<?php
  /* 
    page-past-events.php is for past events
  */
  get_header();
  pageBanner(array(
    'title' => 'A recap of our past events',
    'subtitle' => 'See what has gone on in our world.'
  ));
  ?>

  <!-- class attributes classnames will center our blog posts on the page -->
  <div class="container container--narrow page-section">
    <?php 
    
        $today = date('Ymd');
        /* Querying our custom post type 'event' NOTE: When you create a new custom post type you need to resave the permalink structure, even
        if there are no changes. For performance reasons, WordPress only updates the Permalink structure at key moments. To resave the permalink
        structure go to your WordPress Dashboard > Settings > Permalink > Scroll down and Select 'save'.
        Also note that single.php will be used as a custom post type template until you create one yourself. If we want a custom template for 
        our custom post-type, we need a new php file named single-{custom-post-type-key-word}.php. For example in the case of events, this would 
        be single-event.php.
        */
        $pastEvents = new WP_Query(array(
        /* with this custom query we need to let our pagination function near the end of the file what page results to pull in 
        paged tells it to get the query var, and if there isn't one, we are probably on the first page (1)
        */
        'paged'=> get_query_var('paged',1),
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
            'compare' => '<',
            'value' => $today,
            /* type doesn't have to be here, but lets be specific since we were with meta_value_num */
            'type' => 'numeric'
            )
        )
        ));
    
    while($pastEvents->have_posts()) {
      $pastEvents->the_post(); 
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
    /* 
            Adding Pagination. For custom queries paginate_links will not work without additional arguments.
    */
    echo paginate_links(array(
        'total'=>$pastEvents->max_num_pages
    ));
    ?>
  </div>
<?php 
  get_footer();
?>