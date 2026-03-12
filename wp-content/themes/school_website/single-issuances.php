<?php
/**
 * Single Issuance Post Template
 * Custom Post Type: issuance
 * ACF Fields:
 *   - issuance_file     (file)
 *   - file_description  (textarea / wysiwyg)
 */
get_header();

while ( have_posts() ) : the_post();

    $issuance_file    = function_exists('get_field') ? get_field('issuance_file')    : null;
    $file_description = function_exists('get_field') ? get_field('file_description') : '';

    // Resolve file details from ACF file field
    $file_url  = '';
    $file_name = '';
    $file_size = '';
    $file_ext  = '';

    if ( $issuance_file && is_array($issuance_file) ) {
        $file_url  = esc_url($issuance_file['url']);
        $file_name = esc_html($issuance_file['filename']);
        $file_ext  = strtoupper(pathinfo($issuance_file['filename'], PATHINFO_EXTENSION));
        if ( !empty($issuance_file['filesize']) ) {
            $bytes     = $issuance_file['filesize'];
            $file_size = $bytes >= 1048576
                ? round($bytes / 1048576, 2) . ' MB'
                : round($bytes / 1024, 1) . ' KB';
        }
    } elseif ( $issuance_file && is_string($issuance_file) ) {
        $file_url  = esc_url($issuance_file);
        $file_name = basename($issuance_file);
        $file_ext  = strtoupper(pathinfo($issuance_file, PATHINFO_EXTENSION));
    }

    // Related issuances (any type, exclude current)
    $related = new WP_Query([
        'post_type'      => 'issuance',
        'posts_per_page' => 6,
        'post__not_in'   => [ get_the_ID() ],
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span><a href="<?php echo esc_url(home_url('/issuances')); ?>">Issuances</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="si-body">
    <div class="si-container">

        <!-- ── Issuance Card ── -->
        <article class="si-card">

            <!-- Header -->
            <header class="si-header">

                <h1 class="si-title"><?php the_title(); ?></h1>

                <div class="si-meta-row">
                    <span class="si-meta-item">
                        <i class="fa fa-calendar"></i>
                        <?php echo get_the_date('F j, Y'); ?>
                    </span>
                    <span class="si-meta-item">
                        <i class="fa fa-clock-o"></i>
                        <?php echo get_the_date('g:i A'); ?>
                    </span>
                </div>

            </header>

            <!-- File Description -->
            <?php if ($file_description) : ?>
                <div class="si-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($file_description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="si-download-block">
                    <div class="si-file-info">
                        <div class="si-file-icon">
                            <?php
                            $icon_class = 'fa-file-o';
                            if ($file_ext === 'PDF') $icon_class = 'fa-file-pdf-o';
                            elseif (in_array($file_ext, ['DOC','DOCX'])) $icon_class = 'fa-file-word-o';
                            elseif (in_array($file_ext, ['XLS','XLSX'])) $icon_class = 'fa-file-excel-o';
                            ?>
                            <i class="fa <?php echo $icon_class; ?>"></i>
                        </div>
                        <div class="si-file-details">
                            <span class="si-file-name"><?php echo $file_name ?: 'Attached File'; ?></span>
                            <span class="si-file-meta">
                                <?php if ($file_ext)  echo '<span class="si-file-ext">'  . $file_ext  . '</span>'; ?>
                                <?php if ($file_size) echo '<span class="si-file-size">' . $file_size . '</span>'; ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?php echo $file_url; ?>"
                       download
                       target="_blank"
                       rel="noopener noreferrer"
                       class="si-download-btn">
                        <i class="fa fa-download"></i> Download File
                    </a>
                </div>
            <?php else : ?>
                <div class="si-no-file">
                    <i class="fa fa-exclamation-circle"></i>
                    No file attached to this issuance.
                </div>
            <?php endif; ?>

            <!-- Footer: back + share -->
            <footer class="si-footer">
                <a href="<?php echo esc_url(home_url('/issuances')); ?>" class="si-back-btn">
                    <i class="fa fa-arrow-left"></i> Back to Issuances
                </a>
                <div class="si-share">
                    <span class="si-share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer" class="si-share-btn si-share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="si-share-btn si-share-em" title="Share via Email">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>
            </footer>

        </article><!-- /.si-card -->

        <!-- ── Other Issuances ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="si-related">
                <div class="si-related-heading">
                    <h2><i class="fa fa-files-o"></i> Other Issuances</h2>
                </div>
                <ul class="si-related-list">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <li class="si-related-item">
                            <div class="si-related-icon">
                                <i class="fa fa-file-pdf-o"></i>
                            </div>
                            <div class="si-related-info">
                                <a href="<?php the_permalink(); ?>" class="si-related-title"><?php the_title(); ?></a>
                                <span class="si-related-date">
                                    <i class="fa fa-calendar"></i> <?php echo get_the_date('F j, Y'); ?>
                                </span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="si-related-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>
            </section>
        <?php endif; ?>

    </div><!-- /.si-container -->
</div><!-- /.si-body -->

<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
/* ── Layout ── */
.si-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.si-container {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Card ── */
.si-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 40px;
}

/* Header */
.si-header {
    padding: 36px 40px 28px;
    border-bottom: 1px solid var(--border);
}

.si-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.35;
    margin: 0 0 18px;
}

.si-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.si-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.si-meta-item .fa { color: var(--deped-blue); }

/* File Description */
.si-description {
    padding: 28px 40px;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    box-shadow: none;
}

/* ── Download Block ── */
.si-download-block {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 28px 40px;
    background: var(--deped-light);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}

.si-file-info {
    display: flex;
    align-items: center;
    gap: 18px;
    flex: 1;
    min-width: 0;
}

.si-file-icon {
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

.si-file-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.si-file-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--deped-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.si-file-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.si-file-ext {
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

.si-file-size {
    font-size: 12px;
    color: var(--text-light);
}

.si-download-btn {
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
.si-download-btn:hover {
    background: var(--deped-dark);
    transform: translateY(-2px);
    color: var(--white) !important;
}
.si-download-btn .fa { font-size: 16px; }

/* No file */
.si-no-file {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 24px 40px;
    font-size: 14px;
    color: var(--text-light);
    font-style: italic;
    border-bottom: 1px solid var(--border);
}
.si-no-file .fa { color: var(--deped-red); font-size: 18px; }

/* Footer */
.si-footer {
    padding: 22px 40px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}

.si-back-btn {
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
.si-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

.si-share {
    display: flex;
    align-items: center;
    gap: 10px;
}
.si-share-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
}
.si-share-label .fa { color: var(--deped-blue); }
.si-share-btn {
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
.si-share-btn:hover { transform: scale(1.12); opacity: 0.85; }
.si-share-fb { background: #1877f2; }
.si-share-em { background: var(--deped-red); }

/* ── Other Issuances ── */
.si-related {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.si-related-heading {
    background: var(--deped-blue);
    padding: 16px 28px;
}
.si-related-heading h2 {
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

.si-related-list { list-style: none; margin: 0; padding: 0; }

.si-related-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 28px;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
}
.si-related-item:last-child { border-bottom: none; }
.si-related-item:hover { background: #f8f9ff; }

.si-related-icon {
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

.si-related-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.si-related-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.si-related-title:hover { color: var(--deped-blue); }

.si-related-date {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
}
.si-related-date .fa { color: var(--deped-blue); }

.si-related-arrow {
    color: var(--deped-blue);
    font-size: 13px;
    flex-shrink: 0;
    opacity: 0.4;
    transition: opacity 0.2s, transform 0.2s;
}
.si-related-item:hover .si-related-arrow {
    opacity: 1;
    transform: translateX(3px);
}

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .si-body { padding: 40px 0 60px; }
    .si-container { padding: 0 16px; }
    .si-header { padding: 24px 22px 20px; }
    .si-title { font-size: 20px; }
    .si-description { padding: 22px; }
    .si-download-block { padding: 22px; flex-direction: column; align-items: flex-start; }
    .si-download-btn { width: 100%; justify-content: center; }
    .si-footer { padding: 18px 22px; flex-direction: column; align-items: flex-start; }
    .si-no-file { padding: 20px 22px; }
    .si-related-heading { padding: 14px 20px; }
    .si-related-item { padding: 14px 20px; }
}
@media only screen and (max-width: 480px) {
    .si-title { font-size: 18px; }
    .si-file-icon { width: 46px; height: 46px; font-size: 20px; }
}
</style>

<?php endwhile; ?>
<?php get_footer(); ?>