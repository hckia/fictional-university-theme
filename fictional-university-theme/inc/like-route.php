<?php
/* 
PHP doesn't require a closing ?> tag when the file only contains php code
*/
add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes(){
    /* 
        register_rest_route takes 3 arguments
        1. namespace you want to use. For example the namespace of the default WordPress REST API is wp (wp-json/wp/...). Its considered good practice
        to add {namespace}/v{num} to your namespace, so if we modify it we don't pull the rug out from under anyone, we just iterate and include updated
        features
        2. route is the ending point of our... endpoint (API url) (for example in wp-json/wp/v2/posts - posts is the route)
        3. An array that describes what should happen when someone visits this endpoint
    */
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    /* 
        One may to check if the user is logged in is to use current_user_can() and then define what the current user can do, but to show an alternative
        method... 
        is_user_logged_in() <-- since all we care about is if the user is logged in. 
    */
    if(is_user_logged_in()){
        $professor = sanitize_text_field($data['professorId']);
        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type'=> 'like',
            /*we need to use a meta query because we only want to pull in liked posts where the professors 
            liked id matches the professor page */
            'meta_query' => array(
                array(
                    'key' => 'like_professor_id',
                    'compare' => '=',
                    'value' => $professor
                )
            )
        ));
        if($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor'){
            /* 
            wp_insert_post - a WordPress function that allows us to Programmatically create a post.
            */
            return wp_insert_post(array(
                'post_type' => 'like',
                'post_status' => 'publish',
                // WordPress doesn't require a post_title, and usually in this case we wouldn't, but for testing...
                'post_title' => 'Our Second PHP Create Post Test',
                //the post_content won't show in our post because we do not have a editor supported in our Like post type under university-post-types.
                //'post_content' => 'Hello World 1 2 3'
                // creating meta fields with meta_input -> an array with key fields and key values
                'meta_input' => array(
                    'like_professor_id' => $professor,

            )
        ));
        } else {
            die("Invalid Professor Id");
        }
    } else {
        die("Only logged in users can create a like.");
    }
}

function deleteLike($data){
    $likeId = sanitize_text_field($data['like']);
    /* 
        wp_delete_post takes two arguments, the id, and if you want to skip the trash (true/false). It is extremely insecure and should be wrapped in
        a control statement at the very least. 
    */
    // get_post_field takes two arguments, 1st argument what information you want about the post, 2nd is the post ID of the post
    if(get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like'){
        wp_delete_post($likeId, true);
        return 'Congrats, like deleted.';
    } else {
        die("You do not have permission to delete that.");
    }
}