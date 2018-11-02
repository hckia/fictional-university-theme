  <footer class="site-footer">

    <div class="site-footer__inner container container--narrow">

      <div class="group">

        <div class="site-footer__col-one">
          <h1 class="school-logo-text school-logo-text--alt-color"><a href="<?php echo site_url()?>"><strong>Fictional</strong> University</a></h1>
          <p><a class="site-footer__link" href="#">555.555.5555</a></p>
        </div>

        <div class="site-footer__col-two-three-group">
          <div class="site-footer__col-two">
            <h3 class="headline headline--small">Explore</h3>
            <nav class="nav-list">
              <!-- For custom themes you'll want to control your navigation menu through a unordered 
                   list, but for more generic themes there's another way (see below the ul tag, and check 
                   for more information in functions.php under the university_features function).
                   One benefit of using wp_nav_menu function as you'll see below in this file is that it
                   will "highlight" an active page. this is because it will add 'current-menu-item' 
                   className to our list items dynamically, and then you can control its value through
                   css
              -->
              <ul>
                <li><a href="<?php echo site_url('/about-us')?>">About Us</a></li>
                <li><a href="#">Programs</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Campuses</a></li>
              </ul>
              <!-- our dynamic menu, generated from our WordPress dashboard through our 
                   university-features function in functions.php     
              -->
              <!-- Be sure to remove the spaces after the greater than less than signs, and between the
         question mark and php < ? php 
                wp_nav_menu(array(
                  'theme_location' => 'footerLocationOne'
                ));
              ? > -->
            </nav>
          </div>

          <div class="site-footer__col-three">
            <h3 class="headline headline--small">Learn</h3>
            <nav class="nav-list">
              <!-- For custom themes you'll want to control your navigation menu through a unordered 
                   list, but for more generic themes there's another way (see below the ul tag, and check 
                   for more information in functions.php under the university_features function).
                   One benefit of using wp_nav_menu function as you'll see below in this file is that it
                   will "highlight" an active page. this is because it will add 'current-menu-item' 
                   className to our list items dynamically, and then you can control its value through
                   css
              -->
              <ul>
                <li><a href="#">Legal</a></li>
                <li><a href="<?php echo site_url('/privacy-policy')?>">Privacy</a></li>
                <li><a href="#">Careers</a></li>
              </ul>
              <!-- our dynamic menu, generated from our WordPress dashboard through our 
                   university-features function in functions.php     
              -->
              <!-- Be sure to remove the spaces after the greater than less than signs, and between the
         question mark and php < ? php 
                wp_nav_menu(array(
                  'theme_location' => 'footerLocationTwo'
                ));
              ? > -->
            </nav>
          </div>
        </div>

        <div class="site-footer__col-four">
          <h3 class="headline headline--small">Connect With Us</h3>
          <nav>
            <ul class="min-list social-icons-list group">
              <li><a href="#" class="social-color-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
              <li><a href="#" class="social-color-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
              <li><a href="#" class="social-color-youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
              <li><a href="#" class="social-color-linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
              <li><a href="#" class="social-color-instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            </ul>
          </nav>
        </div>
      </div>

    </div>
  </footer>
  <!-- When a user selects the search icon the overlay below will appear.
  CSS class hook JS will use to make it appear is search-overlay--active 
  -->
  <div class="search-overlay">
      <div class="search-overlay__top">
        <div class="container">
        <!-- we use the i element for font awesome 
        aria-hidden <-- screen reader will not try to read this out to the visitor.
        -->
          <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
          <input type="text" class="search-term" placeholder="what are you looking for?" id="search-term">
          <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>
      </div>
      <div class="container">
        <div id="search-overlay__results">
                
        </div>
      </div>
  </div>
<?php wp_footer(); ?>
    </body>
</html>