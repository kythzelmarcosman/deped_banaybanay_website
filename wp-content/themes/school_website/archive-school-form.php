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
<div class="archive-body">
    <div class="archive-container">

        <div class="issuances-panel archive-panel">

            <!-- Search Bar -->
            <div class="issuances-search">
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
            <div class="results-row">
                <span class="results-count" id="sf-count">
                    <?php echo $total; ?> form<?php echo $total !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <?php if ($forms_query->have_posts()) : ?>

                <ul class="issuances-list" id="sf-list">
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
                            <a href="<?php the_permalink(); ?>" class="list-view-btn">
                                View <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>

                <div class="empty-state" id="sf-no-results" style="display:none;">
                    <i class="fa fa-search"></i>
                    <p>No forms matched your search.</p>
                </div>

            <?php else : ?>
                <div class="empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>No school forms have been published yet.</p>
                </div>
            <?php endif; ?>

        </div><!-- /.issuances-panel -->

    </div><!-- /.sf-container -->
</div><!-- /.sf-body -->


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