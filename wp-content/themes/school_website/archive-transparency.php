<?php
get_header();

$transparency_query = new WP_Query([
    'post_type'      => 'transparency',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

$total = $transparency_query->found_posts;
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">Transparency</span>
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
                    id="tp-search-input"
                    placeholder="Search transparency documents..."
                    autocomplete="off"
                />
                <button type="button" id="tp-search-btn">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            <!-- Results Count -->
            <div class="results-row">
                <span class="results-count" id="tp-count">
                    <?php echo $total; ?> document<?php echo $total !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <?php if ($transparency_query->have_posts()) : ?>

                <ul class="issuances-list" id="tp-list">
                    <?php while ($transparency_query->have_posts()) : $transparency_query->the_post(); ?>
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

                <div class="empty-state" id="tp-no-results" style="display:none;">
                    <i class="fa fa-search"></i>
                    <p>No documents matched your search.</p>
                </div>

            <?php else : ?>
                <div class="empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>No transparency documents have been published yet.</p>
                </div>
            <?php endif; ?>

        </div><!-- /.issuances-panel -->

    </div><!-- /.tp-container -->
</div><!-- /.tp-body -->


<!-- ============================================================
     JAVASCRIPT — Live Search
     ============================================================ -->
<script>
(function () {
    var input    = document.getElementById('tp-search-input');
    var btn      = document.getElementById('tp-search-btn');
    var list     = document.getElementById('tp-list');
    var noResult = document.getElementById('tp-no-results');
    var countEl  = document.getElementById('tp-count');
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
            countEl.textContent = visible + ' document' + (visible !== 1 ? 's' : '');
        }
    }

    input.addEventListener('input', filter);
    if (btn) btn.addEventListener('click', filter);
})();
</script>

<?php get_footer(); ?>