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
<div class="sp-body">
    <div class="sp-container">

        <!-- ── Program Card ── -->
        <article class="sp-card">

            <header class="sp-header">
                <div class="sp-header-icon">
                    <i class="fa fa-graduation-cap"></i>
                </div>
                <div class="sp-header-text">
                    <h1 class="sp-title"><?php the_title(); ?></h1>
                    <div class="sp-meta-row">
                        <span class="sp-meta-item">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_the_date('F j, Y'); ?>
                        </span>
                        <?php if ($content_query->found_posts > 0) : ?>
                            <span class="sp-meta-item">
                                <i class="fa fa-files-o"></i>
                                <?php echo $content_query->found_posts; ?> content item<?php echo $content_query->found_posts !== 1 ? 's' : ''; ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Program Description -->
            <?php if ($program_description) : ?>
                <div class="sp-description profile-wysiwyg">
                    <?php echo wp_kses_post(wpautop($program_description)); ?>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <footer class="sp-footer">
                <a href="<?php echo esc_url(get_post_type_archive_link('program') ?: home_url('/programs/')); ?>" class="sp-back-btn">
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

                <div class="issuances-panel sp-content-panel">

                    <!-- Search -->
                    <div class="issuances-search sp-content-search">
                        <input type="text" id="sp-content-search" placeholder="Search content..." autocomplete="off">
                        <button type="button"><i class="fa fa-search"></i></button>
                    </div>

                    <ul class="issuances-list sp-content-list" id="sp-content-list">
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
                                <a href="<?php the_permalink(); ?>" class="sp-view-btn">
                                    View <i class="fa fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </ul>

                    <div class="sp-no-results" id="sp-no-results" style="display:none;">
                        <i class="fa fa-search"></i>
                        <p>No content matched your search.</p>
                    </div>

                </div>

            </section>

        <?php else : ?>
            <div class="sp-empty">
                <i class="fa fa-inbox"></i>
                <p>No content items have been added to this program yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.sp-container -->
</div><!-- /.sp-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.sp-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.sp-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Program Card ── */
.sp-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    margin-bottom: 40px;
    border-top: 4px solid var(--deped-blue);
}

.sp-header {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 32px 40px 24px;
    border-bottom: 1px solid var(--border);
}

.sp-header-icon {
    width: 56px;
    height: 56px;
    background: var(--deped-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.sp-header-icon .fa {
    font-size: 26px;
    color: var(--deped-blue);
}

.sp-header-text { flex: 1; min-width: 0; }

.sp-title {
    font-size: 26px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.3;
    margin: 0 0 12px;
}

.sp-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.sp-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.sp-meta-item .fa { color: var(--deped-blue); }

.sp-description {
    padding: 28px 40px;
    border-bottom: 1px solid var(--border);
    border-radius: 0;
    box-shadow: none;
}

.sp-footer {
    padding: 20px 40px;
}

.sp-back-btn {
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
.sp-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

/* ── Contents Section ── */
.sp-contents { margin-bottom: 0; }

.sp-content-count {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-light);
}

.sp-content-panel {
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.sp-content-search input  { font-size: 14px; padding: 16px 20px; }
.sp-content-search button { padding: 0 22px; font-size: 17px; }

/* Content list items */
.sp-content-list li {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f3f9;
    transition: background 0.15s;
}
.sp-content-list li:last-child { border-bottom: none; }
.sp-content-list li:hover { background: #f5f7ff; }

.sp-content-list li > .fa {
    color: var(--deped-blue);
    font-size: 20px;
    flex-shrink: 0;
}
.sp-content-list li > div {
    flex: 1;
    min-width: 0;
}
.sp-content-list li > div > a {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    display: block;
    line-height: 1.5;
}
.sp-content-list li > div > a:hover { color: var(--deped-blue); }

.sp-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 700;
    color: var(--deped-blue);
    text-transform: uppercase;
    letter-spacing: 0.4px;
    white-space: nowrap;
    flex-shrink: 0;
    opacity: 0;
    transition: opacity 0.15s, transform 0.15s;
}
.sp-content-list li:hover .sp-view-btn {
    opacity: 1;
    transform: translateX(3px);
}

/* Empty / no results */
.sp-empty,
.sp-no-results {
    text-align: center;
    padding: 60px 24px;
    color: var(--text-light);
}
.sp-empty .fa,
.sp-no-results .fa {
    font-size: 48px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 14px;
}
.sp-empty p,
.sp-no-results p { font-size: 15px; }

/* Responsive */
@media only screen and (max-width: 768px) {
    .sp-body { padding: 40px 0 60px; }
    .sp-container { padding: 0 16px; }
    .sp-header { padding: 24px 22px 20px; flex-direction: column; gap: 14px; }
    .sp-title { font-size: 20px; }
    .sp-description { padding: 22px; }
    .sp-footer { padding: 18px 22px; }
    .sp-view-btn { display: none; }
}
@media only screen and (max-width: 480px) {
    .sp-title { font-size: 18px; }
    .sp-header-icon { width: 46px; height: 46px; }
}
</style>

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