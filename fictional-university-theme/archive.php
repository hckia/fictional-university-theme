<?php
  /* 
    archive.php is for archive pages such as /author/{authorname}, or /category/{categoryname}
  */
  get_header();
  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg')?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title">
      <!-- grab the archive title -->
      <?php the_archive_title(); ?>
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
        <!-- the_archive_description will grab the description of that archive, if it has one. In the case of an author it will grab it from
        Users > Your Profile > Biographical Info within your WordPress dashboard. 
        For Categories if you go to Posts > Categories > click on a specific Category's hyperlink > Description... This will be where you place
        content for the_archive_description to grab data for that specific category.
        -->
        <p><?php the_archive_description(); ?></p>
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