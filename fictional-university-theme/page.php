<?php
    /* 
        single.php - individual posts
        page.php - individual pages
    */
    get_header();

    while(have_posts()){
        the_post();?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/ocean.jpg')?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php the_title(); ?></h1>
      <div class="page-banner__intro">
        <p>Don't forget to replace me later.</p>
      </div>
    </div>  
  </div>

  <div class="container container--narrow page-section">

    <?php
        /*if(2+2 == 4) {
           echo "The sky is blue";
        }*/
        // function below gets the page ID
        //echo get_the_ID();
        /* function gets the parent ID when there are no parameters, but to make it dynamic 
        we pass the get_the_ID to the function below. If we go to a page with no Parent, it will now return 0 in its current form*/
        //echo wp_get_post_parent_id(get_the_ID());
        $theParentID = wp_get_post_parent_id(get_the_ID());
        if($theParentID) { ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
            <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParentID); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParentID); ?></a> <span class="metabox__main"><?php the_title(); ?></span></p>
            </div>
       <?php }
    ?>

    <?php 
    //get_pages works similar to wp_list_pages except it returns it in memory
    $testArray = get_pages(array(
        'child_of' => get_the_ID()
    ));
    if($theParentID or $testArray) { ?>
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParentID); ?>"><?php echo get_the_title($theParentID); ?></a></h2>
      <ul class="min-list">
        <?php 
            if ($theParentID) {
                //if there's a Parent... 
                $findChildrenOf = $theParentID;
            } else {
                //if no Parent... 
                $findChildrenOf = get_the_ID();
            }
            // array $animals = array('cat', 'dog')
            // associative array $animalSounds = array('cat' => 'meow', 'dog' => 'bark')
            // echo $animalSounds['dog']; // will echo bark
            wp_list_pages(array(
                // removes 'pages' from the wp_list_pages function's result
                'title_li' => NULL,
                'child_of' => $findChildrenOf,
                'sort_column' => 'menu_order' /* changes the alphabetical ordering of the list to the menu_order 
                                                 found on each page's WordPress dashboard under menu order
                                              */
            ));
        ?>
      </ul>
    </div>
    <?php } ?>

    <div class="generic-content">
      <?php the_content(); ?>
    </div>

  </div>

    <?php }

    get_footer();
?>