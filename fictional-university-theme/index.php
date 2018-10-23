<?php
  /* 
    Index.php is a generic fallback. A last line insurance policy. Often you want something more specific like single.php for individual posts, 
    or page.php for individual pages, or archive.php for archives, etc. 
  */
  get_header();
  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg')?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"> Welcome to our blog! <!-- < ? php the_title(); ? >--> </h1>
      <div class="page-banner__intro">
        <p>Keep up with our latest news</p>
      </div>
    </div>  
  </div>

  <!-- class attributes classnames will center our blog posts on the page -->
  <div class="container container--narrow page-section">
    <?php while(have_posts()) {
      the_post(); ?>
      <!-- post-item will create a bit of border and vertical space between individual posts -->
      <div class="post-item">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <!-- css class to make things look nice -->
        <div class="metabox">
          <!-- authors post link will show the author in lower case. To control this go to wp-admin > users > "Your Profile" > change nickname to 
           uppercase > then under "display name publicly as" you can choose the nickname
           http://localhost/wp-admin/profile.php 
           -->
           <!-- Go to WordPress codex on formatting date and time for the_time() - https://codex.wordpress.org/Formatting_Date_and_Time 
            You can separate n j y with - . or whatever. Just depends how you want it to show
           -->
           <!-- all get functions need to be echoed, and the comma in get_the_category_list is in case it has multiple categories. 
           
           note that all categories by default will show under http://localhost/category/ . For example 'awards' category will be 
           http://localhost/category/awards/
           -->
          <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in <?php echo get_the_category_list(','); ?></p>
        </div>
        <div class="generic-content">
          <?php 
            the_excerpt();
          ?>
          <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
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