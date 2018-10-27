<?php
  /* 
    archive-events.php is for archive event pages specifically such as /events/{authorname}
  */
  get_header();
  pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
  ));
  ?>

  <!-- class attributes classnames will center our blog posts on the page -->
  <div class="container container--narrow page-section">
    <?php while(have_posts()) {
      the_post(); 
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
            Adding Pagination
    */
    echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking for a recap of past events <a href="<?php echo site_url('/past-events')?>">Check out our past events archive.</a></p>
  </div>
<?php 
  get_footer();
?>