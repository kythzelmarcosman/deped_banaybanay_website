<?php /* Template Name: PSDS Page */
get_header(); ?>

<!-- Page Hero Banner -->
<div class="inner-page-hero">
    <div class="inner-page-hero-content">
        <p class="caption-eyebrow">Department of Education &ndash; Region XI</p>
        <h1>Meet the PSDS</h1>
        <span class="caption-divider"></span>
        <p class="caption-sub">District of Banaybanay, Division of Davao Oriental</p>
    </div>
</div>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">Meet the PSDS</span>
    </div>
</div>

<!-- Main Body -->
<div class="home-body">
<div class="container">
<div class="home-layout">

<!-- LEFT CONTENT -->
<div class="main-content-col">

<section class="home-section">

    <div class="section-heading">
        <h2><i class="fa fa-user"></i> Message from the PSDS</h2>
    </div>

    <div class="psds-layout">

        <!-- PSDS IMAGE -->
        <div class="psds-image">
            <?php 
            $psds_image = get_field('psds_image');
            if($psds_image): ?>
                <img src="<?php echo esc_url($psds_image['url']); ?>" 
                     alt="<?php echo esc_attr($psds_image['alt']); ?>">
            <?php endif; ?>

            <p class="psds-name">
                <?php echo esc_html(get_field('psds_name')); ?>
            </p>
        </div>

        <!-- PSDS MESSAGE -->
        <div class="psds-message">
            <?php echo wp_kses_post(wpautop(get_field('psds_message'))); ?>
        </div>

    </div>

</section>

</div>
</div>
</div>

<?php get_footer(); ?>