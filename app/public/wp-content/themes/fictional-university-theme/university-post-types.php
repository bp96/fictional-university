<?php function university_post_types(){
    register_post_type('event', array(
        'supports' => array('title', 'editor', 'excerpt', 'custom-fields'),
        'rewrite' => array('slug' => 'events'),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => "Add New Event",
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-calendar'

    ));
}
add_action('init', 'university_post_types');
?>
<!-- After creating this, the permalinks to these posts may not work, so you have to go into wp-admin, settings, permalinks, and click save -->