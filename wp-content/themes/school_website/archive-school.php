<?php get_header(); ?>

<div class="home-body">
<div class="container">

<section id="schools" class="home-section">

<div class="section-heading">
                <h2><i class="fa fa-building-o"></i> Schools </h2>
            </div>

<div class="schools-row">

<?php
$args = array(
    'post_type' => 'school',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC'
);

$query = new WP_Query($args);

if($query->have_posts()) :
while($query->have_posts()) : $query->the_post();

$school_image = get_field('school_image');
$school_id = get_field('school_id');
$school_head = get_field('school_head');
$address = get_field('address');
$map_image = get_field('map_image');

/* ACF GROUP FIELD */
$contact = get_field('contact_information');
$email = $contact['email'] ?? '';
$contact_number = $contact['contact_number'] ?? '';

?>

<div class="school-card">

    <div class="school-img">
        <?php if($school_image): ?>
            <img src="<?php echo esc_url($school_image['url']); ?>" alt="<?php the_title(); ?>">
        <?php endif; ?>
    </div>

    <div class="school-info">

        <h5><?php the_title(); ?></h5>

        <?php if($school_id): ?>
        <p><strong>School ID:</strong> <?php echo esc_html($school_id); ?></p>
        <?php endif; ?>

        <?php if($school_head): ?>
        <p><strong>School Head:</strong> <?php echo esc_html($school_head); ?></p>
        <?php endif; ?>

        <p><strong>School Contact</strong></p>

        <?php if($contact_number): ?>
        <p><i class="fa fa-phone"></i> <?php echo esc_html($contact_number); ?></p>
        <?php endif; ?>

        <?php if($email): ?>
        <p><i class="fa fa-envelope"></i> <?php echo esc_html($email); ?></p>
        <?php endif; ?>

        <?php if($address): ?>
        <p><i class="fa fa-map-marker"></i> <?php echo esc_html($address); ?></p>
        <?php endif; ?>

        <?php if($map_image): ?>
        <div class="school-map">
            <img src="<?php echo esc_url($map_image['url']); ?>" alt="School Map">
        </div>
        <?php endif; ?>

    </div>

</div>

<?php
endwhile;
wp_reset_postdata();
endif;
?>

</div>

</section>

</div>
</div>

<?php get_footer(); ?>