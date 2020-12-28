<?php 

get_header(); 
pageBanner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'We have several conveniently located campuses.'
))
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list" style="text-align: center;"> 
        <?php while(have_posts()){ 
            the_post(); ?>
            <li> <h3><a href='<?php the_permalink(); ?>'><?php the_title(); ?></a></h3>
            <?php
            $mapLocation = get_field('map_location'); 
            echo ($mapLocation);  
         ?></li>
         
        <?php }  ?>
    </ul>
</div>

<?php get_footer()

?>