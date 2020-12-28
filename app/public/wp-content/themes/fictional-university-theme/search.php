<?php 

get_header(); 
pageBanner(array(
    'title' => 'Search Results',
    'subtitle' => 'You searched for "'. esc_html(get_search_query(false)) . '"' // get_search_query() by default will be true and block any malicious script commands in the search but you can also set it to false and escape it as html 
))
?>

<div class="container container--narrow page-section">
    
<?php 
if(have_posts()){
    while(have_posts()){ 
        the_post(); 
        get_template_part('template-parts/content', get_post_type()); // e.g. looks for content-professor file
        } 
    echo paginate_links();
    }
else{
    echo '<h2 class="headline headline--small-plus">No results found.</h2>';
}

get_search_form();
?>

<?php get_footer();

?>