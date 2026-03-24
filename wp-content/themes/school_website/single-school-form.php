<?php
/**
 * Single School Form Template
 * Custom Post Type: school_form
 * ACF Fields:
 *   - description  (textarea)
 *   - attachment   (file)
 */
get_header();

while ( have_posts() ) : the_post();

    $description = function_exists('get_field') ? get_field('description') : '';
    $attachment  = function_exists('get_field') ? get_field('attachment')  : null;

    // Resolve file details
    $file_url  = '';
    $file_name = '';
    $file_size = '';
    $file_ext  = '';

    if ($attachment && is_array($attachment)) {
        $file_url  = esc_url($attachment['url']);
        $file_name = esc_html($attachment['filename']);
        $file_ext  = strtoupper(pathinfo($attachment['filename'], PATHINFO_EXTENSION));
        if (!empty($attachment['filesize'])) {
            $bytes     = $attachment['filesize'];
            $file_size = $bytes >= 1048576
                ? round($bytes / 1048576, 2) . ' MB'
                : round($bytes / 1024, 1) . ' KB';
        }
    } elseif ($attachment && is_string($attachment)) {
        $file_url  = esc_url($attachment);
        $file_name = basename($attachment);
        $file_ext  = strtoupper(pathinfo($attachment, PATHINFO_EXTENSION));
    }

    // Other school forms
    $related = new WP_Query([
        'post_type'      => 'school-form',
        'posts_per_page' => 6,
        'post__not_in'   => [get_the_ID()],
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span><a href="<?php echo esc_url(get_post_type_archive_link('school-form') ?: home_url('/school-form/')); ?>">School Forms</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="single-body ssf-body">
    <div class="single-container ssf-container">

        <!-- ── Form Card ── -->
        <article class="single-card">

            <!-- Header -->
            <header class="single-header">
                <div class="single-header-icon">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="single-header-text">
                    <h1 class="single-title"><?php the_title(); ?></h1>
                    <div class="single-meta-row">
                        <span class="single-meta-item">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_the_date('F j, Y'); ?>
                        </span>
                        <span class="single-meta-item">
                            <i class="fa fa-clock-o"></i>
                            <?php echo get_the_date('g:i A'); ?>
                        </span>
                    </div>
                </div>
            </header>

            <!-- Description -->
            <?php if ($description) : ?>
                <div class="single-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="download-block">
                    <div class="file-info">
                        <div class="file-icon">
                            <?php
                            $icon_class = 'fa-file-o';
                            if ($file_ext === 'PDF')                          $icon_class = 'fa-file-pdf-o';
                            elseif (in_array($file_ext, ['DOC', 'DOCX']))    $icon_class = 'fa-file-word-o';
                            elseif (in_array($file_ext, ['XLS', 'XLSX']))    $icon_class = 'fa-file-excel-o';
                            elseif (in_array($file_ext, ['PPT', 'PPTX']))    $icon_class = 'fa-file-powerpoint-o';
                            elseif (in_array($file_ext, ['ZIP', 'RAR']))     $icon_class = 'fa-file-archive-o';
                            ?>
                            <i class="fa <?php echo $icon_class; ?>"></i>
                        </div>
                        <div class="file-details">
                            <span class="file-name"><?php echo $file_name ?: 'Attached Form'; ?></span>
                            <span class="file-meta">
                                <?php if ($file_ext)  echo '<span class="file-ext">'  . $file_ext  . '</span>'; ?>
                                <?php if ($file_size) echo '<span class="file-size">' . $file_size . '</span>'; ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?php echo $file_url; ?>"
                       download
                       target="_blank"
                       rel="noopener noreferrer"
                       class="download-btn">
                        <i class="fa fa-download"></i> Download Form
                    </a>
                </div>
            <?php else : ?>
                <div class="no-file">
                    <i class="fa fa-exclamation-circle"></i>
                    No file attached to this form.
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="single-footer">
                <a href="<?php echo esc_url(get_post_type_archive_link('school_form') ?: home_url('/school-forms/')); ?>"
                   class="back-btn">
                    <i class="fa fa-arrow-left"></i> Back to School Forms
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

        </article><!-- /.ssf-card -->

        <!-- ── Other Forms ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="related-section">
                <div class="related-heading">
                    <h2><i class="fa fa-files-o"></i> Other School Forms</h2>
                </div>
                <ul class="related-list">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <li class="related-item">
                            <div class="related-icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="related-info">
                                <a href="<?php the_permalink(); ?>" class="related-title"><?php the_title(); ?></a>
                                <span class="related-date">
                                    <i class="fa fa-calendar"></i> <?php echo get_the_date('F j, Y'); ?>
                                </span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="related-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php endif; ?>

    </div><!-- /.ssf-container -->
</div><!-- /.ssf-body -->

<?php endwhile; ?>
<?php get_footer(); ?>