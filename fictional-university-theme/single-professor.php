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

        <div class="container container--narrow page-section">


            <div class="generic-content">

                <div class="row group">

                    <div class="one-third">
                        <?php the_post_thumbnail('professorPortrait'); ?>
                    </div>

                    <div class="two-thirds">
                        <?php 
                            $likeCount = new WP_Query(array(
                                'post_type'=> 'like',
                                /*we need to use a meta query because we only want to pull in liked posts where the professors 
                                liked id matches the professor page */
                                'meta_query' => array(
                                    array(
                                        'key' => 'like_professor_id',
                                        'compare' => '=',
                                        'value' => get_the_ID()
                                    )
                                )
                            ));

                            $existStatus = 'no';
                            /* if we don't have the first if statement below, logged out users will see a filled in heart, when it should be empty, they 
                            haven't liked nor can they! */
                            if(is_user_logged_in()){
                                $existQuery = new WP_Query(array(
                                    'author' => get_current_user_id(),
                                    'post_type'=> 'like',
                                    /*we need to use a meta query because we only want to pull in liked posts where the professors 
                                    liked id matches the professor page */
                                    'meta_query' => array(
                                        array(
                                            'key' => 'like_professor_id',
                                            'compare' => '=',
                                            'value' => get_the_ID()
                                        )
                                    )
                                ));
                                if($existQuery->found_posts){
                                    $existStatus = 'yes';
                                }
                            }

                        ?>
                        <!-- 
                            You can read more about how data-exists works in this file on the following line... 
                            fictional-university-theme/css/modules/shame.css:282:.like-box[data-exists="yes"] .fa-heart {
                            fictional-university-theme/css/modules/shame.css:288:.like-box[data-exists="yes"] .fa-heart-o {
                        -->
                        <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID; ?>" data-professor="<?php the_id(); ?>" data-exists="<?php echo $existStatus; ?>">
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
                        </span>
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