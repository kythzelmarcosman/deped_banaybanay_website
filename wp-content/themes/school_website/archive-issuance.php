<?php
get_header();

$issuances_query = new WP_Query([
    'post_type'      => 'issuance',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$total = $issuances_query->found_posts;
?>
<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">Issuances</span>
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
                    id="ip-search-input"
                    placeholder="Search issuances by title..."
                    autocomplete="off"
                />
                <button type="button" id="ip-search-btn">
                    <i class="fa fa-search"></i>
                </button>
            </div>

            <!-- Results count row -->
            <div class="results-row">
                <span class="results-count" id="ip-count">
                    <?php echo $total; ?> issuance<?php echo $total !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <?php if ($issuances_query->have_posts()) : ?>

                <ul class="issuances-list" id="ip-list">
                    <?php while ($issuances_query->have_posts()) : $issuances_query->the_post(); ?>
                        <li data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                            <i class="fa fa-file-pdf-o"></i>
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

                <div class="empty-state" id="ip-no-results" style="display:none;">
                    <i class="fa fa-search"></i>
                    <p>No issuances matched your search.</p>
                </div>

            <?php else : ?>
                <div class="empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>No issuances have been published yet.</p>
                </div>
            <?php endif; ?>

        </div><!-- /.issuances-panel -->

    </div><!-- /.ip-container -->
</div><!-- /.ip-body -->


<!-- ============================================================
     JAVASCRIPT — Live Search
     ============================================================ -->
<script>
(function () {
    var input    = document.getElementById('ip-search-input');
    var btn      = document.getElementById('ip-search-btn');
    var list     = document.getElementById('ip-list');
    var noResult = document.getElementById('ip-no-results');
    var countEl  = document.getElementById('ip-count');

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
            countEl.textContent = visible + ' issuance' + (visible !== 1 ? 's' : '');
        }
    }

    input.addEventListener('input', filter);
    if (btn) btn.addEventListener('click', filter);
})();
</script>

<?php get_footer(); ?>