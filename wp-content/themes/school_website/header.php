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

        <!-- Top Header: Dual Logos + Title -->
        <div class="top-header">
            <div class="container-fluid">
                <div class="top-header-inner">

                    <!-- Left Logo -->
                    <div class="top-logo top-logo-left">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/BanaybanayDistrict_Logo.jpg" alt="">
                    </div>

                    <!-- Center Title -->
                    <div class="top-header-center">
                        <div class="school-tagline">
                            DEPARTMENT OF EDUCATION - REGION XI
                            DIVISION OF DAVAO ORIENTAL
                        </div>
                        <div class="school-title">
                            DISTRICT OF BANAYBANAY
                        </div>
                    </div>

                    <!-- Right Logo -->
                    <div class="top-logo top-logo-right">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/DepEdLogo.png" alt="">
                    </div>

                </div>
            </div>
        </div>

        <!-- Main Header: Centered Navigation Only -->
        <div class="main-header">
            <div class="container">

                <!-- Desktop Menu -->
                <nav id="site-navigation" class="menu-wrap hidden-sm hidden-xs">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'menu-1',
                        'container'      => false,
                    ));
                    ?>
                </nav>

                <!-- Mobile Menu -->
                <nav id="mobile-navigation" class="responsive-menu text-right visible-xs visible-sm">
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
// Mobile menu toggle
(function() {
    var btn = document.querySelector('#mobile-navigation .toggle-menu');
    var menu = document.getElementById('mobile-menu');
    if (!btn || !menu) return;
    btn.addEventListener('click', function() {
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
        menu.classList.toggle('open');
    });
})();
</script>