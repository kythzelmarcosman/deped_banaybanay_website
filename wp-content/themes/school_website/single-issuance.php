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
<div class="single-body si-body">
    <div class="single-container si-container">

        <!-- ── Issuance Card ── -->
        <article class="single-card">

            <!-- Header -->
            <header class="single-header">

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

            <!-- File Description -->
            <?php if ($file_description) : ?>
                <div class="single-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($file_description)); ?>
                </div>
            <?php endif; ?>

            <!-- Download Block -->
            <?php if ($file_url) : ?>
                <div class="download-block">
                    <div class="file-info">
                        <div class="file-icon">
                            <?php
                            $icon_class = 'fa-file-o';
                            if ($file_ext === 'PDF') $icon_class = 'fa-file-pdf-o';
                            elseif (in_array($file_ext, ['DOC','DOCX'])) $icon_class = 'fa-file-word-o';
                            elseif (in_array($file_ext, ['XLS','XLSX'])) $icon_class = 'fa-file-excel-o';
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
                    No file attached to this issuance.
                </div>
            <?php endif; ?>

            <!-- Footer: back + share -->
            <footer class="single-footer">
                <a href="<?php echo esc_url(home_url('/issuances')); ?>" class="back-btn">
                    <i class="fa fa-arrow-left"></i> Back to Issuances
                </a>
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

        </article><!-- /.si-card -->

        <!-- ── Other Issuances ── -->
        <?php if ($related->have_posts()) : ?>
            <section class="related-section">
                <div class="related-heading">
                    <h2><i class="fa fa-files-o"></i> Other Issuances</h2>
                </div>
                <ul class="related-list">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <li class="related-item">
                            <div class="related-icon">
                                <i class="fa fa-file-pdf-o"></i>
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

    </div><!-- /.si-container -->
</div><!-- /.si-body -->

<?php endwhile; ?>
<?php get_footer(); ?>