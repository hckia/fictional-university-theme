<?php   
     /* 
        Creating a custom post type. This can be done in themes, or plugins, but best practice is to use it within mu-plugins
        NOTE: When you create a new custom post type you need to resave the permalink structure, even
        if there are no changes. For performance reasons, WordPress only updates the Permalink structure at key moments. To resave the permalink
        structure go to your WordPress Dashboard > Settings > Permalink > Scroll down and Select 'save'.
        Also note that single.php will be used as a custom post type template until you create one yourself. If we want a custom template for 
        our custom post-type, we need a new php file named single-{custom-post-type-key-word}.php. For example in the case of events, this would 
        be single-event.php.This is also truve for archive.php (archive-{custom-post-type-key-word}.php).
    */
    function university_post_types() {
        /* 
        finding the different options can be found here - https://codex.wordpress.org/Function_Reference/register_post_type
            Most of these will be under the labels array, which is a nested associative array
        */
        /* 
        finding the different options can be found here - https://codex.wordpress.org/Function_Reference/register_post_type
            Most of these will be under the labels array, which is a nested associative array
        */
        // Campus post type
        register_post_type('campus', array(
            /* supports will give an events post type features, such as excerpts, etc. The custom fields section allows us to enter a new custom 
            field, which you will see when you edit a custom post. However, if you use a plugin like ACF, you won't need this line. 
            Please refer to advanced-custom-field-notes.md
            */
            'supports' => array('title', 'editor', 'excerpt'),
            /* rewrites our custom post type's slug from campus to campuses, since we would want the archive slug to reflect its plural form */
            'rewrite' => array('slug' => 'campuses'),
            /*creates a archive page (in this case, url/campuses/ or /campuses/ . Might need to refresh permalinks */
            'has_archive' => true,
            /*public => true makes it visible in wp-admin*/
            'public' => true,
            /* by default, every custom post type will appear with the name 'Post', We need to use the labels key, which will be a nested 
            associative array within our register_post types assocative array argument. */
            'labels' => array(
                'name' => 'Campuses',
                /*By default add_new_item will say "Add New Post", we can change that value...*/
                'add_new_item' => 'Add New Campus',
                /*can edit Campuss too */
                'edit_item' => 'Edit Campus',
                /* adds hover feature with options for specific post type. For example without the all_items key, the mouse over 'Campus'
                   would not produce any Campus. However, once we add 'All Campuses' as a value for the all_items key, it will now give us two
                   options 1. 'All Campuses' 2. 'Add New'
                */
                'all_items' => 'All Campuses',
                /* Name for one object of this post type */
                'singular_name' => 'Campus'
            ),
            /* menu_icon allows us to change the default icon within wp-admin. these icons are easily provided by wordpress.org through dashicons
               https://developer.wordpress.org/resource/dashicons/#welcome-write-blog
            */
            'menu_icon' => 'dashicons-location-alt'
        ));
        // event post type
        register_post_type('event', array(
            /* supports will give an events post type features, such as excerpts, etc. The custom fields section allows us to enter a new custom 
            field, which you will see when you edit a custom post. However, if you use a plugin like ACF, you won't need this line. 
            Please refer to advanced-custom-field-notes.md
            */
            'supports' => array('title', 'editor', 'excerpt'),
            /* rewrites our custom post type's slug from event to events, since we would want the archive slug to reflect its plural form */
            'rewrite' => array('slug' => 'events'),
            /*creates a archive page (in this case, url/event/ or /events/ . Might need to refresh permalinks */
            'has_archive' => true,
            /*public => true makes it visible in wp-admin*/
            'public' => true,
            /* by default, every custom post type will appear with the name 'Post', We need to use the labels key, which will be a nested 
            associative array within our register_post types assocative array argument. */
            'labels' => array(
                'name' => 'Events',
                /*By default add_new_item will say "Add New Post", we can change that value...*/
                'add_new_item' => 'Add New Event',
                /*can edit events too */
                'edit_item' => 'Edit Event',
                /* adds hover feature with options for specific post type. For example without the all_items key, the mouse over 'Events'
                   would not produce any event. However, once we add 'All Events' as a value for the all_items key, it will now give us two
                   options 1. 'All Events' 2. 'Add New'
                */
                'all_items' => 'All Events',
                /* Name for one object of this post type */
                'singular_name' => 'Event'
            ),
            /* menu_icon allows us to change the default icon within wp-admin. these icons are easily provided by wordpress.org through dashicons
               https://developer.wordpress.org/resource/dashicons/#welcome-write-blog
            */
            'menu_icon' => 'dashicons-calendar-alt'
        ));

        // Program Post Type

        register_post_type('program', array(
            'supports' => array('title', 'editor'),
            'rewrite' => array('slug' => 'programs'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Programs',
                'add_new_item' => 'Add New Program',
                'edit_item' => 'Edit Program',
                'all_items' => 'All Programs',
                'singular_name' => 'Program'
            ),
            'menu_icon' => 'dashicons-awards'
        ));

        // Professor Post Type

        register_post_type('professor', array(
            /* thumbnail value adds Featured images to our custom post type professor*/
            'supports' => array('title', 'editor', 'thumbnail'),
            'public' => true,
            'labels' => array(
                'name' => 'Professor',
                'add_new_item' => 'Add A New Professor',
                'edit_item' => 'Edit Professor',
                'all_items' => 'All Professors',
                'singular_name' => 'Professor'
            ),
            'menu_icon' => 'dashicons-welcome-learn-more'
        ));
    }
    /* 
        init argument is used for a custom post type
    */
    add_action('init', 'university_post_types');

?>