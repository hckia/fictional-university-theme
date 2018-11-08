<?php
  /* 
    Index.php is a generic fallback. A last line insurance policy. Often you want something more specific like single.php for individual posts, 
    or page.php for individual pages, or archive.php for archives, etc. 
  */
  get_header();
  pageBanner(array(
    'title' => 'Search Results',
    /* html code for left angled double quotes is &ldquo; 
       html code for right angled double quotes is &rdquo;
    */
    /* XSS attack basic example could be if get_search_query didn't have any escape parameters for code, you can mimic this by giving the argument 
    of false, but then most modern browsers will stop something like http://localhost:3000/?s=<script>alert("hello")</script> from running.
    to be doubly safe WordPress recommends  putting php used in html like this within an esc_html() function, so we did so below...
    */
    'subtitle' => 'You searched for &ldquo;'. esc_html(get_search_query(false)) .'&rdquo;'
  ));
  ?>

  <!-- class attributes classnames will center our blog posts on the page -->
  <div class="container container--narrow page-section">
    <?php 
    if(have_posts()){
      while(have_posts()) {
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
        get_template_part('template-parts/content', get_post_type());
        /* 
            the div that was here has been moved and copied into content-post.php in the template-parts dir template-parts/content-post.php
        */
      }
    } else{
      echo '<h2 class="headline headline--small-plus">No Results Match that search.</h2>';
    }
    /* WordPress has a function that searches for searchform.php that we created called get_search_form */
    get_search_form();
    /* 
            Adding Pagination
    */
    echo paginate_links();
    ?>
  </div>
<?php 
  get_footer();
?>