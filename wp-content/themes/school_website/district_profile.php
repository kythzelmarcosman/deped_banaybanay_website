<?php /* Template Name: District Profile Page */
get_header(); ?>

<!-- Page Hero Banner -->
<div class="inner-page-hero">
    <div class="inner-page-hero-content">
        <p class="caption-eyebrow">Department of Education &ndash; Region XI</p>
        <h1>District Profile</h1>
        <span class="caption-divider"></span>
        <p class="caption-sub">District of Banaybanay, Division of Davao Oriental</p>
    </div>
</div>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">District Profile</span>
    </div>
</div>

<!-- Main Body -->
<div class="profile-page-body">
    <div class="container">

        <!-- About the District -->
        <section class="profile-section">
            <div class="section-heading">
                <h2><i class="fa fa-info-circle"></i> About the District</h2>
            </div>

            <?php $about_image = function_exists('get_field') ? get_field('about_us_image') : null; ?>
            <?php if ($about_image) : ?>
                <div class="profile-about-img">
                    <img src="<?php echo esc_url($about_image['url']); ?>" alt="<?php echo esc_attr($about_image['alt']); ?>">
                </div>
            <?php endif; ?>

            <div class="profile-wysiwyg">
                <?php echo wp_kses_post(wpautop(get_field('about_us_content'))); ?>
            </div>
        </section>

        <!-- Vision, Mission & Core Values -->
        <section class="profile-section">
            <div class="section-heading">
                <h2><i class="fa fa-bullseye"></i> Vision, Mission &amp; Core Values</h2>
            </div>

            <div class="vmc-grid">

                <!-- Vision -->
                <div class="vmc-card vmc-vision">
                    <div class="vmc-card-header">
                        <div class="vmc-icon">
                            <i class="fa fa-eye"></i>
                        </div>
                        <h3>Vision</h3>
                    </div>
                    <div class="vmc-card-body">
                        <?php
                        $vision = function_exists('get_field') ? get_field('vision') : '';
                        if ($vision) :
                            echo wp_kses_post(wpautop($vision));
                        else : ?>
                            <p>We dream of Filipinos who passionately love their country and whose values and competencies enable them to realize their full potential and contribute meaningfully to building the nation.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mission -->
                <div class="vmc-card vmc-mission">
                    <div class="vmc-card-header">
                        <div class="vmc-icon">
                            <i class="fa fa-rocket"></i>
                        </div>
                        <h3>Mission</h3>
                    </div>
                    <div class="vmc-card-body">
                        <?php
                        $mission = function_exists('get_field') ? get_field('mission') : '';
                        if ($mission) :
                            echo wp_kses_post(wpautop($mission));
                        else : ?>
                            <p>To protect and promote the right of every Filipino to quality, equitable, culture-based, and complete basic education.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Core Values -->
                <div class="vmc-card vmc-values">
                    <div class="vmc-card-header">
                        <div class="vmc-icon">
                            <i class="fa fa-star"></i>
                        </div>
                        <h3>Core Values</h3>
                    </div>
                    <div class="vmc-card-body">
                        <?php
                        $core_values = function_exists('get_field') ? get_field('core_values') : '';
                        if ($core_values) :
                            echo wp_kses_post(wpautop($core_values));
                        else : ?>
                            <ul>
                                <li><strong>Maka-Diyos</strong></li>
                                <li><strong>Maka-tao</strong></li>
                                <li><strong>Makakalikasan</strong></li>
                                <li><strong>Makabansa</strong></li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- /.vmc-grid -->
        </section>

        <!-- Location Map -->
        <section class="profile-section profile-map-section">
            <div class="section-heading">
                <h2><i class="fa fa-map-marker"></i> Location Map</h2>
            </div>
            <div class="profile-map-wrapper">
                <img 
                    src="<?php echo get_template_directory_uri(); ?>/assets/images/maps/MBGNHS.png" 
                    alt="Map of Banaybanay District"
                    class="profile-map-img"
                >
            </div>
        </section>

    </div><!-- /.container -->
</div><!-- /.profile-page-body -->

<?php get_footer(); ?>