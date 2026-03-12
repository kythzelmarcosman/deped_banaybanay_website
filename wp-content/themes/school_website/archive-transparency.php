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
<div class="tp-body">
    <div class="tp-container">

        <div class="issuances-panel tp-panel">

            <!-- Search Bar -->
            <div class="issuances-search tp-search">
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
            <div class="tp-results-row">
                <span class="tp-results-count" id="tp-count">
                    <?php echo $total; ?> document<?php echo $total !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <?php if ($transparency_query->have_posts()) : ?>

                <ul class="issuances-list tp-list" id="tp-list">
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
                            <a href="<?php the_permalink(); ?>" class="tp-view-btn">
                                View <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>

                <div class="tp-no-results" id="tp-no-results" style="display:none;">
                    <i class="fa fa-search"></i>
                    <p>No documents matched your search.</p>
                </div>

            <?php else : ?>
                <div class="tp-empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>No transparency documents have been published yet.</p>
                </div>
            <?php endif; ?>

        </div><!-- /.issuances-panel -->

    </div><!-- /.tp-container -->
</div><!-- /.tp-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.tp-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.tp-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 28px;
}

.tp-panel {
    border-radius: 8px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.tp-search input  { font-size: 14px; padding: 16px 20px; }
.tp-search button { padding: 0 22px; font-size: 17px; }

.tp-results-row {
    padding: 12px 20px;
    background: #f8f9ff;
    border-bottom: 1px solid var(--border);
}
.tp-results-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* List */
.tp-list li {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f3f9;
    transition: background 0.15s;
}
.tp-list li:last-child { border-bottom: none; }
.tp-list li:hover { background: #f5f7ff; }

.tp-list li > .fa {
    color: var(--deped-blue);
    font-size: 22px;
    flex-shrink: 0;
}
.tp-list li > div {
    flex: 1;
    min-width: 0;
}
.tp-list li > div > a {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.5;
    display: block;
}
.tp-list li > div > a:hover { color: var(--deped-blue); }

.tp-view-btn {
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
.tp-list li:hover .tp-view-btn {
    opacity: 1;
    transform: translateX(3px);
}

.tp-empty-state,
.tp-no-results {
    text-align: center;
    padding: 72px 24px;
    color: var(--text-light);
}
.tp-empty-state .fa,
.tp-no-results .fa {
    font-size: 52px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 16px;
}
.tp-empty-state p,
.tp-no-results p { font-size: 15px; }

@media only screen and (max-width: 768px) {
    .tp-body { padding: 40px 0 60px; }
    .tp-container { padding: 0 16px; }
    .tp-view-btn { display: none; }
}
</style>


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