<?php
/**
 * Single Program Template
 * Custom Post Type: program
 * ACF Fields: program_description (textarea)
 *
 * Content items linked via ACF Post Object field: parent_program
 */
get_header();

while ( have_posts() ) : the_post();

    $program_description = function_exists('get_field') ? get_field('program_description') : '';

    // Fetch all Content items whose parent_program points to this Program
    $content_query = new WP_Query([
        'post_type'      => 'content',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [
            'relation' => 'OR',
            // ACF stores post object as plain integer
            [
                'key'     => 'parent_program',
                'value'   => get_the_ID(),
                'compare' => '=',
                'type'    => 'NUMERIC',
            ],
            // ACF may serialize as: a:1:{i:0;s:X:"ID";}
            [
                'key'     => 'parent_program',
                'value'   => '"' . get_the_ID() . '"',
                'compare' => 'LIKE',
            ],
        ],
    ]);
?>

<!-- ============================================================
     PAGE HERO
     ============================================================ -->
<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span><a href="<?php echo esc_url(get_post_type_archive_link('program') ?: home_url('/programs/')); ?>">Programs</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="single-body sp-body">
    <div class="single-container sp-container">

        <!-- ── Program Card ── -->
        <article class="single-card">

            <header class="single-header">
                <div class="single-header-icon">
                    <i class="fa fa-graduation-cap"></i>
                </div>
                <div class="single-header-text">
                    <h1 class="single-title"><?php the_title(); ?></h1>
                    <div class="single-meta-row">
                        <span class="single-meta-item">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_the_date('F j, Y'); ?>
                        </span>
                        <?php if ($content_query->found_posts > 0) : ?>
                            <span class="single-meta-item">
                                <i class="fa fa-files-o"></i>
                                <?php echo $content_query->found_posts; ?> content item<?php echo $content_query->found_posts !== 1 ? 's' : ''; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Program Description -->
            <?php if ($program_description) : ?>
                <div class="single-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($program_description)); ?>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="sp-footer">
                <a href="<?php echo esc_url(get_post_type_archive_link('program') ?: home_url('/programs/')); ?>" class="back-btn">
                    <i class="fa fa-arrow-left"></i> Back to Programs
                </a>
            </footer>

        </article>

        <!-- ── Content Items ── -->
        <?php if ($content_query->have_posts()) : ?>
            <section class="sp-contents">

                <div class="section-heading">
                    <h2><i class="fa fa-files-o"></i> Content</h2>
                    <span class="sp-content-count"><?php echo $content_query->found_posts; ?> item<?php echo $content_query->found_posts !== 1 ? 's' : ''; ?></span>
                </div>

                <div class="issuances-panel archive-panel">

                    <!-- Search -->
                    <div class="issuances-search">
                        <input type="text" id="sp-content-search" placeholder="Search content..." autocomplete="off">
                        <button type="button"><i class="fa fa-search"></i></button>
                    </div>

                    <ul class="issuances-list" id="sp-content-list">
                        <?php while ($content_query->have_posts()) : $content_query->the_post(); ?>
                            <li data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                                <i class="fa fa-file-text-o"></i>
                                <div>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <span class="memo-date">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo get_the_date('F j, Y'); ?>
                                    </span>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="list-view-btn">
                                    View <i class="fa fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>

                    <div class="empty-state" id="sp-no-results" style="display:none;">
                        <i class="fa fa-search"></i>
                        <p>No content matched your search.</p>
                    </div>

                </div>

            </section>

        <?php else : ?>
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <p>No content items have been added to this program yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.sp-container -->
</div><!-- /.sp-body -->

<!-- ============================================================
     JAVASCRIPT — Live Search
     ============================================================ -->
<script>
(function () {
    var input    = document.getElementById('sp-content-search');
    var list     = document.getElementById('sp-content-list');
    var noResult = document.getElementById('sp-no-results');
    if (!input || !list) return;

    input.addEventListener('input', function () {
        var q       = this.value.toLowerCase().trim();
        var items   = list.querySelectorAll('li');
        var visible = 0;

        items.forEach(function (li) {
            var match = !q || (li.dataset.title || '').includes(q);
            li.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        list.style.display     = visible === 0 ? 'none'  : '';
        noResult.style.display = visible === 0 ? 'block' : 'none';
    });
})();
</script>

<?php endwhile; ?>
<?php get_footer(); ?>