<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );


function custom_scripts() {
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/custom.js',  '', '', true);
 }
add_action( 'wp_enqueue_scripts', 'custom_scripts' );



//MODULE HOMEPAGE CARDS

/*
 * Register Custom Post Types - Homepage Cards
 */
function homepage_cards_custom_post_types() {

    /*
     * Register the new "Homepage Cards" post type
     */
    $homepage_cards_labels = array(
        'name' => __('Homepage cards', 'homepagecards'),
        'singular_name' => __('Homepage cards', 'homepagecards'),
    );
    $homepage_cards_args = array(
        'labels' => $homepage_cards_labels,
        'public' => true,
        'hierarchical' => false,
        'description' => __('Homepage cards'),
        'menu_icon' => 'dashicons-nametag',
        'rewrite' => array( 'slug' => 'homepagecards' ),
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
        'has_archive' => true,
        'show_in_rest' => true,
        'taxonomies' => array( 'homepagecards-category' ),
        'menu_position' => 6,
    );
    register_post_type('homepagecards', $homepage_cards_args );


}
add_action( 'init', 'homepage_cards_custom_post_types' );

/*
 * Register Custom Taxonomies for Homepage Cards
 */
function homepage_cards_custom_taxonomies() {

    /*
     * Register the "Homepage Cards Categories" taxonomy
     */
    $homepagecards_cat_labels = array(
        'name' => _x('Homepage Cards Categories', 'taxonomy general name'),
        'singular-name' => _x('Homepage Cards Category', 'taxonomy singular name'),
    );
    $homepagecards_cat_args = array(
        'labels' => $homepagecards_cat_labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'homepagecards-category',
            'with_front' => false,
            'hierarchical' => true,
        ),
    );
    register_taxonomy('homepagecards-category', 'homepagecards', $homepagecards_cat_args );

}
add_action( 'init', 'homepage_cards_custom_taxonomies' );




//Orbit custom meta box for Events
$prefix = 'orb_';

$meta_box_homepage_cards = array(
    'id' => 'orbit_custom_fields',
    'title' => 'Orbit Homepage Cards Custom Fields',
    'page' => 'homepagecards',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Excerpt',
            'desc' => 'Enter Excerpt.',
            'id' => $prefix . 'excerpt',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Button link',
            'desc' => 'Enter page slug.',
            'id' => $prefix . 'button_link',
            'type' => 'text',
            'std' => ''
        ),       
    )
);


add_action('admin_menu', 'orbit_add_homepage_cards_box');

// Add meta box
function orbit_add_homepage_cards_box() {
    global $meta_box_homepage_cards;

    add_meta_box($meta_box_homepage_cards['id'], $meta_box_homepage_cards['title'], 'orbit_show_homepage_cards_box', $meta_box_homepage_cards['page'], $meta_box_homepage_cards['context'], $meta_box_homepage_cards['priority']);
}


// Callback function to show fields in meta box
function orbit_show_homepage_cards_box() {
    global $meta_box_homepage_cards, $post;



     // Use nonce for verification
    echo '<input type="hidden" name="orbit_meta_box_homepage_cards_event_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($meta_box_homepage_cards['fields'] as $field) {
        // get current post meta data
        $meta_hc = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta_hc ? $meta_hc : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'date':
                echo '<input type="date" name="', $field['id'], '" id="', $field['id'], '" value="', $meta_hc ? $meta_hc : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta_hc ? $meta_hc : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option ', $meta_hc == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta_hc == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta_hc ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '</td><td>',
            '</td></tr>';
    }

    echo '</table>';
}

add_action('save_post', 'orbit_save_homepage_cards_data');

// Save data from meta box
function orbit_save_homepage_cards_data($post_id) {
    global $meta_box_homepage_cards;

    // verify nonce
    if (!wp_verify_nonce($_POST['orbit_meta_box_homepage_cards_event_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    foreach ($meta_box_homepage_cards['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}


//Module 12 - Homepage cards category filters

//Shortcode to show dropdown filters [homepage_posts]
function homepage_cards_category(){
   $cards_filter_content .= '<form action="'.site_url().'/wp-admin/admin-ajax.php" method="POST" id="filter-cards">';

        if( $terms_ev = get_terms( array( 'taxonomy' => 'homepagecards-category', 'orderby' => 'name' ) ) ) : 
    
            $cards_filter_content .=  '<select id="homepagecards-category-select" name="homepagecardsfilter">';
            foreach ( $terms_ev as $term_ev ) :
                $cards_filter_content .=  '<option value="' . $term_ev->term_id . '">' . $term_ev->name . '</option>'; // ID of the category as the value of an option
            endforeach;
            $cards_filter_content .=  '</select>';
        endif;
   $cards_filter_content .= '<input type="hidden" name="action" value="myfilter_homepagecards">';
   $cards_filter_content .= '</form>';
   //$cards_filter_content .= '<div id="homepagecards-response"></div>';
    
    return $cards_filter_content;

}
add_shortcode('homepagecards_filters','homepage_cards_category');

//show cards
function show_homepage_cards(){
    $cards_content .= '<h3 id="homepagecards-title"></h3>';
    $cards_content .= '<div id="homepagecards-response"></div>';
    return $cards_content;
}
add_shortcode('homepagecards_cards','show_homepage_cards');


//Show posts from selected category - with ajax call
add_action('wp_ajax_myfilter_homepagecards', 'orbit_homepagecards_filter_function');
add_action('wp_ajax_nopriv_myfilter_homepagecards', 'orbit_homepagecards_filter_function');

function orbit_homepagecards_filter_function(){
    $args_cards = array(
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => '3',
    );
 
    // for taxonomies / categories
    if( isset( $_POST['homepagecardsfilter'] ) )
        $args_cards['tax_query'] = array(
            array(
                'taxonomy' => 'homepagecards-category',
                'field' => 'id',
                'terms' => $_POST['homepagecardsfilter']
            )
        );

    $query_cards = new WP_Query( $args_cards );
    if( $query_cards->have_posts() ) :
        echo '<div class="flex-container cards">';
        while( $query_cards->have_posts() ): $query_cards->the_post();
            echo '<div class="card">';
            if(has_post_thumbnail()){
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                echo '<div class="rounded background-image" style="background-image: url('.$thumb[0].');"></div>';
                
             }
            
            echo '<h4>' . $query_cards->post->post_title . '</h4>';
            echo '<p>'.get_post_meta(get_the_ID(), 'orb_excerpt', TRUE) .'</p>';
            ?>
            <button onclick='window.location.href="<?php echo get_post_meta(get_the_ID(), 'orb_button_link', TRUE) ?>";'>Find out more</button>
            <?php 
            
            echo '</div>';
        endwhile;
        wp_reset_postdata();
        echo '</div>';
    else :
        echo do_shortcode("[default_cards]");
    endif;
    die();
}


//Shortcode for default Homepage cards posts from all categories
function default_homepagecards_posts(){

    $terms_homepagecards_def = get_terms('homepagecards-category');
    $term_homepagecards_ids = wp_list_pluck( $terms_homepagecards_def, 'term_id' );
    $args_homepagecards_def = array(
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => '-1',
    );
    $args_homepagecards_def['tax_query'] = array(
            array(
                'taxonomy' => 'homepagecards-category',
                'field' => 'term_id',
                'terms' => $term_homepagecards_ids
            )
        );

    $query_homepagecards_def = new WP_Query( $args_homepagecards_def );
    
    if( $query_homepagecards_def->have_posts() ) :
        echo '<div id="cards-homepage" class="cards">';
        while( $query_homepagecards_def->have_posts() ): $query_homepagecards_def->the_post();
            echo '<div class="card">';
            if(has_post_thumbnail()){
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                echo '<div class="rounded background-image" style="background-image: url('.$thumb[0].');"></div>';
                               
             }
            $date = get_post_meta(get_the_ID(), 'orb_event_date', TRUE);
            echo '<h4>' . $query_homepagecards_def->post->post_title . '</h4>';
            echo '<p>'.get_post_meta(get_the_ID(), 'orb_excerpt', TRUE) .'</p>';
            ?>
            <button onclick='window.location.href="<?php echo get_post_meta(get_the_ID(), 'orb_button_link', TRUE) ?>";'>Click Here</button>
            <?php 
            
            echo '</div>'; // end .card
        endwhile;
        wp_reset_postdata();
        echo '</div>'; // end .cards
    endif;
    die();

}
add_shortcode('default_cards', 'default_homepagecards_posts');


/*
 * Register Custom Post Types - Staff Members
 */
function orbit_register_custom_post_types() {

    /*
     * Register the new "Staff" post type
     */
    $staff_labels = array(
        'name' => __('Staff Member', 'staff'),
        'singular_name' => __('Staff Member post', 'staff'),
    );
    $staff_args = array(
        'labels' => $staff_labels,
        'public' => true,
        'hierarchical' => false,
        'description' => __('Orbit Staff'),
        'menu_icon' => 'dashicons-businessperson',
        'rewrite' => array( 'slug' => 'staff' ),
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
        'has_archive' => true,
        'show_in_rest' => true,
        'taxonomies' => array( 'staff-categories' ),
        'menu_position' => 6,
    );
    register_post_type('staff', $staff_args );


}
add_action( 'init', 'orbit_register_custom_post_types' );

/*
 * Register Custom Taxonomies
 */
function orbit_register_custom_taxonomies() {

    /*
     * Register the "Staff Categories" taxonomy
     */
    $staff_cat_labels = array(
        'name' => _x('Staff Categories', 'taxonomy general name'),
        'singular-name' => _x('Staff Category', 'taxonomy singular name'),
    );
    $staff_cat_args = array(
        'labels' => $staff_cat_labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'staff-category',
            'with_front' => false,
            'hierarchical' => true,
        ),
    );
    register_taxonomy('staff-category', 'staff', $staff_cat_args );

}
add_action( 'init', 'orbit_register_custom_taxonomies' );




//Orbit custom meta box for Staff
$prefix = 'orb_';

$meta_box = array(
    'id' => 'orbit_custom_fields',
    'title' => 'Orbit Staff Custom Fields',
    'page' => 'staff',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Role',
            'desc' => 'Enter role.',
            'id' => $prefix . 'role',
            'type' => 'text',
            'std' => ''
        ),
        /*array(
            'name' => 'Phone number',
            'desc' => 'Enter phone number',
            'id' => $prefix . 'phone',
            'type' => 'text',
            'std' => ''
        ),*/
        array(
            'name' => 'Email address',
            'desc' => 'Enter email',
            'id' => $prefix . 'email',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'LinkedIn link',
            'desc' => 'Enter LinkedIn link',
            'id' => $prefix . 'linkedin',
            'type' => 'text',
            'std' => ''
        ),
       
    )
);


add_action('admin_menu', 'orbit_add_box');

// Add meta box
function orbit_add_box() {
    global $meta_box;

    add_meta_box($meta_box['id'], $meta_box['title'], 'orbit_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}


// Callback function to show fields in meta box
function orbit_show_box() {
    global $meta_box, $post;



     // Use nonce for verification
    echo '<input type="hidden" name="orbit_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '</td><td>',
            '</td></tr>';
    }

    echo '</table>';
}

add_action('save_post', 'orbit_save_data');

// Save data from meta box
function orbit_save_data($post_id) {
    global $meta_box;

    // verify nonce
    if (!wp_verify_nonce($_POST['orbit_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}


//Module 11 - Staff members category/city filters

//Shortcode to show dropdown filters [staff_posts]
function staff_category(){
    $content = '';
    $content .= '<form action="'. site_url().'/wp-admin/admin-ajax.php" method="POST" id="filter">';
   
        if( $terms = get_terms( array( 'taxonomy' => 'staff-category', 'orderby' => 'name' ) ) ) : 
    
            $content .= '<select id="category-select" name="categoryfilter"><option value="" selected>Our Staff</option>';
            foreach ( $terms as $term ) :
                $content .= '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
            endforeach;
            $content .= '</select>';
        endif;
    
    $content .= '<input type="hidden" name="action" value="myfilter">';
    $content .= '</form>';

    $content .= '<div id="response"></div>';

    return $content;
}
add_shortcode('staff_posts','staff_category');


//Show posts from selected category - with ajax call
add_action('wp_ajax_myfilter', 'orbit_filter_function');
add_action('wp_ajax_nopriv_myfilter', 'orbit_filter_function');

function orbit_filter_function(){
    
    $args = array(
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => '-1',
    );
 
    // for taxonomies / categories
    if( isset( $_POST['categoryfilter'] ) )
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'staff-category',
                'field' => 'id',
                'terms' => $_POST['categoryfilter']
            )
        );

    $query = new WP_Query( $args );
    echo '<div class="flex-container cards">';
    if( $query->have_posts() ) :
        echo '<div id="cards-staff" class="cards">';
        while( $query->have_posts() ): $query->the_post();
            echo '<div class="card">';
            if(has_post_thumbnail()){
                //the_post_thumbnail('thumbnail');
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                //echo $thumb[0]; // thumbnail url
                echo '<div class="rounded background-image" style="background-image: url('.$thumb[0].');"></div>';

                
             }
            echo '<p><strong>' . $query->post->post_title . '</strong><br>';
            echo get_post_meta(get_the_ID(), 'orb_role', TRUE) .'<br>';
            echo '<a href=mailto:"'.get_post_meta(get_the_ID(), 'orb_email', TRUE).'">Get in touch > </a><br>';
            echo '<a href = "'.get_post_meta(get_the_ID(), 'orb_linkedin', TRUE).'"><i class="fab fa-linkedin"></i> Connect ></a></p>';
            echo '</div>'; // end .card
        endwhile;
        wp_reset_postdata();
        echo '</div>'; // end .cards
    else :
        echo do_shortcode("[default_staff]");
    endif;
    echo '</div>';
    die();
}

//Shortcode for first/default random posts from all categories
function default_staff_posts(){

    $terms_def = get_terms('staff-category');
    $term_ids = wp_list_pluck( $terms_def, 'term_id' );
    $args_def = array(
        'orderby'        => 'rand',
        'posts_per_page' => '4',
    );
    $args_def['tax_query'] = array(
            array(
                'taxonomy' => 'staff-category',
                'field' => 'term_id',
                'terms' => $term_ids
            )
        );

    $query_def = new WP_Query( $args_def );
    
    if( $query_def->have_posts() ) :
        echo '<div id="cards-staff" class="cards">';
        while( $query_def->have_posts() ): $query_def->the_post();
            echo '<div class="card">';
            if(has_post_thumbnail()){
                //the_post_thumbnail('thumbnail');
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                //echo $thumb[0]; // thumbnail url
                echo '<div class="rounded background-image" style="background-image: url('.$thumb[0].');"></div>';

                
            }
            echo '<p><strong>' . $query_def->post->post_title . '</strong><br>';
            echo get_post_meta(get_the_ID(), 'orb_role', TRUE) .'<br>';
            echo '<a href=mailto:"'.get_post_meta(get_the_ID(), 'orb_email', TRUE).'">Get in touch ></a><br>';
            echo '<a href = "'.get_post_meta(get_the_ID(), 'orb_linkedin', TRUE).'"><i class="fab fa-linkedin"></i> Connect ></a></p>';
            echo '</div>'; // end .card
        endwhile;
        wp_reset_postdata();
        echo '</div>'; // end .cards
    endif;
    die();

}
add_shortcode('default_staff', 'default_staff_posts');



//MODULE 12 - EVENT FILTERS

/*
 * Register Custom Post Types - Events
 */
function orbit_events_custom_post_types() {

    /*
     * Register the new "Events" post type
     */
    $events_labels = array(
        'name' => __('Events', 'event'),
        'singular_name' => __('Event post', 'event'),
    );
    $event_args = array(
        'labels' => $events_labels,
        'public' => true,
        'hierarchical' => false,
        'description' => __('Events'),
        'menu_icon' => 'dashicons-calendar',
        'rewrite' => array( 'slug' => 'event' ),
        'capability_type' => 'post',
        'supports' => array('title', 'editor', 'revisions', 'thumbnail'),
        'has_archive' => true,
        'show_in_rest' => true,
        'taxonomies' => array( 'event-categories' ),
        'menu_position' => 6,
    );
    register_post_type('event', $event_args );


}
add_action( 'init', 'orbit_events_custom_post_types' );

/*
 * Register Custom Taxonomies for Events
 */
function orbit_event_custom_taxonomies() {

    /*
     * Register the "Event Categories" taxonomy
     */
    $event_cat_labels = array(
        'name' => _x('Event Categories', 'taxonomy general name'),
        'singular-name' => _x('Event Category', 'taxonomy singular name'),
    );
    $event_cat_args = array(
        'labels' => $event_cat_labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'event-category',
            'with_front' => false,
            'hierarchical' => true,
        ),
    );
    register_taxonomy('event-category', 'event', $event_cat_args );

}
add_action( 'init', 'orbit_event_custom_taxonomies' );




//Orbit custom meta box for Events
$prefix = 'orb_';

$meta_box_events = array(
    'id' => 'orbit_custom_fields',
    'title' => 'Orbit Events Custom Fields',
    'page' => 'event',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Place',
            'desc' => 'Enter event place.',
            'id' => $prefix . 'event_place',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Date',
            'desc' => 'Enter event date.',
            'id' => $prefix . 'event_date',
            'type' => 'date',
            'std' => ''
        ),
       
    )
);


add_action('admin_menu', 'orbit_add_event_box');

// Add meta box
function orbit_add_event_box() {
    global $meta_box_events;

    add_meta_box($meta_box_events['id'], $meta_box_events['title'], 'orbit_show_event_box', $meta_box_events['page'], $meta_box_events['context'], $meta_box_events['priority']);
}


// Callback function to show fields in meta box
function orbit_show_event_box() {
    global $meta_box_events, $post;



     // Use nonce for verification
    echo '<input type="hidden" name="orbit_meta_box_events_event_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($meta_box_events['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'date':
                echo '<input type="date" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '</td><td>',
            '</td></tr>';
    }

    echo '</table>';
}

add_action('save_post', 'orbit_save_event_data');

// Save data from meta box
function orbit_save_event_data($post_id) {
    global $meta_box_events;

    // verify nonce
    if (!wp_verify_nonce($_POST['orbit_meta_box_events_event_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    foreach ($meta_box_events['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}


//Module 12 - Events category/city filters

//Shortcode to show dropdown filters [event_posts]
function event_category(){
   $content .= '<form action="'.site_url().'/wp-admin/admin-ajax.php" method="POST" id="filter-event">';

        if( $terms_ev = get_terms( array( 'taxonomy' => 'event-category', 'orderby' => 'name' ) ) ) : 
    
            $content .=  '<select id="event-category-select" name="categoryfilter_event"><option value="" selected>All Events</option>';
            foreach ( $terms_ev as $term_ev ) :
                $content .=  '<option value="' . $term_ev->term_id . '">' . $term_ev->name . '</option>'; // ID of the category as the value of an option
            endforeach;
            $content .=  '</select>';
        endif;
   $content .= '<input type="hidden" name="action" value="myfilter_event">';
   $content .= '</form>';
   $content .= '<div id="event-response"></div>';
    
    return $content;

}
add_shortcode('event_posts','event_category');


//Show posts from selected category - with ajax call
add_action('wp_ajax_myfilter_event', 'orbit_filter_event_function');
add_action('wp_ajax_nopriv_myfilter_event', 'orbit_filter_event_function');

function orbit_filter_event_function(){
    $args_ev = array(
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => '-1',
    );
 
    // for taxonomies / categories
    if( isset( $_POST['categoryfilter_event'] ) )
        $args_ev['tax_query'] = array(
            array(
                'taxonomy' => 'event-category',
                'field' => 'id',
                'terms' => $_POST['categoryfilter_event']
            )
        );

    $query_ev = new WP_Query( $args_ev );
    if( $query_ev->have_posts() ) :
        echo '<div class="flex-container cards">';
        while( $query_ev->have_posts() ): $query_ev->the_post();
            echo '<div class="card">';
            if(has_post_thumbnail()){
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                echo '<div class="rounded background-image" style="background-image: url('.$thumb[0].');">';
                echo '<a href="'.get_permalink( $query_ev->post->ID).'">';
                the_post_thumbnail('thumbnail');
                echo '</a></div>';                
             }
            
            $date = get_post_meta(get_the_ID(), 'orb_event_date', TRUE);
            echo '<h4><a href="'.get_permalink( $query_ev->post->ID).'">' . $query_ev->post->post_title . '</a></h4>';
            echo '<span>'.get_post_meta(get_the_ID(), 'orb_event_place', TRUE) .'<br>';
            echo date('d/m/Y', strtotime($date)) .'</span>';
            echo '</div>';
        endwhile;
        wp_reset_postdata();
        echo '</div>';
    else :
        echo do_shortcode("[default_events]");
    endif;
    die();
}


//Shortcode for first/default random posts from all categories
function default_event_posts(){

    $terms_event_def = get_terms('event-category');
    $term_event_ids = wp_list_pluck( $terms_event_def, 'term_id' );
    $args_event_def = array(
        'order' => 'ASC',
        'orderby' => 'title',
        'posts_per_page' => '-1',
    );
    $args_event_def['tax_query'] = array(
            array(
                'taxonomy' => 'event-category',
                'field' => 'term_id',
                'terms' => $term_event_ids
            )
        );

    $query_event_def = new WP_Query( $args_event_def );
    
    if( $query_event_def->have_posts() ) :
        echo '<div id="cards-event" class="cards">';
        while( $query_event_def->have_posts() ): $query_event_def->the_post();
            echo '<div class="card">';
            if(has_post_thumbnail()){
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                echo '<div class="rounded background-image" style="background-image: url('.$thumb[0].');">';
                echo '<a href="'.get_permalink( $query_ev->post->ID).'">';
                the_post_thumbnail('thumbnail');
                echo '</a></div>';                
             }
            $date = get_post_meta(get_the_ID(), 'orb_event_date', TRUE);
            echo '<h4><a href="'.get_permalink( $query_event_def->post->ID).'">' . $query_event_def->post->post_title . '</a></h4>';
            echo '<span>'.get_post_meta(get_the_ID(), 'orb_event_place', TRUE) .'<br>';
            echo date('d/m/Y', strtotime($date)) .'</span>';
            echo '</div>'; // end .card
        endwhile;
        wp_reset_postdata();
        echo '</div>'; // end .cards
    endif;
    die();

}
add_shortcode('default_events', 'default_event_posts');

//add excerpt for the post
add_post_type_support( 'page', 'excerpt' );


