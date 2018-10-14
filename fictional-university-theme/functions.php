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
    }
    add_action('after_setup_theme', 'university_features');
?>