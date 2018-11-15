<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <!-- How to tell  the web browser what type of characters, letters or numbers you'll use on a page-->
        <meta charset="<?php bloginfo('charset'); ?>">
        <!-- The meta tag below tells devices to be true to their size -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php wp_head(); ?>
    </head>
    <!-- adds classname to the class attributes based on the page you're on. useful for js and css -->
    <body <?php body_class();?>>
         <header class="site-header">
    <div class="container">
      <h1 class="school-logo-text float-left"><a href="<?php echo site_url()?>"><strong>Fictional</strong> University</a></h1>
      <!-- Changed from span to a link so that if JavaScript is disabled the user will be carried to a traditional search page when clicking our icon -->
      <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
      <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
      <div class="site-header__menu group">
        <nav class="main-navigation">
          <!-- For custom themes you'll want to control your navigation menu through a unordered 
                list, but for more generic themes there's another way (see below the ul tag, and check 
                for more information in functions.php under the university_features function).
                One benefit of using wp_nav_menu function as you'll see below in this file is that it
                will "highlight" an active page. this is because it will add 'current-menu-item' 
                className to our list items dynamically, and then you can control its value through
                css
          -->
          <ul>
            <!-- is_page takes the slug (kind of like the end point url for a page / post) and will return true
                 if the slug is active. if its a child theme and we want the parent thing to still be active
                 in the menu when a child page is active, we need to add the or statement wp_get_post_parent_id(0)
                 which will tell WordPress to look up the current parent page. Then we want to ensure it is the
                 equivalent of our lists numerical id. For example, since the first on our list is 'about-us', and
                 in this particular instance about-us as the id of 10, we write wp_get_post_parent_id(0) == 10
            -->
            <li <?php if(is_page('about-us') or wp_get_post_parent_id(0) == 10) echo 'class="current-menu-item"'; ?>><a href="<?php echo site_url('/about-us')?>">About Us</a></li>
            <li <?php if(get_post_type() == 'program') echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('program');?>">Programs</a></li>
            <li <?php if(get_post_type() == 'event' OR is_page('past-events')) echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>
            <li <?php if(get_post_type() == 'campus') echo 'class="current-menu-item"';?>><a href="<?php echo get_post_type_archive_link('campus');?>">Campuses</a></li>
            <li <?php if(get_post_type() == 'post') echo 'class="current-menu-item"';?>><a href="<?php echo site_url('/blog'); ?>">Blog</a></li>
          </ul>
          <!-- our dynamic menu, generated from our WordPress dashboard through our university-features
               function in functions.php

          -->
         <!-- Be sure to remove the spaces after the greater than less than signs, and between the
         question mark and php < ? php 
            wp_nav_menu(array(
              'theme_location' => 'headerMenuLocation'
            )); 
          ? >  -->
        </nav>
        <div class="site-header__util">
          <?php if(is_user_logged_in()) { ?>
            <a href="<?php echo esc_url(site_url('/my-notes')); ?>" class="btn btn--small btn--orange float-left push-right">My Notes</a>
            <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small  btn--dark-orange float-left btn--with-photo">
            <!-- get_avatar takes two args, first is the user id, second is the size of the image -->
            <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span>
            <span class="btn__text">Log Out</span>
            </a>
         <?php } else { ?>
            <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a>
            <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small  btn--dark-orange float-left">Sign Up</a>
          <?php }
          ?>
          <!-- Changed from span to a link so that if JavaScript is disabled the user will be carried to a traditional search page when clicking our icon -->
          <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </header>
