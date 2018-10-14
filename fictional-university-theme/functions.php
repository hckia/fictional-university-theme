<?php

    function university_files() {
        /*
          First line calls our JavaScript files
          Second line calls our google fonts
          Third line calls our font-icons in footer from https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css
          Fourth line calls our stylesheet style.add_cssclass
          to avoid js caching, you can replace our version with microtime() - no strings
          to avoid css caching, add two additional arguments after get_stylesheet_uri() like so - ... get_stylesheet_uri(), NULL, microtime())
          alternatively we could go to Chrome Browser inspector and under Network select Disable cache, but... 
          1. this disables caching on ALL files/sites. 
          2. only works on Chrome
          */
        wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
    }
    /* add_action takes two string arguments */
    /* WordPress lets us give it instructions through this function The first instruction 
    is what TYPE of instructions we are giving it*/
    /* we want to tell it to load a file, so we will use the wp_enqueue_scripts, then the 
    function we created loading those files. WordPress won't call that function until its needed */
    add_action('wp_enqueue_scripts', 'university_files');

    /*  
    Adding other features with our second add_action and university_features function
    */

    function university_features(){
        /* 
        add_theme_support to enable feature for theme. title-tag will add a title tag to the browser's tab
        */
        add_theme_support('title-tag');
        /* 
        register_nav_menu function does exactly what it says, registers a nav menu
        two arguments named (your choice). The first will be used in our header.php file under wp_nav_menu, 
        second one should be human readable as it will appear in WP Admin screen under Appearance > Menu. 
        Without this, Appearance will not have a Menu option.

        Create a new Menu to appear in a specific location (for this example, you can only choose 
        Header Menu Location since thats what we registered). feel free to jump back over to header.php 
        under the nav element to see the end result
        */
        // register_nav_menu('headerMenuLocation', 'Header Menu Location');
        /* 
         The two below will add two more menu locations for our footer menu locations These can be seen
         in the same spot within our dashboard. We will need to add a spot in our footer.php file to
         place both footerLocationOne and footerLocationTWo. 
        */
        // register_nav_menu('footerLocationOne', 'Footer Location One');
        // register_nav_menu('footerLocationTwo', 'Footer Location Two');
    }
    add_action('after_setup_theme', 'university_features');
?>