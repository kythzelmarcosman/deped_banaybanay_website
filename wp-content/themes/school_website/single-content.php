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
<div class="sc-body">
    <div class="sc-container">

        <!-- ── Content Card ── -->
        <article class="sc-card">

            <!-- Header -->
            <header class="sc-header">

                <?php if ($program_title && $program_permalink) : ?>
                    <a href="<?php echo esc_url($program_permalink); ?>" class="sc-program-badge">
                        <i class="fa fa-graduation-cap"></i>
                        <?php echo esc_html($program_title); ?>
                    </a>
                <?php endif; ?>

                <h1 class="sc-title"><?php the_title(); ?></h1>

                <div class="sc-meta-row">
                    <span class="sc-meta-item">
                        <i class="fa fa-calendar"></i>
                        <?php echo get_the_date('F j, Y'); ?>
                    </span>
                    <span class="sc-meta-item">
                        <i class="fa fa-clock-o"></i>
                        <?php echo get_the_date('g:i A'); ?>
                    </span>
                </div>

            </header>

            <!-- Content Description -->
            <?php if ($content_description) : ?>
                <div class="sc-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($content_description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="sc-download-block">
                    <div class="sc-file-info">
                        <div class="sc-file-icon">
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
                        <div class="sc-file-details">
                            <span class="sc-file-name"><?php echo $file_name ?: 'Attached File'; ?></span>
                            <span class="sc-file-meta">
                                <?php if ($file_ext)  echo '<span class="sc-file-ext">'  . $file_ext  . '</span>'; ?>
                                <?php if ($file_size) echo '<span class="sc-file-size">' . $file_size . '</span>'; ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?php echo $file_url; ?>"
                       download
                       target="_blank"
                       rel="noopener noreferrer"
                       class="sc-download-btn">
                        <i class="fa fa-download"></i> Download File
                    </a>
                </div>
            <?php else : ?>
                <div class="sc-no-file">
                    <i class="fa fa-exclamation-circle"></i>
                    No file attached to this content item.
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="sc-footer">
                <?php if ($program_permalink) : ?>
                    <a href="<?php echo esc_url($program_permalink); ?>" class="sc-back-btn">
                        <i class="fa fa-arrow-left"></i> Back to Program
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(get_post_type_archive_link('program') ?: home_url('/programs/')); ?>" class="sc-back-btn">
                        <i class="fa fa-arrow-left"></i> Back to Programs
                    </a>
                <?php endif; ?>

                <div class="sc-share">
                    <span class="sc-share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer" class="sc-share-btn sc-share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="sc-share-btn sc-share-em" title="Share via Email">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>
            </footer>

        </article><!-- /.sc-card -->

        <!-- ── Other Content in this Program ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="sc-related">
                <div class="sc-related-heading">
                    <h2>
                        <i class="fa fa-files-o"></i>
                        <?php echo $program_title ? 'More from ' . esc_html($program_title) : 'Other Content'; ?>
                    </h2>
                </div>
                <ul class="sc-related-list">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <li class="sc-related-item">
                            <div class="sc-related-icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="sc-related-info">
                                <a href="<?php the_permalink(); ?>" class="sc-related-title"><?php the_title(); ?></a>
                                <span class="sc-related-date">
                                    <i class="fa fa-calendar"></i> <?php echo get_the_date('F j, Y'); ?>
                                </span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="sc-related-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php endif; ?>

    </div><!-- /.sc-container -->
</div><!-- /.sc-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.sc-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.sc-container {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Card ── */
.sc-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 40px;
    border-top: 4px solid var(--deped-blue);
}

/* Header */
.sc-header {
    padding: 36px 40px 28px;
    border-bottom: 1px solid var(--border);
}

.sc-program-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--deped-light);
    color: var(--deped-blue);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 16px;
    border: 1px solid rgba(0,56,168,0.15);
    transition: background 0.2s;
}
.sc-program-badge:hover {
    background: var(--deped-blue);
    color: var(--white);
}
.sc-program-badge .fa { font-size: 11px; }

.sc-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.35;
    margin: 0 0 18px;
}

.sc-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.sc-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.sc-meta-item .fa { color: var(--deped-blue); }

/* Description */
.sc-description {
    padding: 28px 40px;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    box-shadow: none;
}

/* ── Download Block ── */
.sc-download-block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 28px 40px;
    background: var(--deped-light);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.sc-file-info {
    display: flex;
    align-items: center;
    gap: 18px;
    flex: 1;
    min-width: 0;
}

.sc-file-icon {
    width: 56px;
    height: 56px;
    background: var(--deped-blue);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 26px;
    color: var(--white);
}

.sc-file-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.sc-file-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--deped-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sc-file-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sc-file-ext {
    display: inline-block;
    background: var(--deped-blue);
    color: var(--white);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 2px 8px;
    border-radius: 3px;
    text-transform: uppercase;
}

.sc-file-size {
    font-size: 12px;
    color: var(--text-light);
}

.sc-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    background: var(--deped-blue);
    color: var(--white) !important;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 14px 28px;
    border-radius: 5px;
    white-space: nowrap;
    transition: background 0.2s, transform 0.15s;
    flex-shrink: 0;
}
.sc-download-btn:hover {
    background: var(--deped-dark);
    transform: translateY(-2px);
    color: var(--white) !important;
}
.sc-download-btn .fa { font-size: 16px; }

/* No file */
.sc-no-file {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 24px 40px;
    font-size: 14px;
    color: var(--text-light);
    font-style: italic;
    border-bottom: 1px solid var(--border);
}
.sc-no-file .fa { color: var(--deped-red); font-size: 18px; }

/* Footer */
.sc-footer {
    padding: 22px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.sc-back-btn {
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
.sc-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

.sc-share {
    display: flex;
    align-items: center;
    gap: 10px;
}
.sc-share-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
}
.sc-share-label .fa { color: var(--deped-blue); }
.sc-share-btn {
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
.sc-share-btn:hover { transform: scale(1.12); opacity: 0.85; }
.sc-share-fb { background: #1877f2; }
.sc-share-em { background: var(--deped-red); }

/* ── Related Content ── */
.sc-related {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.sc-related-heading {
    background: var(--deped-blue);
    padding: 16px 28px;
}
.sc-related-heading h2 {
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

.sc-related-list { list-style: none; margin: 0; padding: 0; }

.sc-related-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 28px;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.sc-related-item:last-child { border-bottom: none; }
.sc-related-item:hover { background: #f8f9ff; }

.sc-related-icon {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    background: var(--deped-blue);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 18px;
    flex-shrink: 0;
}

.sc-related-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sc-related-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.sc-related-title:hover { color: var(--deped-blue); }

.sc-related-date {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
}
.sc-related-date .fa { color: var(--deped-blue); }

.sc-related-arrow {
    color: var(--deped-blue);
    font-size: 13px;
    flex-shrink: 0;
    opacity: 0.4;
    transition: opacity 0.2s, transform 0.2s;
}
.sc-related-item:hover .sc-related-arrow {
    opacity: 1;
    transform: translateX(3px);
}

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .sc-body { padding: 40px 0 60px; }
    .sc-container { padding: 0 16px; }
    .sc-header { padding: 24px 22px 20px; }
    .sc-title { font-size: 20px; }
    .sc-description { padding: 22px; }
    .sc-download-block { padding: 22px; flex-direction: column; align-items: flex-start; }
    .sc-download-btn { width: 100%; justify-content: center; }
    .sc-footer { padding: 18px 22px; flex-direction: column; align-items: flex-start; }
    .sc-no-file { padding: 20px 22px; }
    .sc-related-heading { padding: 14px 20px; }
    .sc-related-item { padding: 14px 20px; }
}
@media only screen and (max-width: 480px) {
    .sc-title { font-size: 18px; }
    .sc-file-icon { width: 46px; height: 46px; font-size: 20px; }
}
</style>

<?php endwhile; ?>
<?php get_footer(); ?>