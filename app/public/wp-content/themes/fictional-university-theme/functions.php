<?php

require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function university_custom_rest(){
    register_rest_field('post', 'authorName', array(
        'get_callback' => function(){return get_the_author();}
    ));

    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function(){return count_user_posts(get_current_user_id(), 'note');}
    ));
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args=NULL){ // NULL makes $args optional
    if(!$args['title']){
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']){
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if(!$args['photo']){
        if(get_field('page_banner_background_image') AND !is_archive() AND !is_home()  ){
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else{
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
        
    }
    ?>

    <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']
    ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle'];?></p>
      </div>
    </div>  
  </div>

<? }
function university_files(){
    // Install NodeJs and copy over webpack.config.js and package.json, then "npm install". Then "npm run devFast", and when you come to publishing "npm run build" OR you only do this in one step by doing "npm run dev" instead 
 //   wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);

    wp_enqueue_style('google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
 //   wp_enqueue_style('openStreetMapCSS', '//unpkg.com/leaflet@1.7.1/dist/leaflet.css');

 //   wp_enqueue_style('university_main_styles', get_stylesheet_uri());
 // wp_enqueue_script('googleMap', '//unpkg.com/leaflet@1.7.1/dist/leaflet.js', NULL, '1.0', true);

 // check $_SERVER['SERVER_NAME'] == 'localhost'
    if(strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')){
        wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    } else {
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.9678b4003190d41dd438.js'), NULL, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.d07424bb88b864d09ef2.js'), NULL, '1.0', true);
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.d07424bb88b864d09ef2.css'));
    }

    wp_localize_script('main-university-js', 'universityData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest') // secret property generated when you log in for that user session (e.g. needed for deleting posts)
    ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features(){
    // register_nav_menu('headerMenuLocation', 'Header Menu Location');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true); //set true = crop image
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');


function university_adjust_queries($query){
    if(!is_admin() AND is_post_type_archive('program') AND is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }


   if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()){
    $today = date('Ymd');
    $query -> set('meta_key', 'event_date');
    $query -> set('orderby', 'meta_value_num');
    $query -> set('order', 'ASC');
    $query -> set('meta_query', array(
        array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric'
            )
        )
        );
   }
}

add_action('pre_get_posts', 'university_adjust_queries');

// redirect subscriber accounts out of admin and onto homepage
function redirectSubsToFrontend(){
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles)==1 AND $ourCurrentUser->roles[0]=='subscriber'){
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init', 'redirectSubsToFrontend');

// hide admin bar for subsciber accounts
function noSubsAdminBar(){
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles)==1 AND $ourCurrentUser->roles[0]=='subscriber'){
        show_admin_bar(false);
    }
}
add_action('wp_loaded', 'noSubsAdminBar');

// Customise login screen
function ourHeaderUrl(){
    return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'ourHeaderUrl');

function ourLoginCSS(){
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.d07424bb88b864d09ef2.css'));
    wp_enqueue_style('google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');

}
add_action('login_enqueue_scripts','ourLoginCSS'); // login screen needs to load CSS separately compared to rest of website

function ourLoginText(){
    return get_bloginfo('name');
}
add_filter('login_headertext', 'ourLoginText');
 
// Force note posts to be private - much more secure than setting ourNewPost status to "publish" in MyNotes.js since function below happens on server-side which is harder to hack into

function makeNotePrivate($data, $postarr){ // postarr contains slightly different information about post, including id number
    if($data['post_type'] == 'note'){
        if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID'] ){
            die("You have reached your note limit");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']); // strips all html from text field
    }
    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){ // post status is trash when you delete it
        $data['post_status'] = 'private';
    }

    return $data; 
}
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2); // default priority is 10 - and lower the number, the earlier it will run - only useful if you have say multiple filters , and 2 is how many arguments function will take

// exclude files from 'all in one migration' plugin
function ignoreCertainFiles($exclude_filters){
    $exclude_filters[] = 'themes/fictional-university-theme/node_modules';

    return $exclude_filters;

}

add_filter('ai1wm_exclude_content_from_export', 'ignoreCertainFiles');

?>




