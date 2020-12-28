<?php 

function universityRegisterSearch(){ // creates /wp-json/university/v1/search url
    register_rest_route('university/v1', 'search', array( // 1: namespace (e.g. wp/v2), 2. route (e.g. event),  /wp-json/wp/v2/event
        'methods' => WP_REST_SERVER::READABLE, // safer than using 'methods' => GET,
        'callback' => 'universitySearchResults'
    ));

}
add_action('rest_api_init', 'universityRegisterSearch');

function universitySearchResults($data){ // $data argument accesses the data passed into the "callback" in universityRegisterSearch()
    $mainQuery = new WP_Query(array(
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
        's' => sanitize_text_field($data['term']) // s = search and allows for searching: wp-json/university/v1/search?term="meowsalot"
    ));
    
    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()

    );

    while($mainQuery -> have_posts()){
        $mainQuery->the_post();

        if(get_post_type() == 'post' OR get_post_type()=='page'){
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
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // 0 means current post, second arg is size
              ));
            }
     

        if(get_post_type() == 'program'){
            $relatedCampuses = get_field('related_campus');

            // run only if there are related campuses
            if($relatedCampuses){
                foreach($relatedCampuses as $campus){
                    array_push($results['campuses'],array(
                        'title' => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }
            
            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
              ));
            }
        

        if(get_post_type() == 'campus'){
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
              ));
            }
        

        if(get_post_type() == 'event'){
            $eventDate = new DateTime(get_field('event_date'));
                  $description= null;

            if(has_excerpt()){
                $description =  get_the_excerpt();
            }else {
                $description = wp_trim_words(get_the_content(), 18);
            };

            array_push($results['events'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate->format('M'),
                'day' => $eventDate->format('d'),
                'description' => $description
              ));
            }
    }
    
    // if statement needed to ensure that line 108 does not just return all results if the programs array is empty
    if($results['programs']){
        $programsMetaQuery = array('relation' => 'OR');
    
        // loop through each item in programs and grab the professors for that program
        foreach($results['programs'] as $item){
            array_push($programsMetaQuery, array(
                    'post_type' => 'professor',
                    'meta_query' => array(
                    'relation' => 'OR',
                        array(
                            'key' => 'related_programs',
                            'compare' => 'LIKE',
                            'value' => '"'.$item['id'].'"'
                    )
                )
            ));
        }
        
        // search for related professors and events
        $programRelationshipQuery = new WP_Query(
            array(
                'post_type' => array('professor', 'event'),
                'meta_query' => $programsMetaQuery
         ));
    
            
        while($programRelationshipQuery->have_posts()){
            $programRelationshipQuery->the_post();

            // store related events
            if(get_post_type() == 'event'){
                $eventDate = new DateTime(get_field('event_date'));
                      $description= null;
    
                if(has_excerpt()){
                    $description =  get_the_excerpt();
                }else {
                    $description = wp_trim_words(get_the_content(), 18);
                };
    
                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                  ));
                }
            
            // store related professors
            if(get_post_type() == 'professor'){
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // 0 means current post, second arg is size
                  ));
                }

            if(get_post_type() == 'professor'){
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape') // 0 means current post, second arg is size
                    ));
                }
        }
        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR)); // ensures values are unique so results are not duplicated
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR)); // ensures values are unique so results are not duplicated

    }

   

    return $results;             // wordpress automatically converst from php to json format
}
?>