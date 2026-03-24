<?php
/**
 * 
 * Single Content Template
 * Custom Post Type: content
 * ACF Fields:
 *   - content_description (textarea)
 *   - content_file        (file)
 *   - parent_program      (post object → program)
 */
get_header();

while ( have_posts() ) : the_post();

    $content_description = function_exists('get_field') ? get_field('content_description') : '';
    $content_file        = function_exists('get_field') ? get_field('content_file')        : null;
    $parent_program      = function_exists('get_field') ? get_field('parent_program')      : null;

    // Resolve parent program link
    $program_title     = '';
    $program_permalink = '';
    if ($parent_program) {
        if (is_array($parent_program)) {
            // ACF may return an array when multiple=false but format=array
            $prog_post         = is_array($parent_program) && isset($parent_program['ID']) ? $parent_program : $parent_program[0];
            $program_title     = isset($prog_post->post_title) ? $prog_post->post_title : get_the_title($prog_post);
            $program_permalink = get_permalink($prog_post);
        } elseif (is_object($parent_program)) {
            $program_title     = $parent_program->post_title;
            $program_permalink = get_permalink($parent_program);
        } elseif (is_numeric($parent_program)) {
            $program_title     = get_the_title($parent_program);
            $program_permalink = get_permalink($parent_program);
        }
    }

    // Resolve file details
    $file_url  = '';
    $file_name = '';
    $file_size = '';
    $file_ext  = '';

    if ($content_file && is_array($content_file)) {
        $file_url  = esc_url($content_file['url']);
        $file_name = esc_html($content_file['filename']);
        $file_ext  = strtoupper(pathinfo($content_file['filename'], PATHINFO_EXTENSION));
        if (!empty($content_file['filesize'])) {
            $bytes     = $content_file['filesize'];
            $file_size = $bytes >= 1048576
                ? round($bytes / 1048576, 2) . ' MB'
                : round($bytes / 1024, 1) . ' KB';
        }
    } elseif ($content_file && is_string($content_file)) {
        $file_url  = esc_url($content_file);
        $file_name = basename($content_file);
        $file_ext  = strtoupper(pathinfo($content_file, PATHINFO_EXTENSION));
    }

    // Other content in the same program
    $related_args = [
        'post_type'      => 'content',
        'posts_per_page' => 6,
        'post__not_in'   => [get_the_ID()],
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ];
    if ($parent_program) {
        $prog_id = is_object($parent_program) ? $parent_program->ID : (is_numeric($parent_program) ? $parent_program : (isset($parent_program->ID) ? $parent_program->ID : 0));
        if ($prog_id) {
            $related_args['meta_query'] = [[
                'key'     => 'parent_program',
                'value'   => '"' . $prog_id . '"',
                'compare' => 'LIKE',
            ]];
        }
    }
    $related = new WP_Query($related_args);
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span><a href="<?php echo esc_url(get_post_type_archive_link('program') ?: home_url('/programs/')); ?>">Programs</a></span>
        <?php if ($program_title && $program_permalink) : ?>
            <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
            <span><a href="<?php echo esc_url($program_permalink); ?>"><?php echo esc_html($program_title); ?></a></span>
        <?php endif; ?>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="single-body sc-body">
    <div class="single-container sc-container">

        <!-- ── Content Card ── -->
        <article class="single-card">

            <!-- Header -->
            <header class="single-header">

                <?php if ($program_title && $program_permalink) : ?>
                    <a href="<?php echo esc_url($program_permalink); ?>" class="sc-program-badge">
                        <i class="fa fa-graduation-cap"></i>
                        <?php echo esc_html($program_title); ?>
                    </a>
                <?php endif; ?>

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

            </header>

            <!-- Content Description -->
            <?php if ($content_description) : ?>
                <div class="single-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($content_description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="download-block">
                    <div class="file-info">
                        <div class="file-icon">
                            <?php
                            $icon_class = 'fa-file-o';
                            if ($file_ext === 'PDF')  $icon_class = 'fa-file-pdf-o';
                            elseif (in_array($file_ext, ['DOC','DOCX'])) $icon_class = 'fa-file-word-o';
                            elseif (in_array($file_ext, ['XLS','XLSX'])) $icon_class = 'fa-file-excel-o';
                            elseif (in_array($file_ext, ['PPT','PPTX'])) $icon_class = 'fa-file-powerpoint-o';
                            elseif (in_array($file_ext, ['ZIP','RAR']))  $icon_class = 'fa-file-archive-o';
                            ?>
                            <i class="fa <?php echo $icon_class; ?>"></i>
                        </div>
                        <div class="file-details">
                            <span class="file-name"><?php echo $file_name ?: 'Attached File'; ?></span>
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
                        <i class="fa fa-download"></i> Download File
                    </a>
                </div>
            <?php else : ?>
                <div class="no-file">
                    <i class="fa fa-exclamation-circle"></i>
                    No file attached to this content item.
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="single-footer">
                <?php if ($program_permalink) : ?>
                    <a href="<?php echo esc_url($program_permalink); ?>" class="back-btn">
                        <i class="fa fa-arrow-left"></i> Back to Program
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(get_post_type_archive_link('program') ?: home_url('/programs/')); ?>" class="back-btn">
                        <i class="fa fa-arrow-left"></i> Back to Programs
                    </a>
                <?php endif; ?>

                <div class="share-row">
                    <span class="share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer" class="share-btn share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="share-btn share-em" title="Share via Email">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>
            </footer>

        </article><!-- /.sc-card -->

        <!-- ── Other Content in this Program ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="related-section">
                <div class="related-heading">
                    <h2>
                        <i class="fa fa-files-o"></i>
                        <?php echo $program_title ? 'More from ' . esc_html($program_title) : 'Other Content'; ?>
                    </h2>
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

    </div><!-- /.sc-container -->
</div><!-- /.sc-body -->

<?php endwhile; ?>
<?php get_footer(); ?>