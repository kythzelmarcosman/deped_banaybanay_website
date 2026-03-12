<?php /* Template Name: PSDS Page */
get_header(); ?>

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