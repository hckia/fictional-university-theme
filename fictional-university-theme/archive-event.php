<?php
  /* 
    archive-events.php is for archive event pages specifically such as /events/{authorname}
  */
  get_header();
  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg')?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title">
      <!-- grab the archive title in this case we don't need it to be dynamic since this is specific to events. We can hardcode it safely -->
      All Events
      <!-- 
      If you want finer granular control, and you don't use dates, the options below for single_cat_title for categories and the_author function
      will do nicely, however, if you have dates, the option above with (the _archive_title()) is probably best, as it will simply give you the
      archive title of whatever you're on. (month, day, year, author, category, etc).    
      < ?php if(is_category()) {
          single_cat_title();
      }
      if (is_author()){
        echo 'Posts by '; the_author();
      } ? > </h1> -->
      <div class="page-banner__intro">
        <p>See what is going on in our world.</p>
      </div>
    </div>  
  </div>

  <!-- class attributes classnames will center our blog posts on the page -->
  <div class="container container--narrow page-section">
    <?php while(have_posts()) {
      the_post(); ?>
      <div class="event-summary">
        <a class="event-summary__date t-center" href="#">
        <span class="event-summary__month">Mar</span>
        <span class="event-summary__day">25</span>  
        </a>
        <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <!-- -->
        <p><?php if(has_excerpt()){ 
            echo get_the_excerpt();
        } else {
             echo wp_trim_words(get_the_content(), 18);
        } ?><a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
        </div>
      </div>
    <?php } 
    /* 
            Adding Pagination
    */
    echo paginate_links();
    ?>
  </div>
<?php 
  get_footer();
?>