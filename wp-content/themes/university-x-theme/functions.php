<?php
require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/like-route.php');

function x_custom_rest() {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function(){ return get_the_author(); }
    ));
    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function(){ return count_user_posts(get_current_user_id(), 'note'); }
    ));
};
add_action('rest_api_init', 'x_custom_rest');

function pageBanner($args = NULL) {
    if(!$args['title']) {
        $args['title'] = get_the_title();
    }

    if(!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    
    if(!$args['photo']) {
        if(get_field('page_banner_background_image'))
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        else $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
    ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(
          <?php echo $args['photo'] ?>);">
    </div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
        <div class="page-banner__intro">
            <p><?php echo $args['subtitle'] ?></p>
        </div>
    </div>
</div>
<?php }

function x_files() {
    wp_enqueue_style('custom_google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyDSu6vYMNohQRmZkBW6PQsq9Kw5EFAtNeo', null, '1.0', true);

    if(strstr($_SERVER['SERVER_NAME'], 'university-x.local'))
    wp_enqueue_script('main-x-js','http://localhost:3000/bundled.js', null, '1.0', true);
    else { 
        wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.c0230591fee5550328c6.js'), null, '1.0', true);
        wp_enqueue_script('main-x-js', get_theme_file_uri('/bundled-assets/scripts.48cd48d60e52a7663cfc.js'), null, '1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.48cd48d60e52a7663cfc.css'));
    }

    wp_localize_script('main-x-js', 'x_data', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
));

}
add_action('wp_enqueue_scripts', 'x_files');

function x_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 660, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'x_features');

function x_adjust_queries($query) {
    
    if(!is_admin() AND is_post_type_archive('campus') AND is_main_query()){
        $query->set('posts_per_page', -1);
    } 

    if(!is_admin() AND is_post_type_archive('program') AND is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    if(!is_admin() AND is_post_type_archive('event') AND is_main_query()){ 
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric'
            )
        ));
    }
}
add_action('pre_get_posts', 'x_adjust_queries');

function xMapKey($api) {
    $api['key'] = 'AIzaSyDSu6vYMNohQRmZkBW6PQsq9Kw5EFAtNeo';
    return $api;
}

add_filter('acf/fields/google_map/api', 'xMapKey');

//Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
        //redirect user to the home page
        wp_redirect(site_url('/'));
        exit;
    }
}

//hide admin top nav bar from subscribers
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    //redirect user to the home page
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber')
        show_admin_bar(false);
}

//Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
    return esc_url(site_url('/'));
}

//Customize login style
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS() {
    wp_enqueue_style('custom_google_fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('our-main-styles', get_theme_file_uri('/bundled-assets/styles.48cd48d60e52a7663cfc.css'));
}

//Customize login title
add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
    return get_bloginfo('name');
}

//Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postarr) {
    if($data['post_type'] == 'note') {
        if(count_user_posts( get_current_user_id(), 'note') > 5 AND !$postarr['ID']) 
            die('You have reached your note limit');
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash')
        $data['post_status'] = 'private';
    
   return $data; 
}
?>