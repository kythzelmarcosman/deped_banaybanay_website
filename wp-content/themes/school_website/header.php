<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Favicons -->
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/ico/favicon.ico">

<div id="site-wrapper">
    <div class="site-header">

        <!-- Main Header: Logo Left + Navigation Right -->
        <div class="main-header">
            <div class="container">

                <!-- Left Logo -->
                <div class="header-logo">
                    <a href="<?php echo esc_url( home_url('/') ); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/DepEd-Circle.png" alt="<?php bloginfo('name'); ?>">
                    </a>
                    <a href="<?php echo esc_url( home_url('/') ); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/DIVISION-OF-DAVAO-ORIENTAL-1024x1024.png" alt="<?php bloginfo('name'); ?>">
                    </a>
                    <a href="<?php echo esc_url( home_url('/') ); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/BanaybanayDistrict_Logo.png" alt="<?php bloginfo('name'); ?>">
                    </a>
                    <h1>
                       DEPED BANAYBANAY DISTRICT
                    </h1>
                </div>

                <!-- Desktop Menu -->
                <nav id="site-navigation" class="menu-wrap hidden-sm hidden-xs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu-1',
                        'container'      => false,
                    ));
                    ?>
                </nav>

                <!-- Mobile Menu Toggle -->
                <nav id="mobile-navigation" class="responsive-menu visible-xs visible-sm">
                    <button class="toggle-menu" aria-expanded="false" aria-controls="mobile-menu">
                        <i class="fa fa-bars color-white"></i>
                        <span class="screen-reader-text">Menu</span>
                    </button>
                    <div class="menu" id="mobile-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'menu-1',
                            'container'      => false,
                        ));
                        ?>
                    </div>
                </nav>

            </div>
        </div>

    </div>

    <?php
    $announcement = get_announcement_ticker_content();
    if ( $announcement ) : ?>
        <div class="announcement-bar">
            <div class="announcement-label"><i class="fa fa-bullhorn"></i></div>
            <div class="announcement-ticker">
                <span><?php echo esc_html( $announcement ); ?></span>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
(function() {
    var btn  = document.querySelector('#mobile-navigation .toggle-menu');
    var menu = document.getElementById('mobile-menu');
    if (!btn || !menu) return;

    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
        menu.classList.toggle('open');
    });

    /* Close when clicking anywhere outside the menu */
    document.addEventListener('click', function(e) {
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        }
    });

    /* Close when a menu link is clicked */
    menu.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            menu.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        });
    });
})();
</script>