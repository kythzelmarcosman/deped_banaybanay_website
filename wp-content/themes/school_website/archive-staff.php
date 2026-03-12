<?php get_header(); ?>

<!-- Staff Body -->
<div class="staff-page-body">
    <div class="container">

        <?php
        $staff_args = array(
            'post_type'      => 'district-staff',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        );
        $staff_query = new WP_Query($staff_args);

        if ($staff_query->have_posts()) :

            // Collect and group by designation
            $staff_groups = array();
            while ($staff_query->have_posts()) : $staff_query->the_post();
                $designation = '';
                $img_url     = '';

                if (function_exists('get_field')) {
                    $designation = get_field('designation');
                    $staff_image = get_field('staff_image');
                    if (is_array($staff_image) && !empty($staff_image['url'])) {
                        $img_url = $staff_image['url'];
                    } elseif (is_string($staff_image) && !empty($staff_image)) {
                        $img_url = $staff_image;
                    }
                } else {
                    $designation = get_post_meta(get_the_ID(), 'designation', true);
                }

                if (empty($img_url) && has_post_thumbnail()) {
                    $img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
                }

                $group_key = !empty($designation) ? $designation : 'Other Staff';

                $staff_groups[$group_key][] = array(
                    'name'        => get_the_title(),
                    'designation' => $designation,
                    'img_url'     => $img_url,
                    'permalink'   => get_the_permalink(),
                );
            endwhile;
            wp_reset_postdata();

            foreach ($staff_groups as $group_name => $members) : ?>

                <div class="staff-section">
                    <div class="section-heading">
                        <h2><i class="fa fa-users"></i> <?php echo esc_html($group_name); ?></h2>
                    </div>
                    <div class="staff-grid">
                        <?php foreach ($members as $member) : ?>
                        <div class="staff-card">
                            <div class="staff-card-img">
                                <?php if (!empty($member['img_url'])) : ?>
                                    <img src="<?php echo esc_url($member['img_url']); ?>" alt="<?php echo esc_attr($member['name']); ?>">
                                <?php else : ?>
                                    <div class="staff-img-placeholder">
                                        <i class="fa fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="staff-card-overlay">
                                    <a href="<?php echo esc_url($member['permalink']); ?>" class="staff-view-btn">
                                        <i class="fa fa-eye"></i> View Profile
                                    </a>
                                </div>
                            </div>
                            <div class="staff-card-info">
                                <h4 class="staff-name"><?php echo esc_html($member['name']); ?></h4>
                                <?php if (!empty($member['designation'])) : ?>
                                    <span class="staff-designation"><?php echo esc_html($member['designation']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endforeach;

        else : ?>
            <div class="staff-empty">
                <i class="fa fa-users"></i>
                <p>No staff members found. Please add District Staff posts in the WordPress admin.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>