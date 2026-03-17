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
<div class="ssf-body">
    <div class="ssf-container">

        <!-- ── Form Card ── -->
        <article class="ssf-card">

            <!-- Header -->
            <header class="ssf-header">
                <div class="ssf-header-icon">
                    <i class="fa fa-file-text"></i>
                </div>
                <div class="ssf-header-text">
                    <h1 class="ssf-title"><?php the_title(); ?></h1>
                    <div class="ssf-meta-row">
                        <span class="ssf-meta-item">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_the_date('F j, Y'); ?>
                        </span>
                        <span class="ssf-meta-item">
                            <i class="fa fa-clock-o"></i>
                            <?php echo get_the_date('g:i A'); ?>
                        </span>
                    </div>
                </div>
            </header>

            <!-- Description -->
            <?php if ($description) : ?>
                <div class="ssf-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="ssf-download-block">
                    <div class="ssf-file-info">
                        <div class="ssf-file-icon">
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
                        <div class="ssf-file-details">
                            <span class="ssf-file-name"><?php echo $file_name ?: 'Attached Form'; ?></span>
                            <span class="ssf-file-meta">
                                <?php if ($file_ext)  echo '<span class="ssf-file-ext">'  . $file_ext  . '</span>'; ?>
                                <?php if ($file_size) echo '<span class="ssf-file-size">' . $file_size . '</span>'; ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?php echo $file_url; ?>"
                       download
                       target="_blank"
                       rel="noopener noreferrer"
                       class="ssf-download-btn">
                        <i class="fa fa-download"></i> Download Form
                    </a>
                </div>
            <?php else : ?>
                <div class="ssf-no-file">
                    <i class="fa fa-exclamation-circle"></i>
                    No file attached to this form.
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="ssf-footer">
                <a href="<?php echo esc_url(get_post_type_archive_link('school_form') ?: home_url('/school-forms/')); ?>"
                   class="ssf-back-btn">
                    <i class="fa fa-arrow-left"></i> Back to School Forms
                </a>
                <div class="ssf-share">
                    <span class="ssf-share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="ssf-share-btn ssf-share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="ssf-share-btn ssf-share-em" title="Share via Email">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>
            </footer>

        </article><!-- /.ssf-card -->

        <!-- ── Other Forms ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="ssf-related">
                <div class="ssf-related-heading">
                    <h2><i class="fa fa-files-o"></i> Other School Forms</h2>
                </div>
                <ul class="ssf-related-list">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <li class="ssf-related-item">
                            <div class="ssf-related-icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="ssf-related-info">
                                <a href="<?php the_permalink(); ?>" class="ssf-related-title"><?php the_title(); ?></a>
                                <span class="ssf-related-date">
                                    <i class="fa fa-calendar"></i> <?php echo get_the_date('F j, Y'); ?>
                                </span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="ssf-related-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php endif; ?>

    </div><!-- /.ssf-container -->
</div><!-- /.ssf-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.ssf-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.ssf-container {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Card ── */
.ssf-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 40px;
    border-top: 4px solid var(--deped-blue);
}

/* Header */
.ssf-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 32px 40px 24px;
    border-bottom: 1px solid var(--border);
}

.ssf-header-icon {
    width: 56px;
    height: 56px;
    background: var(--deped-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 26px;
    color: var(--deped-blue);
}

.ssf-header-text { flex: 1; min-width: 0; }

.ssf-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.35;
    margin: 0 0 14px;
}

.ssf-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.ssf-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.ssf-meta-item .fa { color: var(--deped-blue); }

/* Description */
.ssf-description {
    padding: 28px 40px;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    box-shadow: none;
}

/* ── Download Block ── */
.ssf-download-block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 28px 40px;
    background: var(--deped-light);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.ssf-file-info {
    display: flex;
    align-items: center;
    gap: 18px;
    flex: 1;
    min-width: 0;
}

.ssf-file-icon {
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

.ssf-file-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.ssf-file-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--deped-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ssf-file-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.ssf-file-ext {
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

.ssf-file-size {
    font-size: 12px;
    color: var(--text-light);
}

.ssf-download-btn {
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
.ssf-download-btn:hover {
    background: var(--deped-dark);
    transform: translateY(-2px);
    color: var(--white) !important;
}
.ssf-download-btn .fa { font-size: 16px; }

/* No file */
.ssf-no-file {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 24px 40px;
    font-size: 14px;
    color: var(--text-light);
    font-style: italic;
    border-bottom: 1px solid var(--border);
}
.ssf-no-file .fa { color: var(--deped-red); font-size: 18px; }

/* Footer */
.ssf-footer {
    padding: 22px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.ssf-back-btn {
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
.ssf-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

.ssf-share {
    display: flex;
    align-items: center;
    gap: 10px;
}
.ssf-share-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
}
.ssf-share-label .fa { color: var(--deped-blue); }
.ssf-share-btn {
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
.ssf-share-btn:hover { transform: scale(1.12); opacity: 0.85; }
.ssf-share-fb { background: #1877f2; }
.ssf-share-em { background: var(--deped-red); }

/* ── Related ── */
.ssf-related {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.ssf-related-heading {
    background: var(--deped-blue);
    padding: 16px 28px;
}
.ssf-related-heading h2 {
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

.ssf-related-list { list-style: none; margin: 0; padding: 0; }

.ssf-related-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 28px;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.ssf-related-item:last-child { border-bottom: none; }
.ssf-related-item:hover { background: #f8f9ff; }

.ssf-related-icon {
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

.ssf-related-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.ssf-related-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ssf-related-title:hover { color: var(--deped-blue); }

.ssf-related-date {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
}
.ssf-related-date .fa { color: var(--deped-blue); }

.ssf-related-arrow {
    color: var(--deped-blue);
    font-size: 13px;
    flex-shrink: 0;
    opacity: 0.4;
    transition: opacity 0.2s, transform 0.2s;
}
.ssf-related-item:hover .ssf-related-arrow {
    opacity: 1;
    transform: translateX(3px);
}

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .ssf-body        { padding: 40px 0 60px; }
    .ssf-container   { padding: 0 16px; }
    .ssf-header      { padding: 24px 22px 20px; flex-direction: column; gap: 14px; }
    .ssf-title       { font-size: 20px; }
    .ssf-description { padding: 22px; }
    .ssf-download-block { padding: 22px; flex-direction: column; align-items: flex-start; }
    .ssf-download-btn   { width: 100%; justify-content: center; }
    .ssf-footer      { padding: 18px 22px; flex-direction: column; align-items: flex-start; }
    .ssf-no-file     { padding: 20px 22px; }
    .ssf-related-heading { padding: 14px 20px; }
    .ssf-related-item    { padding: 14px 20px; }
}
@media only screen and (max-width: 480px) {
    .ssf-title       { font-size: 18px; }
    .ssf-file-icon   { width: 46px; height: 46px; font-size: 20px; }
    .ssf-header-icon { width: 46px; height: 46px; font-size: 20px; }
}
</style>

<?php endwhile; ?>
<?php get_footer(); ?>