<?php
/**
 * School_Website functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package School_Website
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function school_website_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on School_Website, use a find and replace
		* to change 'school_website' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'school_website', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'school_website' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'school_website_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'school_website_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function school_website_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'school_website_content_width', 640 );
}
add_action( 'after_setup_theme', 'school_website_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function school_website_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'school_website' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'school_website' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'school_website_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function school_website_scripts() {
	wp_enqueue_style( 'school_website-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'school_website-style', 'rtl', 'replace' );

	// Custom style and javascript for theme start.
	//styles
	wp_enqueue_style( 'school_website-Open+Sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,300,600,700,800');
	wp_enqueue_style( 'school_website-Droid+Serif', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic');
	wp_enqueue_style( 'school_website-bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.css');
	wp_enqueue_style( 'school_website-font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css');
	wp_enqueue_style( 'school_website-templatemo', get_template_directory_uri() . '/assets/css/templatemo-misc.css');
	wp_enqueue_style( 'school_website-animate', get_template_directory_uri() . '/assets/css/animate.css');
	wp_enqueue_style( 'school_website-templatemo-main', get_template_directory_uri() . '/assets/css/templatemo-main.css');
	
	//scripts
	//wp_enqueue_script( 'school_website-jquery', get_template_directory_uri() . '/assets/js/jquery-1.10.2.min.js');
	wp_enqueue_script( 'school_website-bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script( 'school_website-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script( 'school_website-lightbox', get_template_directory_uri() . '/assets/js/jquery.lightbox.js', array('jquery'), _S_VERSION, true);
	wp_enqueue_script( 'school_website-custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), _S_VERSION, true);
	// Custom style and javascript for theme end.

	wp_enqueue_script( 'school_website-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'school_website_scripts' );

// Enqueue Swiper.js from CDN for the homepage slider
function enqueue_swiper() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_swiper');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Remove the default Field on Pages tab in the WordPress admin dashboard
 */
function remove_editor_from_pages() {
    remove_post_type_support('page', 'editor');
}
add_action('init', 'remove_editor_from_pages');

/**
 * Change the title placeholder for the "District Staff" post type
 */
function change_staff_title_placeholder($title) {
    $screen = get_current_screen();

    if ($screen->post_type == 'district-staff') {
        $title = 'Add Staff Name';
    }

    return $title;
}
add_filter('enter_title_here', 'change_staff_title_placeholder');

/**
 * Change the title placeholder for the "School" post type
 */
function change_school_title_placeholder($title) {
    $screen = get_current_screen();

    if ($screen->post_type == 'school') {
        $title = 'Add School Name';
    }

    return $title;
}
add_filter('enter_title_here', 'change_school_title_placeholder');

/**
 * Clean Programs → Contents Workflow
 * Fully independent of ACF fields to avoid extra editors
 */

/* ------------------------------
1️⃣ Add Meta Box to Program Editor
------------------------------- */
add_action('add_meta_boxes', function() {

    add_meta_box(
        'program_contents_box',
        'Contents',
        'program_contents_box_callback',
        'program', // Shows only in Programs
        'normal',
        'high'
    );

});

/* Render Add Content Button + Contents Table */
function program_contents_box_callback($post){
    // Add Content Button
    $add_url = admin_url('post-new.php?post_type=content&program_id=' . $post->ID);
    echo '<p><a href="' . esc_url($add_url) . '" class="button button-primary">+ Add Content</a></p>';

    // Contents Table
    $contents = get_posts([
        'post_type' => 'content',
        'meta_query' => [
            ['key' => 'parent_program', 'value' => $post->ID]
        ],
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    if ($contents) {
        echo '<table class="widefat striped"><thead><tr><th>Content</th><th>Date</th><th>Actions</th></tr></thead><tbody>';
        foreach ($contents as $content) {
            echo '<tr>';
            echo '<td>' . esc_html($content->post_title) . '</td>';
            echo '<td>' . get_the_date('', $content) . '</td>';
            echo '<td><a href="' . get_edit_post_link($content->ID) . '">Edit</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No contents assigned.</p>';
    }
}

/* ------------------------------
2️⃣ Auto-fill Parent Program Field in Contents
------------------------------- */
add_filter('acf/load_value/name=parent_program', function($value, $post_id, $field){

    if (isset($_GET['program_id'])) {
        return intval($_GET['program_id']);
    }

    return $value;

}, 10, 3);

/* ------------------------------
3️⃣ Back to Program Button in Content Editor
------------------------------- */
add_action('edit_form_after_title', function($post){

    if ($post->post_type !== 'content') return;

    $program_id = 0;

    // Use URL parameter if coming from Add Content button
    if (isset($_GET['program_id'])) {
        $program_id = intval($_GET['program_id']);
    }
    // Fallback: use parent_program field if editing existing content
    elseif ($parent_program = get_field('parent_program', $post->ID)) {
        $program_id = $parent_program;
    }

    if ($program_id) {
        $program_title = get_the_title($program_id);
        $program_link = get_edit_post_link($program_id);

        echo '<div style="margin-bottom:10px;">';
        echo '<a class="button button-secondary" href="' . esc_url($program_link) . '">&larr; Back to Program: ' . esc_html($program_title) . '</a>';
        echo '</div>';
    }

});

/* ------------------------------
4️⃣ Optional: Frontend Display Function
------------------------------- */
function display_program_contents($program_id){
    $contents = get_posts([
        'post_type' => 'content',
        'meta_query' => [
            ['key' => 'parent_program', 'value' => $program_id]
        ],
        'posts_per_page' => -1,
    ]);

    if ($contents) {
        echo '<h2>Contents</h2><ul>';
        foreach ($contents as $content) {
            echo '<li><a href="' . get_permalink($content->ID) . '">' . get_the_title($content->ID) . '</a></li>';
        }
        echo '</ul>';
    }
}



// =============================================
// SITE SETTINGS - Custom Admin Menu
// =============================================

add_action( 'admin_menu', 'site_settings_admin_menu' );

function site_settings_admin_menu() {

    // --- Page IDs (update these to match your actual page IDs) ---
    $home_banner_id      = 9;
    $district_profile_id = 63;
    $meet_psds_id        = 106;
    $contact_us_id       = 26;

    // --- Top-level "Site Settings" menu ---
    add_menu_page(
        'Site Settings',           // Page title
        'Site Settings',           // Menu label
        'edit_pages',              // Capability required
        'site-settings',           // Menu slug
        'site_settings_main_page', // Callback function
        'dashicons-admin-settings',// Icon (Dashicon)
        25                         // Position in menu
    );

    // --- Child: Home Image Banner ---
    add_submenu_page(
        'site-settings',
        'Home Image Banner',
        'Home Image Banner',
        'edit_pages',
        esc_url( admin_url( 'post.php?post=' . $home_banner_id . '&action=edit' ) ),
        null
    );

    // --- Child: District Profile ---
    add_submenu_page(
        'site-settings',
        'District Profile',
        'District Profile',
        'edit_pages',
        esc_url( admin_url( 'post.php?post=' . $district_profile_id . '&action=edit' ) ),
        null
    );

    // --- Child: Meet the PSDS ---
    add_submenu_page(
        'site-settings',
        'Meet the PSDS',
        'Meet the PSDS',
        'edit_pages',
        esc_url( admin_url( 'post.php?post=' . $meet_psds_id . '&action=edit' ) ),
        null
    );

    // --- Child: Contact Us ---
    add_submenu_page(
        'site-settings',
        'Contact Us',
        'Contact Us',
        'edit_pages',
        esc_url( admin_url( 'post.php?post=' . $contact_us_id . '&action=edit' ) ),
        null
    );

    // Remove the auto-generated duplicate first child
    remove_submenu_page( 'site-settings', 'site-settings' );
}

// --- Main page callback (landing page for the top-level menu) ---
function site_settings_main_page() {
    echo '<div class="wrap">';
    echo '<h1>Site Settings</h1>';
    echo '<p>Select a section below to edit its content:</p>';
    echo '<ul style="list-style:disc; padding-left:20px;">';
    $items = [
        'Home Image Banner',
        'District Profile',
        'Meet the PSDS',
        'Contact Us',
    ];
    foreach ( $items as $item ) {
        echo '<li><strong>' . esc_html( $item ) . '</strong></li>';
    }
    echo '</ul>';
    echo '</div>';
}

// /**
//  * Hide Posts, Pages, and Comments from Admin Sidebar
//  */
function hide_default_admin_menus() {
    remove_menu_page( 'edit.php' );          // Posts
    remove_menu_page( 'edit.php?post_type=page' ); // Pages
    remove_menu_page( 'edit-comments.php' ); // Comments
}
add_action( 'admin_menu', 'hide_default_admin_menus', 999 );

// Remove "Add New" from Admin Bar (top bar)
add_action( 'admin_bar_menu', 'remove_add_new_page_adminbar', 999 );

function remove_add_new_page_adminbar( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'new-page' );
}

// Remove "Add New" button on the Edit Page screen (post.php)
add_action( 'admin_head-post.php', 'remove_add_new_button_edit_screen' );

function remove_add_new_button_edit_screen() {
    global $post_type;
    if ( $post_type === 'page' ) {
        echo '<style>
            .page-title-action,
            #wpbody-content .wrap .page-title-action { 
                display: none !important; 
            }
        </style>';
    }
}

// =============================================
// URGENT ANNOUNCEMENT - Custom Admin Menu
// =============================================

add_action( 'admin_menu', 'announcement_admin_menu' );

function announcement_admin_menu() {
    $urgent_announce_id = 279;

    add_menu_page(
        'Announcement',
        'Announcement',
        'edit_pages',
        esc_url( admin_url( 'post.php?post=' . $urgent_announce_id . '&action=edit' ) ),
        null,
        'dashicons-megaphone',
        25
    );
}


// =============================================
// ANNOUNCEMENT - Meta Box (Toggle + Textarea)
// =============================================

add_action( 'add_meta_boxes', 'announcement_add_meta_box' );

function announcement_add_meta_box() {
    add_meta_box(
        'announcement_settings',
        'Announcement Settings',
        'announcement_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}

function announcement_meta_box_callback( $post ) {
    if ( $post->ID !== 279 ) return; // Only show on your Announcement page

    wp_nonce_field( 'announcement_save_meta', 'announcement_nonce' );

    $is_active    = get_post_meta( $post->ID, '_announcement_active', true );
    $raw_content  = get_post_meta( $post->ID, '_announcement_content', true );
    ?>

    <style>
        .ann-toggle-wrap { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .ann-toggle { position: relative; display: inline-block; width: 52px; height: 28px; }
        .ann-toggle input { opacity: 0; width: 0; height: 0; }
        .ann-slider {
            position: absolute; cursor: pointer; inset: 0;
            background-color: #ccc; border-radius: 28px; transition: .3s;
        }
        .ann-slider:before {
            content: ""; position: absolute;
            height: 20px; width: 20px; left: 4px; bottom: 4px;
            background: white; border-radius: 50%; transition: .3s;
        }
        input:checked + .ann-slider { background-color: #2271b1; }
        input:checked + .ann-slider:before { transform: translateX(24px); }
        .ann-status-label { font-weight: 600; font-size: 14px; }
        .ann-status-label.active { color: #2271b1; }
        .ann-status-label.inactive { color: #999; }
        #announcement_content_wrap { transition: opacity 0.3s; }
        #announcement_content_wrap.hidden { opacity: 0.3; pointer-events: none; }
        #announcement_content_wrap label { display: block; margin-bottom: 6px; font-weight: 600; }
        #announcement_content_wrap p.desc { color: #666; font-size: 12px; margin-top: 6px; }
    </style>

    <div class="ann-toggle-wrap">
        <label class="ann-toggle">
            <input type="checkbox" id="announcement_active" name="announcement_active" value="1" <?php checked( $is_active, '1' ); ?>>
            <span class="ann-slider"></span>
        </label>
        <span class="ann-status-label <?php echo $is_active ? 'active' : 'inactive'; ?>" id="ann-status-text">
            <?php echo $is_active ? 'Active' : 'Inactive'; ?>
        </span>
    </div>

    <div id="announcement_content_wrap" class="<?php echo ! $is_active ? 'hidden' : ''; ?>">
        <label for="announcement_content">Announcements (one per line):</label>
        <textarea
            id="announcement_content"
            name="announcement_content"
            rows="6"
            style="width:100%; font-family: monospace;"
            placeholder="announcement 1&#10;announcement 2&#10;announcement 3"
        ><?php echo esc_textarea( $raw_content ); ?></textarea>
        <p class="desc">
            Enter each announcement on its own line.<br>
            They will display as: <strong>announcement 1 | announcement 2 | announcement 3</strong>
        </p>
    </div>

    <script>
        (function() {
            const toggle   = document.getElementById('announcement_active');
            const wrap     = document.getElementById('announcement_content_wrap');
            const label    = document.getElementById('ann-status-text');

            function updateUI() {
                if (toggle.checked) {
                    wrap.classList.remove('hidden');
                    label.textContent = 'Active';
                    label.className   = 'ann-status-label active';
                } else {
                    wrap.classList.add('hidden');
                    label.textContent = 'Inactive';
                    label.className   = 'ann-status-label inactive';
                }
            }

            toggle.addEventListener('change', updateUI);
        })();
    </script>

    <?php
}


// =============================================
// ANNOUNCEMENT - Save Meta Data
// =============================================

add_action( 'save_post', 'announcement_save_meta' );

function announcement_save_meta( $post_id ) {
    if ( ! isset( $_POST['announcement_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['announcement_nonce'], 'announcement_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_page', $post_id ) ) return;
    if ( $post_id !== 279 ) return;

    $is_active = isset( $_POST['announcement_active'] ) ? '1' : '0';
    update_post_meta( $post_id, '_announcement_active', $is_active );

    $raw = isset( $_POST['announcement_content'] ) ? sanitize_textarea_field( $_POST['announcement_content'] ) : '';
    update_post_meta( $post_id, '_announcement_content', $raw );
}


// =============================================
// ANNOUNCEMENT - Helper to get formatted output
// =============================================

function get_announcement_ticker_content() {
    $is_active = get_post_meta( 279, '_announcement_active', true );
    if ( $is_active !== '1' ) return null;

    $raw   = get_post_meta( 279, '_announcement_content', true );
    $lines = array_filter( array_map( 'trim', explode( "\n", $raw ) ) );

    return ! empty( $lines ) ? implode( ' | ', $lines ) : null;
}

/**
 * AJAX handler for schools pagination.
 * Paste this into your theme's functions.php
 */
add_action( 'wp_ajax_load_schools_page',        'handle_load_schools_page' );
add_action( 'wp_ajax_nopriv_load_schools_page', 'handle_load_schools_page' );

function handle_load_schools_page() {

    check_ajax_referer( 'schools_pagination', 'nonce' );

    $paged    = isset( $_POST['paged'] )    ? absint( $_POST['paged'] )    : 1;
    $per_page = isset( $_POST['per_page'] ) ? absint( $_POST['per_page'] ) : 6;

    $args = array(
        'post_type'      => 'school',
        'posts_per_page' => $per_page,
        'paged'          => $paged,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

    $query = new WP_Query( $args );

    ob_start();

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();

            $school_image   = get_field('school_image');
            $school_id      = get_field('school_id');
            $school_head    = get_field('school_head');
            $address        = get_field('address');
            $map_image      = get_field('map_image');
            $map_link       = get_field('map_link');

            $contact        = get_field('contact_information');
            $email          = $contact['email']          ?? '';
            $contact_number = $contact['contact_number'] ?? '';
    ?>

    <div class="school-card">

        <div class="school-img">
            <?php if ( $school_image ) : ?>
                <img src="<?php echo esc_url( $school_image['url'] ); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     loading="lazy">
            <?php endif; ?>
        </div>

        <div class="school-info">

            <h5><?php the_title(); ?></h5>

            <?php if ( $school_id ) : ?>
            <p><strong>School ID:</strong> <?php echo esc_html( $school_id ); ?></p>
            <?php endif; ?>

            <?php if ( $school_head ) : ?>
            <p><strong>School Head:</strong> <?php echo esc_html( $school_head ); ?></p>
            <?php endif; ?>

            <?php if ( $contact_number || $email ) : ?>
            <p class="contact-label">Contact</p>
            <?php endif; ?>

            <?php if ( $contact_number ) : ?>
            <p><i class="fa fa-phone"></i> <?php echo esc_html( $contact_number ); ?></p>
            <?php endif; ?>

            <?php if ( $email ) : ?>
            <p><i class="fa fa-envelope"></i> <?php echo esc_html( $email ); ?></p>
            <?php endif; ?>

            <?php if ( $address ) : ?>
            <p><i class="fa fa-map-marker"></i> <?php echo esc_html( $address ); ?></p>
            <?php endif; ?>

            <?php if ( $map_image ) : ?>
            <div class="school-map">
                <?php if ( $map_link ) : ?>
                    <a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener">
                        <img src="<?php echo esc_url( $map_image['url'] ); ?>"
                             alt="Map – <?php the_title_attribute(); ?>"
                             loading="lazy">
                    </a>
                <?php else : ?>
                    <img src="<?php echo esc_url( $map_image['url'] ); ?>"
                         alt="Map – <?php the_title_attribute(); ?>"
                         loading="lazy">
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>

    </div>

    <?php
        endwhile;
        wp_reset_postdata();
    endif;

    $html = ob_get_clean();

    wp_send_json_success( array( 'html' => $html ) );
}