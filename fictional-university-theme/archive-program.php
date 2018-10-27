<?php
  /* 
    archive-events.php is for archive event pages specifically such as /events/{authorname}
  */
  get_header();
  pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'there is something for everyone. Have a look around.'
  ));
  ?>

  <!-- class attributes classnames will center our blog posts on the page -->
  <div class="container container--narrow page-section">
    <ul class="link-list min-list">
    <?php while(have_posts()) {
      the_post(); ?>
      <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
    <?php } 
    /* 
            Adding Pagination
    */
    echo paginate_links();
    ?>
    </ul>
  </div>
<?php 
  get_footer();
?>