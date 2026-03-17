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
<div class="sfs-body">
    <div class="sfs-container">

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

                <header class="sfs-header">
                    <div class="sfs-header-icon">
                        <i class="fa fa-university"></i>
                    </div>
                    <div class="sfs-header-text">
                        <h1 class="sfs-title"><?php the_title(); ?></h1>
                        <div class="sfs-meta-row">
                            <span class="sfs-meta-item">
                                <i class="fa fa-map-marker"></i>
                                Banaybanay, Davao Oriental
                            </span>
                            <span class="sfs-meta-item">
                                <i class="fa fa-calendar"></i>
                                <?php echo get_the_date('F j, Y'); ?>
                            </span>
                        </div>
                    </div>
                </header>

                <?php if ($description) : ?>
                    <div class="sfs-description profile-wysiwyg">
                        <?php echo wp_kses_post(wpautop($description)); ?>
                    </div>
                <?php endif; ?>

                <footer class="sfs-footer">
                    <a href="<?php echo esc_url(get_post_type_archive_link('featured_schools') ?: home_url('/featured-schools/')); ?>"
                       class="sfs-back-btn">
                        <i class="fa fa-arrow-left"></i> Back to Featured Schools
                    </a>
                    <div class="sfs-share">
                        <span class="sfs-share-label"><i class="fa fa-share-alt"></i> Share:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="sfs-share-btn sfs-share-fb" title="Share on Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                           class="sfs-share-btn sfs-share-em" title="Share via Email">
                            <i class="fa fa-envelope"></i>
                        </a>
                    </div>
                </footer>

            </div><!-- /.sfs-content -->

        </article><!-- /.sfs-card -->

        <!-- ── Other Schools ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="sfs-related">
                <div class="sfs-related-heading">
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


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.sfs-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.sfs-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── School Card ── */
.sfs-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 40px;
}

/* Image */
.sfs-image-wrap {
    width: 100%;
    max-height: 420px;
    overflow: hidden;
    line-height: 0;
}
.sfs-image {
    width: 100%;
    height: 420px;
    object-fit: cover;
    display: block;
}

/* Content */
.sfs-content {
    border-top: 4px solid var(--deped-blue);
}

/* Header */
.sfs-header {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    padding: 30px 40px 24px;
    border-bottom: 1px solid var(--border);
}

.sfs-header-icon {
    width: 52px;
    height: 52px;
    background: var(--deped-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 24px;
    color: var(--deped-blue);
}

.sfs-header-text { flex: 1; min-width: 0; }

.sfs-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.3;
    margin: 0 0 12px;
}

.sfs-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.sfs-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.sfs-meta-item .fa { color: var(--deped-blue); }

/* Description */
.sfs-description {
    padding: 28px 40px;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    box-shadow: none;
}

/* Footer */
.sfs-footer {
    padding: 22px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.sfs-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--deped-blue);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 20px;
    border: 2px solid var(--deped-blue);
    border-radius: 4px;
    transition: background 0.2s, color 0.2s;
}
.sfs-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

.sfs-share {
    display: flex;
    align-items: center;
    gap: 10px;
}
.sfs-share-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
}
.sfs-share-label .fa { color: var(--deped-blue); }
.sfs-share-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: var(--white) !important;
    transition: transform 0.2s, opacity 0.2s;
}
.sfs-share-btn:hover { transform: scale(1.12); opacity: 0.85; }
.sfs-share-fb { background: #1877f2; }
.sfs-share-em { background: var(--deped-red); }

/* ── Related Schools ── */
.sfs-related {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.sfs-related-heading {
    background: var(--deped-blue);
    padding: 16px 28px;
}
.sfs-related-heading h2 {
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--white);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sfs-related-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
}

.sfs-related-card {
    display: flex;
    flex-direction: column;
    text-decoration: none;
    border-right: 1px solid var(--border);
    transition: background 0.2s;
}
.sfs-related-card:last-child { border-right: none; }
.sfs-related-card:hover { background: #f5f7ff; }

.sfs-related-img-wrap {
    height: 140px;
    overflow: hidden;
}
.sfs-related-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s;
}
.sfs-related-card:hover .sfs-related-img-wrap img { transform: scale(1.05); }

.sfs-related-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 14px 18px;
    border-top: 1px solid var(--border);
}

.sfs-related-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1.4;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.sfs-related-card:hover .sfs-related-title { color: var(--deped-blue); }

.sfs-related-arrow {
    color: var(--deped-blue);
    font-size: 12px;
    opacity: 0.4;
    flex-shrink: 0;
    transition: opacity 0.2s, transform 0.2s;
}
.sfs-related-card:hover .sfs-related-arrow {
    opacity: 1;
    transform: translateX(3px);
}

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .sfs-body { padding: 40px 0 60px; }
    .sfs-container { padding: 0 16px; }
    .sfs-image, .sfs-image-wrap { height: 260px; }
    .sfs-header { padding: 22px 22px 18px; flex-direction: column; gap: 12px; }
    .sfs-title { font-size: 20px; }
    .sfs-description { padding: 22px; }
    .sfs-footer { padding: 18px 22px; flex-direction: column; align-items: flex-start; }
    .sfs-related-grid { grid-template-columns: 1fr; }
    .sfs-related-card { border-right: none; border-bottom: 1px solid var(--border); }
    .sfs-related-card:last-child { border-bottom: none; }
    .sfs-related-img-wrap { height: 180px; }
}
@media only screen and (max-width: 480px) {
    .sfs-title { font-size: 18px; }
    .sfs-image, .sfs-image-wrap { height: 200px; }
}
</style>

<?php endwhile; ?>
<?php get_footer(); ?>