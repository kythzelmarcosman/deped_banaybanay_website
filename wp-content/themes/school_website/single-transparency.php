<?php
/**
 * Single Transparency Template
 * Custom Post Type: transparency
 * ACF Fields:
 *   - description        (textarea / wysiwyg)
 *   - transparency_file  (file)
 */
get_header();

while ( have_posts() ) : the_post();

    $description       = function_exists('get_field') ? get_field('description')       : '';
    $transparency_file = function_exists('get_field') ? get_field('transparency_file') : null;

    // Resolve file details
    $file_url  = '';
    $file_name = '';
    $file_size = '';
    $file_ext  = '';

    if ($transparency_file && is_array($transparency_file)) {
        $file_url  = esc_url($transparency_file['url']);
        $file_name = esc_html($transparency_file['filename']);
        $file_ext  = strtoupper(pathinfo($transparency_file['filename'], PATHINFO_EXTENSION));
        if (!empty($transparency_file['filesize'])) {
            $bytes     = $transparency_file['filesize'];
            $file_size = $bytes >= 1048576
                ? round($bytes / 1048576, 2) . ' MB'
                : round($bytes / 1024, 1) . ' KB';
        }
    } elseif ($transparency_file && is_string($transparency_file)) {
        $file_url  = esc_url($transparency_file);
        $file_name = basename($transparency_file);
        $file_ext  = strtoupper(pathinfo($transparency_file, PATHINFO_EXTENSION));
    }

    // Other transparency documents
    $related = new WP_Query([
        'post_type'      => 'transparency',
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
        <span><a href="<?php echo esc_url(home_url('/transparency')); ?>">Transparency</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="st-body">
    <div class="st-container">

        <!-- ── Document Card ── -->
        <article class="st-card">

            <!-- Header -->
            <header class="st-header">
                <div class="st-header-icon">
                    <i class="fa fa-shield"></i>
                </div>
                <div class="st-header-text">
                    <h1 class="st-title"><?php the_title(); ?></h1>
                    <div class="st-meta-row">
                        <span class="st-meta-item">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_the_date('F j, Y'); ?>
                        </span>
                        <span class="st-meta-item">
                            <i class="fa fa-clock-o"></i>
                            <?php echo get_the_date('g:i A'); ?>
                        </span>
                    </div>
                </div>
            </header>

            <!-- Description -->
            <?php if ($description) : ?>
                <div class="st-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="st-download-block">
                    <div class="st-file-info">
                        <div class="st-file-icon">
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
                        <div class="st-file-details">
                            <span class="st-file-name"><?php echo $file_name ?: 'Attached Document'; ?></span>
                            <span class="st-file-meta">
                                <?php if ($file_ext)  echo '<span class="st-file-ext">'  . $file_ext  . '</span>'; ?>
                                <?php if ($file_size) echo '<span class="st-file-size">' . $file_size . '</span>'; ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?php echo $file_url; ?>"
                       download
                       target="_blank"
                       rel="noopener noreferrer"
                       class="st-download-btn">
                        <i class="fa fa-download"></i> Download Document
                    </a>
                </div>
            <?php else : ?>
                <div class="st-no-file">
                    <i class="fa fa-exclamation-circle"></i>
                    No file attached to this document.
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="st-footer">
                <a href="<?php echo esc_url(home_url('/transparency')); ?>" class="st-back-btn">
                    <i class="fa fa-arrow-left"></i> Back to Transparency
                </a>
                <div class="st-share">
                    <span class="st-share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer" class="st-share-btn st-share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="st-share-btn st-share-em" title="Share via Email">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>
            </footer>

        </article><!-- /.st-card -->

        <!-- ── Other Documents ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="st-related">
                <div class="st-related-heading">
                    <h2><i class="fa fa-files-o"></i> Other Documents</h2>
                </div>
                <ul class="st-related-list">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <li class="st-related-item">
                            <div class="st-related-icon">
                                <i class="fa fa-file-text-o"></i>
                            </div>
                            <div class="st-related-info">
                                <a href="<?php the_permalink(); ?>" class="st-related-title"><?php the_title(); ?></a>
                                <span class="st-related-date">
                                    <i class="fa fa-calendar"></i> <?php echo get_the_date('F j, Y'); ?>
                                </span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="st-related-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php endif; ?>

    </div><!-- /.st-container -->
</div><!-- /.st-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.st-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.st-container {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Card ── */
.st-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 40px;
    border-top: 4px solid var(--deped-blue);
}

/* Header */
.st-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 32px 40px 24px;
    border-bottom: 1px solid var(--border);
}

.st-header-icon {
    width: 56px;
    height: 56px;
    background: var(--deped-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.st-header-icon .fa {
    font-size: 26px;
    color: var(--deped-blue);
}

.st-header-text { flex: 1; min-width: 0; }

.st-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.35;
    margin: 0 0 14px;
}

.st-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.st-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.st-meta-item .fa { color: var(--deped-blue); }

/* Description */
.st-description {
    padding: 28px 40px;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    box-shadow: none;
}

/* ── Download Block ── */
.st-download-block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 28px 40px;
    background: var(--deped-light);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.st-file-info {
    display: flex;
    align-items: center;
    gap: 18px;
    flex: 1;
    min-width: 0;
}

.st-file-icon {
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

.st-file-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.st-file-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--deped-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.st-file-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.st-file-ext {
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

.st-file-size {
    font-size: 12px;
    color: var(--text-light);
}

.st-download-btn {
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
.st-download-btn:hover {
    background: var(--deped-dark);
    transform: translateY(-2px);
    color: var(--white) !important;
}
.st-download-btn .fa { font-size: 16px; }

/* No file */
.st-no-file {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 24px 40px;
    font-size: 14px;
    color: var(--text-light);
    font-style: italic;
    border-bottom: 1px solid var(--border);
}
.st-no-file .fa { color: var(--deped-red); font-size: 18px; }

/* Footer */
.st-footer {
    padding: 22px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.st-back-btn {
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
.st-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

.st-share {
    display: flex;
    align-items: center;
    gap: 10px;
}
.st-share-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
}
.st-share-label .fa { color: var(--deped-blue); }
.st-share-btn {
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
.st-share-btn:hover { transform: scale(1.12); opacity: 0.85; }
.st-share-fb { background: #1877f2; }
.st-share-em { background: var(--deped-red); }

/* ── Related ── */
.st-related {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.st-related-heading {
    background: var(--deped-blue);
    padding: 16px 28px;
}
.st-related-heading h2 {
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

.st-related-list { list-style: none; margin: 0; padding: 0; }

.st-related-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 28px;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.st-related-item:last-child { border-bottom: none; }
.st-related-item:hover { background: #f8f9ff; }

.st-related-icon {
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

.st-related-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.st-related-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.st-related-title:hover { color: var(--deped-blue); }

.st-related-date {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
}
.st-related-date .fa { color: var(--deped-blue); }

.st-related-arrow {
    color: var(--deped-blue);
    font-size: 13px;
    flex-shrink: 0;
    opacity: 0.4;
    transition: opacity 0.2s, transform 0.2s;
}
.st-related-item:hover .st-related-arrow {
    opacity: 1;
    transform: translateX(3px);
}

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .st-body { padding: 40px 0 60px; }
    .st-container { padding: 0 16px; }
    .st-header { padding: 24px 22px 20px; flex-direction: column; gap: 14px; }
    .st-title { font-size: 20px; }
    .st-description { padding: 22px; }
    .st-download-block { padding: 22px; flex-direction: column; align-items: flex-start; }
    .st-download-btn { width: 100%; justify-content: center; }
    .st-footer { padding: 18px 22px; flex-direction: column; align-items: flex-start; }
    .st-no-file { padding: 20px 22px; }
    .st-related-heading { padding: 14px 20px; }
    .st-related-item { padding: 14px 20px; }
}
@media only screen and (max-width: 480px) {
    .st-title { font-size: 18px; }
    .st-file-icon { width: 46px; height: 46px; font-size: 20px; }
    .st-header-icon { width: 46px; height: 46px; }
}
</style>

<?php endwhile; ?>
<?php get_footer(); ?>