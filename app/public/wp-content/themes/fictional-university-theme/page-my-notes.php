<?php

if(!is_user_logged_in()){
    wp_redirect(esc_url(site_url('/')));
    exit;
}
get_header();
while(have_posts()){
    the_post(); 
    pageBanner();
    ?>

  <div class="container container--narrow page-section">
  <div class="create-note">  
    <h2 class="headline headline--medium"> Create New Note </h2>
    <input class="new-note-title" placeholder="Title">
    <textarea class="new-note-body" placeholder="Your note here..."></textarea>
    <span class="submit-note">Create Note</span>
    <span class="note-limit-message">Note limit reached: delete an existing note to make room for a new one.</span>
  </div>
  <ul class="min-list link-list" id="my-notes">         
    <?php 
        $userNotes = new WP_Query(array(
          'post_type' => 'note',
          'posts_per_page' => -1,
          'author' => get_current_user_id()
        ));

      while($userNotes->have_posts()){
        $userNotes -> the_post(); ?>
        <li data-id="<?php the_ID() ?> ">
          <input readonly class="note-title-field" value="<?php echo esc_attr(get_post_field( 'post_title', get_the_ID(), 'raw' )); // esc_attr or esc_html or other esc_ functions are vital for security when grabbing db data info as it ensures any <script></script> if typed in does not get run ?> "> 
          <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
          <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
          <textarea readonly class="note-body-field"><?php echo esc_textarea(wp_strip_all_tags(get_the_content())); // esc_textarea uses slightly different strategies to remove malicious script text- need to use the appropriate esc_ attribute in the right place ?></textarea>
          <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
        </li>
    
    <?php  }
    ?>

  </ul>
  </div>


<?php
}
get_footer();
?>

<!-- 
How come it is not necessary to grant subscribers permissions for edit_private_notes and delete_private_notes, and only edit_published_notes and delete_published_notes, even if all the notes are private now by default?

The best way I found to understand things is to go into the role editor for Administrators -> Notes and uncheck everything, and slowly just start giving yourself one ability at a time, saving, and then trying to visit the "Notes" list from the left-hand admin sidebar. Before doing this, make sure you have at least one note posted created by a separate subscriber account.
If you give yourself (an admin) the ability to "delete_notes" you can delete notes created by yourself. But you won't be able to delete other people's private notes even if you have edit_notes, edit_others_notes, read_private_notes, and delete_notes.
Then, even if you also give yourself "delete_private_notes" you still won't be able to delete another users note. 
But if you also give yourself "delete_others_notes" then you can.
However, if you keep "delete_others_notes" and remove "delete_private_notes" then you will *not* be able to delete other people's notes assuming they are private. You need the combo of both abilities.
I know that was super impossible to read and make sense of... basically, there are lots of primitive capabilities that are very granular and can be combined with each other.
To answer your question, we didn't need to give subscribers the "delete_private_notes" ability because by default if someone has the "delete_note" ability they can delete notes they are the author of, even if they are private.
The "delete_private_notes" ability is there more for combining with other abilities.
 -->