<?php
/**
 * Single Featured School Template
 * Custom Post Type: featured_schools
 * ACF Fields:
 *   - image       (image)
 *   - description (textarea / wysiwyg)
 */
get_header();

while ( have_posts() ) : the_post();

    $school_image = function_exists('get_field') ? get_field('image')       : null;
    $description  = function_exists('get_field') ? get_field('description') : '';

    $img_url = '';
    $img_alt = get_the_title();
    if ($school_image && is_array($school_image)) {
        $img_url = esc_url($school_image['url']);
        $img_alt = !empty($school_image['alt']) ? esc_attr($school_image['alt']) : $img_alt;
    } elseif ($school_image && is_string($school_image)) {
        $img_url = esc_url($school_image);
    }

    $fallback = get_template_directory_uri() . '/assets/images/school-placeholder.jpg';

    // Other featured schools
    $related = new WP_Query([
        'post_type'      => 'featured_schools',
        'posts_per_page' => 3,
        'post__not_in'   => [get_the_ID()],
        'post_status'    => 'publish',
        'orderby'        => 'rand',
    ]);
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span><a href="<?php echo esc_url(get_post_type_archive_link('featured_school') ?: home_url('/featured-school/')); ?>">Featured Schools</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="single-body sfs-body">
    <div class="single-container sfs-container">

        <!-- ── School Card ── -->
        <article class="sfs-card">

            <!-- Featured Image -->
            <div class="sfs-image-wrap">
                <img src="<?php echo $img_url ?: $fallback; ?>"
                     alt="<?php echo $img_alt; ?>"
                     class="sfs-image">
            </div>

            <!-- Content -->
            <div class="sfs-content">

                <header class="single-header">
                    <div class="single-header-icon">
                        <i class="fa fa-university"></i>
                    </div>
                    <div class="single-header-text">
                        <h1 class="single-title"><?php the_title(); ?></h1>
                        <div class="single-meta-row">
                            <span class="single-meta-item">
                                <i class="fa fa-map-marker"></i>
                                Banaybanay, Davao Oriental
                            </span>
                            <span class="single-meta-item">
                                <i class="fa fa-calendar"></i>
                                <?php echo get_the_date('F j, Y'); ?>
                            </span>
                        </div>
                    </div>
                </header>

                <?php if ($description) : ?>
                    <div class="single-description profile-wysiwyg">
                        <?php echo wp_kses_post(wpautop($description)); ?>
                    </div>
                <?php endif; ?>

                <footer class="single-footer">
                    <a href="<?php echo esc_url(get_post_type_archive_link('featured_schools') ?: home_url('/featured-schools/')); ?>"
                       class="back-btn">
                        <i class="fa fa-arrow-left"></i> Back to Featured Schools
                    </a>
                    <div class="share-row">
                        <span class="share-label"><i class="fa fa-share-alt"></i> Share:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn share-fb" title="Share on Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                           class="share-btn share-em" title="Share via Email">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </div>
                </footer>

            </div><!-- /.sfs-content -->

        </article><!-- /.sfs-card -->

        <!-- ── Other Schools ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="related-section">
                <div class="related-heading">
                    <h2><i class="fa fa-university"></i> Other Featured Schools</h2>
                </div>
                <div class="sfs-related-grid">
                    <?php while ($related->have_posts()) : $related->the_post();
                        $rel_image = function_exists('get_field') ? get_field('image') : null;
                        $rel_url   = '';
                        $rel_alt   = get_the_title();
                        if ($rel_image && is_array($rel_image)) {
                            $rel_url = esc_url($rel_image['url']);
                            $rel_alt = !empty($rel_image['alt']) ? esc_attr($rel_image['alt']) : $rel_alt;
                        } elseif ($rel_image && is_string($rel_image)) {
                            $rel_url = esc_url($rel_image);
                        }
                    ?>
                        <a href="<?php the_permalink(); ?>" class="sfs-related-card">
                            <div class="sfs-related-img-wrap">
                                <img src="<?php echo $rel_url ?: $fallback; ?>"
                                     alt="<?php echo $rel_alt; ?>">
                            </div>
                            <div class="sfs-related-info">
                                <span class="sfs-related-title"><?php the_title(); ?></span>
                                <span class="sfs-related-arrow"><i class="fa fa-arrow-right"></i></span>
                            </div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </section>
        <?php endif; ?>

    </div><!-- /.sfs-container -->
</div><!-- /.sfs-body -->


<?php endwhile; ?>
<?php get_footer(); ?>