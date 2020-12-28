<?php

add_action('rest_api_init', 'universityLikeRoutes');

function universityLikeRoutes(){
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'

    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' =>'DELETE' ,
        'callback' => 'deleteLike'

    ));
};



function createLike($data){
    if(is_user_logged_in()){ // ensure there's a nonce value in Like.js otherwise this will always evaluate to false
        $professor = sanitize_text_field($data['professorId']);  // argument matches that in Like.js file
        
        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'like',
             'meta_query' => array(
               array(
                 'key' => 'liked_professor_id',
                 'compare' => '=',
                 'value' => $professor
               )
             )
          ));

        if($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor' ){
            // create new like post
            // following code returns ID number of the post created
             return wp_insert_post(array(
            'post_type' => 'like',
            'post_status' => 'publish',
            'post_title' => '2nd PHP Test',
            'meta_input' => array(
            'liked_professor_id' => $professor   // key is the same as the advanced custom field name
            )
       ));

        } else{
            die("You cannot like again a professor you've already liked.");
        }
        
        
    } else {
        die("Only logged in users can create a like");
    }


}

function deleteLike($data){
    $likeId = sanitize_text_field($data['like']);

    if(get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like'){
        wp_delete_post($likeId, true); // true indicates delete permanently and bypass trash
        return "Like has been deleted";
    } else {
        die("You do not have permission to delete that");
    }

}
?>