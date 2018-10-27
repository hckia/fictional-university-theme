<?php
    /* 
        single.php - individual posts
        page.php - individual pages
    */
    get_header();
    while(have_posts()){
        the_post();
        pageBanner();
        ?>

        <div class="container container--narrow page-sectiodn">


            <div class="generic-content">

                <div class="row group">

                    <div class="one-third">
                        <?php the_post_thumbnail('professorPortrait'); ?>
                    </div>

                    <div class="two-thirds">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
            <?php 
                $relatedPrograms = get_field('related_programs');

                if($relatedPrograms){
                    // If you are ever curous what an accessor method (get function) returns, you can type this into php... print_r($relatedPrograms);
                    echo '<hr class="section-break">';
                    echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
                    echo '<ul class="link-list min-list">';
                    foreach($relatedPrograms as $program){ ?>
                        <!-- echos out every single program associated with the event echo get_the_title($program); -->
                        <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>
                    <?php  }
                    echo '</ul>';    
                }
            ?>
        </div>
    <?php }
    get_footer();
?>