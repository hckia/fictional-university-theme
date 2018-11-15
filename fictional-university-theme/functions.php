<?php
require __DIR__ . '/vendor/autoload.php';
require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

/******** 
Modify WordPress REST API
*********/
function university_custom_rest(){
    /*
        register_rest_field takes three args
        1st arg - the post type you want to customize
        2nd arg - what you want to name the new field
        3rd arg - an array on how we want to manage this field
    */
    register_rest_field('post','authorName', array(
        'get_callback' => function() {return get_the_author();}
    ));

    register_rest_field('note','userNoteCount', array(
        'get_callback' => function() {return count_user_posts(get_current_user_id(),'note');}
    ));
    /*You can add as many of these as you want to modify the native REST API, For example... 
    
    register_rest_field('post','perfectlyCroppedImageURL', array(
        'get_callback' => function() {return ...}
    ));
    */
}

add_action('rest_api_init', 'university_custom_rest');
    /* 
    the PageBanner function was added midway through this tutorial. It takes an argument that's an associative array so that each of our page
    templates can pass their own values. This serves as a middle ground between redundant code, and non-specific values. The first example of
    this can be found in page.php
    $args = NULL makes the argument optional
    */
    function pageBanner($args = NULL) {
        if(!$args['title']){
            $args['title'] = get_the_title();
        }
        if(!$args['subtitle']){
            $args['subtitle'] = get_field('page_banner_subtitle');
        }
        if(!$args['photo']){
            if(get_field('page_banner_background_image')){
                $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
            } else {
                $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
            }
        }
        ?>
        <div class="page-banner">
            <!-- we can do $pageBannerImage['url'], but that would give us the massive uncropted default image. 
            echo $pageBannerImage['sizes']['pageBanner'] would be better.
            -->
            <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
            <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
            </div>  
        </div>
    <?php }

    function university_files() {
        /*
          First Line embeds js file from Google's server
          Second line calls our JavaScript files
          Third line calls our google fonts
          Fourth line calls our font-icons in footer from https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css
          Fifth line calls our stylesheet style.add_cssclass
          Sixth: wp_localize_script takes three arguments 1st is name or $handle 2nd declare a variable 3rd an array of data to be available. In this
          case we will be adding root_url to make our sites code more dynamic, not requiring us to hardcode our root url.
          to avoid js caching, you can replace our version with microtime() - no strings
          to avoid css caching, add two additional arguments after get_stylesheet_uri() like so - ... get_stylesheet_uri(), NULL, microtime())
          alternatively we could go to Chrome Browser inspector and under Network select Disable cache, but... 
          1. this disables caching on ALL files/sites. 
          2. only works on Chrome
          */
        $mapsUrl =  '//maps.googleapis.com/maps/api/js?key='. getenv("GOOGLE_MAPS_API");
        wp_enqueue_script('googleMap', $mapsUrl , NULL, '1.0', true);
        wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        // will only load CSS on the frontend, see function towards the end of the functions.php file for how to load it in other places.
        wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
        wp_localize_script('main-university-js', 'universityData', array(
            'root_url' => get_site_url(),
            /* everytime we successfully log into WordPress there will be a secret property we can see if we check the view source of the page named 
            'nonce' that equals a randomly generated number that WordPress generated for our users session.
            example: var universityData = {"root_url":"http:\/\/localhost:3000","nonce":"563825"};
            */
            'nonce' => wp_create_nonce('wp_rest')
        ));
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

        /* enables featured images for post types (blog posts), but we need to do additional work for custom post types. university-post-types.php 
        under Professor has an example of adding support for featured images (the string is called 'thumbnail')*/
        add_theme_support('post-thumbnails');
        /* create image sizes 
        first argument is the title for our size
        second is width
        third is height
        fourth is cropped (true or false)
        */
        add_image_size('professorLandscape', 400, 260, true);
        add_image_size('professorPortrait', 480, 650, true);
        add_image_size('pageBanner', 1500,350, true);
    }
    add_action('after_setup_theme', 'university_features');

    /* 
        Creating a custom post type. This can be done in themes, or plugins, but best practice is to use it within mu-plugins
        NOTE: When you create a new custom post type you need to resave the permalink structure, even
        if there are no changes. For performance reasons, WordPress only updates the Permalink structure at key moments. To resave the permalink
        structure go to your WordPress Dashboard > Settings > Permalink > Scroll down and Select 'save'.
        Also note that single.php will be used as a custom post type template until you create one yourself. If we want a custom template for our
        custom post-type, we need a new php file named single-{custom-post-type-key-word}.php. For example in the case of events, this would be
        single-event.php. This is also truve for archive.php (archive-{custom-post-type-key-word}.php).
    */
    
    /* 
        end of custom post type. If we were to delete the custom post type data above and we tried to access a post type 
        page we would receive "invalid post type". That is, unless we put it in plugins, or mu-plugins. In this case we have moved the custom
        post type to mu-plugins/university-post-type.php
    */
    /* 
        pre_get_posts gives a change to adjust the query before an event. So we will pass our function the $query variable, which will contain 
        that information.
    */
    function university_adjust_queries($query) {
        /* 
            without the control statements below, (for example) $query->set('post_per_page', 1) this function would effect EVERY post/page, including the 
            dashboard.
        */
        if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()){
            $query->set('orderby','title');
            $query->set('order','ASC');
            $query->set('posts_per_page',-1);
        }
        /* ensure that ALL(-1) pins will show at /campuses/ google map, and not just few  */
        if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()){
            $query->set('posts_per_page',-1);
        }
        /* the query->$is_main_query() checks to make sure we don't manipulate custom queries */
        if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
            /* we don't actually want to manipulate post_per_page, but we can use our custom Query from front-page.php starting around line 30+ to
            50 as a reference. 
            For example, we had 'meta_key' => 'event_date', here we would write set('meta_key', 'event_date')
            */
            $today = date('Ymd');
            $query->set('meta_key', 'event_date');
            $query->set('orderby', 'meta_value_num');
            $query->set('order','ASC');
            $query->set('meta_query', array(
              array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                /* type doesn't have to be here, but lets be specific since we were with meta_value_num */
                'type' => 'numeric'
              )
              ));
        }
    }

    add_action('pre_get_posts', 'university_adjust_queries');

// add api for google maps
    function universityMapKey($api) {
        $api['key'] = getenv('GOOGLE_MAPS_API');
        return $api;
    }

    add_filter('acf/fields/google_map/api', 'universityMapKey');

// Redirect subscriber accounts out of admi na nd onto homepage

function redirectSubsToFrontEnd() {
    $ourCurrentUser = _wp_get_current_user();
    //count roles in count, which counts the indeces in an array
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber'){
        wp_redirect(site_url('/'));
        // tells PHP stop spinning its gears
        exit;
    }
}

// hide admin bar from subscribers

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $ourCurrentUser = _wp_get_current_user();
    //count roles in count, which counts the indeces in an array
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber'){
        show_admin_bar(false);
    }
}

add_action('admin_init', 'redirectSubsToFrontEnd');

// customize login screen
// css styles are under fictional/university-theme/css/modules/login.css for examples of how to tinker with WordPress login.

function ourHeaderUrl(){
    return esc_url(site_url('/'));
}

add_filter('login_headerurl', 'ourHeaderUrl');

function ourLoginCSS(){
        wp_enqueue_style('university_main_styles', get_stylesheet_uri());
        wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

//edit WordPress header title on login

function ourLoginTitle() {
    // comment out return line to see example of image replacement.
    return get_bloginfo('name');
}

add_filter('login_headertitle', 'ourLoginTitle');

// Force note posts to be private

function makeNotePrivate($data, $postarr){
    //cleaning our data of any code including html
    if($data['post_type'] == 'note'){
        //this will only run if user has reached its max, and the post does not have an ID
        if(count_user_posts(get_current_user_id(),'note') > 3 AND !$postarr['ID']){
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
        $data['post_status'] ="private";
    }
    return $data;
}

//10 is the priority of a callback function. if we had two add_filters one after the other, the one with the lowest number will run first.
//last arg means we want our function to work with two parameters.
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

?>