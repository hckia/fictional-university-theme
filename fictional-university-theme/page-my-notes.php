<?php
    if(!is_user_logged_in()){
        wp_redirect(esc_url(site_url('/')));
        exit;
    }
    /* 
        single.php - individual posts
        page.php - individual pages
    */
    get_header();

    while(have_posts()){
        the_post();
        /*
        array below is for testing pageBanner on the about us page.
        array(
            'title' => 'Hello there this is the title',
            'subtitle' => 'We are a great school that has been around for a long time!',
            'photo' => 'https://upload.wikimedia.org/wikipedia/commons/8/86/360-degree_Panorama_of_the_Southern_Sky_edit.jpg'
        )
        */
        pageBanner();
        ?>

  <div class="container container--narrow page-section">
      <div class="create-note">
          <h2 class="headline headline--medium">Create New Note</h2>
          <input class="new-note-title" placeholder="Title">
          <textarea class="new-note-body" placeholder="Your note here..."></textarea>
          <span class="submit-note">Create Note</span>
          <span class="note-limit-message">note limit reached: delete an existing note to make room for a new one!</span>
      </div>
      <ul class="min-list link-list" id="my-notes">
          <?php 
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'post_per_page' => -1,
                'author' => get_current_user_id()
            ));

            while($userNotes->have_posts()){
                $userNotes->the_post(); ?>
                <li data-id="<?php the_ID(); ?>">
                <!-- the readonly attribute makes it so we cant simply click on and update our input or textarea -->
                <!-- as it stands, esc_attr will add Private: to the beginning of our title, we will use the str_replace function to get rid of that
                portion. str_replace takes three arguments. 
                1. The word/phrase/letter we want to replace
                2. what we want to replace it with.
                3. the original string we want to manipulate
                -->
                    <input readonly class="note-title-field" value="<?php echo str_replace('Private: ','',esc_attr(get_the_title())); ?>"/>
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                    <textarea readonly class="note-body-field"><?php echo esc_textarea(get_the_content()); ?></textarea>
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i>Save</span>
                </li>
           <?php }
          ?>
      </ul>
  </div>

    <?php }

    get_footer();
?>