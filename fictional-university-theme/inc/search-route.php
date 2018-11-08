<?php


add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
    /* 
        register_rest_route takes 3 arguments
        1. namespace you want to use. For example the namespace of the default WordPress REST API is wp (wp-json/wp/...). Its considered good practice
        to add {namespace}/v{num} to your namespace, so if we modify it we don't pull the rug out from under anyone, we just iterate and include updated
        features
        2. route is the ending point of our... endpoint (API url) (for example in wp-json/wp/v2/posts - posts is the route)
        3. An array that describes what should happen when someone visits this endpoint
    */
    register_rest_route('university/v1', 'search', array(
        /* 
            methods is for GET, POST, PUT, DELETE (CRUD) - basically what methods this endpoint will allow. 
            For example, if we want to allow consumer to GET, we could write GET, but depending on the server we may want something mnore dynamic like... 
            WP_REST_SERVER::READABLE which is the WordPress equivalent of 'GET'
        */
        'methods' => WP_REST_SERVER::READABLE,
        /* 
            we set this to a functio and whatever it returns will be our JSON data
        */
        'callback' => 'universitySearchResults'
    ));
}
/* 
the data argument here can be used
*/
function universitySearchResults($data) {
    $mainQuery = new WP_Query(array(
        /* 
        you can provide post_type a single post_type such as post or professor, or pass it an associative
        array with multiple post types...
        */
        'post_type' => array(
            'post',
            'page',
            'professor',
            'program',
            'campus',
            'event'
        ),
        // s in WP_Query stands for search.
        // sanitize_text_field is just an extra security precaution against injections
        's' => sanitize_text_field($data['term'])
    ));

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'campuses' => array(),
        'events' => array()
    );

    while($mainQuery->have_posts()){
        $mainQuery->the_post();
        if(get_post_type() == 'post' OR get_post_type() == 'page'){
            array_push($results['generalInfo'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'postType' => get_post_type(),
                        'authorName' => get_the_author()
                    ));
        }
        if(get_post_type() == 'professor'){
            array_push($results['professors'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'postType' => get_post_type(),
                        'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                    ));
        }
        if(get_post_type() == 'program'){
            $relatedCampuses = get_field('related_campus');

            if($relatedCampuses){
                foreach($relatedCampuses as $campus){
                    array_push($results['campuses'], array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }

            array_push($results['programs'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'postType' => get_post_type(),
                        'id' => get_the_id()
                    ));
        }
        if(get_post_type() == 'campus'){
            array_push($results['campuses'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'postType' => get_post_type(),
                        'authorName' => get_the_author()
                    ));
        }
        if(get_post_type() == 'event'){
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){ 
                $description = get_the_excerpt();
                }else {
                $description = wp_trim_words(get_the_content(), 18);
            }
            array_push($results['events'], array(
                        'title' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'month' => $eventDate->format('M'),
                        'day' => $eventDate->format('d'),
                        'description'=> $description
                    ));
        }
    }
    // will only do the rest if programs doesn't return an empty query
    if($results['programs']){
        $programsMetaQuery = array(// tells the query not to match all filters 
            'relation' => 'OR');
        foreach($results['programs'] as $result){
            array_push($programsMetaQuery, array(
                //can nest arrays to create filters within meta_query
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"'. $result['id']. '"'
            ));
        }
        // single-program.php has a similar example to this on line 101 explaining this in more detail
        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $programsMetaQuery
        ));

        while($programRelationshipQuery->have_posts()) {
            $programRelationshipQuery->the_post();
            if(get_post_type() == 'event'){
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if(has_excerpt()){ 
                    $description = get_the_excerpt();
                    }else {
                    $description = wp_trim_words(get_the_content(), 18);
                }
                array_push($results['events'], array(
                            'title' => get_the_title(),
                            'permalink' => get_the_permalink(),
                            'month' => $eventDate->format('M'),
                            'day' => $eventDate->format('d'),
                            'description'=> $description
                        ));
            }
            if(get_post_type() == 'professor'){
                array_push($results['professors'], array(
                            'title' => get_the_title(),
                            'permalink' => get_the_permalink(),
                            'postType' => get_post_type(),
                            'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
                        ));
            }       
        }
        // remove duplicate results
        // array_values removes the integer keys generated by array_unique's SORT_REGULAR arg.
        // array_unique takes two args. 1 arg = array. 2 arg = method for sorting
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }
    return $results;
}