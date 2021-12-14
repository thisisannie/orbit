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
        'posts_per_page' => '4',
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
    echo '<div class="flex-container">';
    if( $query->have_posts() ) :
        while( $query->have_posts() ): $query->the_post();
            echo '<div>';
            if(has_post_thumbnail()){
                //the_post_thumbnail('thumbnail');
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                //echo $thumb[0]; // thumbnail url
                echo '<div style="width: 200px; height: 200px; background-image: url('.$thumb[0].');background-repeat: no-repeat;background-size: cover;"></div>';

                
             }
            echo '<h4>' . $query->post->post_title . '</h4>';
            echo '<span>'.get_post_meta(get_the_ID(), 'orb_role', TRUE) .'<br>';
            /*echo get_post_meta(get_the_ID(), 'orb_phone', TRUE) .'<br>';*/
            echo '<a href=mailto:"'.get_post_meta(get_the_ID(), 'orb_email', TRUE).'">Get in touch > </a><br>';
            echo '<a href = "'.get_post_meta(get_the_ID(), 'orb_linkedin', TRUE).'"><i class="fab fa-linkedin-in"></i> Connect ></a></span>';
            echo '</div>';
        endwhile;
        wp_reset_postdata();
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
        while( $query_def->have_posts() ): $query_def->the_post();
            echo '<div>';
            if(has_post_thumbnail()){
                //the_post_thumbnail('thumbnail');
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                //echo $thumb[0]; // thumbnail url
                echo '<div style="width: 200px; height: 200px; background-image: url('.$thumb[0].');background-repeat: no-repeat;background-size: cover;"></div>';

                
             }
            echo '<h4>' . $query_def->post->post_title . '</h4>';
            echo '<span>'.get_post_meta(get_the_ID(), 'orb_role', TRUE) .'<br>';
            /*echo get_post_meta(get_the_ID(), 'orb_phone', TRUE) .'<br>';*/
           echo '<a href=mailto:"'.get_post_meta(get_the_ID(), 'orb_email', TRUE).'">Get in touch > </a><br>';
            echo '<a href = "'.get_post_meta(get_the_ID(), 'orb_linkedin', TRUE).'"><i class="fab fa-linkedin-in"></i> Connect ></a></span>';
            echo '</div>';
        endwhile;
        wp_reset_postdata();
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
    echo '<div class="flex-container">';
    if( $query_ev->have_posts() ) :
        while( $query_ev->have_posts() ): $query_ev->the_post();
            echo '<div>';
            if(has_post_thumbnail()){
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                echo '<div style="width: 200px; height: 200px; background-image: url('.$thumb[0].');background-repeat: no-repeat;background-size: cover;">';
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
    else :
        echo do_shortcode("[default_events]");
    endif;
    echo '</div>';
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
        while( $query_event_def->have_posts() ): $query_event_def->the_post();
            echo '<div>';
            if(has_post_thumbnail()){
                $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
                echo '<div style="width: 200px; height: 200px; background-image: url('.$thumb[0].');background-repeat: no-repeat;background-size: cover;">';
                echo '<a href="'.get_permalink( $query_ev->post->ID).'">';
                the_post_thumbnail('thumbnail');
                echo '</a></div>';                
             }
            $date = get_post_meta(get_the_ID(), 'orb_event_date', TRUE);
            echo '<h4><a href="'.get_permalink( $query_event_def->post->ID).'">' . $query_event_def->post->post_title . '</a></h4>';
            echo '<span>'.get_post_meta(get_the_ID(), 'orb_event_place', TRUE) .'<br>';
            echo date('d/m/Y', strtotime($date)) .'</span>';
            echo '</div>';
        endwhile;
        wp_reset_postdata();
    endif;
    die();

}
add_shortcode('default_events', 'default_event_posts');

//add excerpt for the post
add_post_type_support( 'page', 'excerpt' );


