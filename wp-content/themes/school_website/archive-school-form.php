<?php
/**
 * Archive Template: School Forms
 * Custom Post Type: school_form
 * ACF Fields:
 *   - description  (textarea)
 *   - attachment   (file)
 */
get_header();

$forms_query = new WP_Query([
    'post_type'      => 'school-form',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

$total = $forms_query->found_posts;
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">School Forms</span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="sf-body">
    <div class="sf-container">

        <div class="issuances-panel sf-panel">

            <!-- Search Bar -->
            <div class="issuances-search sf-search">
                <input
                    type="text"
                    id="sf-search-input"
                    placeholder="Search school forms..."
                    autocomplete="off"
                />
                <button type="button" id="sf-search-btn">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            <!-- Results count -->
            <div class="sf-results-row">
                <span class="sf-results-count" id="sf-count">
                    <?php echo $total; ?> form<?php echo $total !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <?php if ($forms_query->have_posts()) : ?>

                <ul class="issuances-list sf-list" id="sf-list">
                    <?php while ($forms_query->have_posts()) : $forms_query->the_post();
                        $attachment = function_exists('get_field') ? get_field('attachment') : null;

                        // Resolve file extension for icon
                        $file_ext   = '';
                        if ($attachment && is_array($attachment)) {
                            $file_ext = strtoupper(pathinfo($attachment['filename'], PATHINFO_EXTENSION));
                        } elseif ($attachment && is_string($attachment)) {
                            $file_ext = strtoupper(pathinfo($attachment, PATHINFO_EXTENSION));
                        }

                        $icon_class = 'fa-file-o';
                        if ($file_ext === 'PDF')                          $icon_class = 'fa-file-pdf-o';
                        elseif (in_array($file_ext, ['DOC', 'DOCX']))    $icon_class = 'fa-file-word-o';
                        elseif (in_array($file_ext, ['XLS', 'XLSX']))    $icon_class = 'fa-file-excel-o';
                        elseif (in_array($file_ext, ['PPT', 'PPTX']))    $icon_class = 'fa-file-powerpoint-o';
                        elseif (in_array($file_ext, ['ZIP', 'RAR']))     $icon_class = 'fa-file-archive-o';
                    ?>
                        <li data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                            <i class="fa <?php echo $icon_class; ?>"></i>
                            <div>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <span class="memo-date">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo get_the_date('F j, Y'); ?>
                                </span>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="sf-view-btn">
                                View <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>

                <div class="sf-no-results" id="sf-no-results" style="display:none;">
                    <i class="fa fa-search"></i>
                    <p>No forms matched your search.</p>
                </div>

            <?php else : ?>
                <div class="sf-empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>No school forms have been published yet.</p>
                </div>
            <?php endif; ?>

        </div><!-- /.issuances-panel -->

    </div><!-- /.sf-container -->
</div><!-- /.sf-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.sf-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.sf-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 28px;
}

.sf-panel {
    border-radius: 8px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.sf-search input  { font-size: 14px; padding: 16px 20px; }
.sf-search button { padding: 0 22px; font-size: 17px; }

.sf-results-row {
    padding: 12px 20px;
    background: #f8f9ff;
    border-bottom: 1px solid var(--border);
}
.sf-results-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* List */
.sf-list li {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f3f9;
    transition: background 0.15s;
}
.sf-list li:last-child { border-bottom: none; }
.sf-list li:hover { background: #f5f7ff; }

.sf-list li > .fa {
    color: var(--deped-blue);
    font-size: 22px;
    flex-shrink: 0;
}
.sf-list li > div {
    flex: 1;
    min-width: 0;
}
.sf-list li > div > a {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.5;
    display: block;
}
.sf-list li > div > a:hover { color: var(--deped-blue); }

.sf-view-btn {
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
.sf-list li:hover .sf-view-btn {
    opacity: 1;
    transform: translateX(3px);
}

/* Empty / no results */
.sf-empty-state,
.sf-no-results {
    text-align: center;
    padding: 72px 24px;
    color: var(--text-light);
}
.sf-empty-state .fa,
.sf-no-results .fa {
    font-size: 52px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 16px;
}
.sf-empty-state p,
.sf-no-results p { font-size: 15px; }

@media only screen and (max-width: 768px) {
    .sf-body      { padding: 40px 0 60px; }
    .sf-container { padding: 0 16px; }
    .sf-view-btn  { display: none; }
}
</style>


<!-- ============================================================
     JAVASCRIPT — Live Search
     ============================================================ -->
<script>
(function () {
    var input    = document.getElementById('sf-search-input');
    var btn      = document.getElementById('sf-search-btn');
    var list     = document.getElementById('sf-list');
    var noResult = document.getElementById('sf-no-results');
    var countEl  = document.getElementById('sf-count');
    if (!input || !list) return;

    function filter() {
        var q       = input.value.toLowerCase().trim();
        var items   = list.querySelectorAll('li');
        var visible = 0;

        items.forEach(function (li) {
            var match = !q || (li.dataset.title || '').includes(q);
            li.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        list.style.display     = visible === 0 ? 'none'  : '';
        noResult.style.display = visible === 0 ? 'block' : 'none';

        if (countEl) {
            countEl.textContent = visible + ' form' + (visible !== 1 ? 's' : '');
        }
    }

    input.addEventListener('input', filter);
    if (btn) btn.addEventListener('click', filter);
})();
</script>

<?php get_footer(); ?>