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
<div class="ip-body">
    <div class="ip-container">

        <div class="issuances-panel ip-panel">

            <!-- Search Bar -->
            <div class="issuances-search ip-search">
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
            <div class="ip-results-row">
                <span class="ip-results-count" id="ip-count">
                    <?php echo $total; ?> issuance<?php echo $total !== 1 ? 's' : ''; ?>
                </span>
            </div>

            <?php if ($issuances_query->have_posts()) : ?>

                <ul class="issuances-list ip-list" id="ip-list">
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
                            <a href="<?php the_permalink(); ?>" class="ip-view-btn">
                                View <i class="fa fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endwhile; wp_reset_postdata(); ?>
                </ul>

                <div class="ip-no-results" id="ip-no-results" style="display:none;">
                    <i class="fa fa-search"></i>
                    <p>No issuances matched your search.</p>
                </div>

            <?php else : ?>
                <div class="ip-empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>No issuances have been published yet.</p>
                </div>
            <?php endif; ?>

        </div><!-- /.issuances-panel -->

    </div><!-- /.ip-container -->
</div><!-- /.ip-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.ip-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.ip-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 28px;
}

.ip-panel {
    border-radius: 8px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

/* Search bar */
.ip-search input {
    font-size: 14px;
    padding: 16px 20px;
}
.ip-search button {
    padding: 0 22px;
    font-size: 17px;
}

/* Results row */
.ip-results-row {
    padding: 12px 20px;
    background: #f8f9ff;
    border-bottom: 1px solid var(--border);
}

.ip-results-count {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* List */
.ip-list li {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f3f9;
    transition: background 0.15s;
}
.ip-list li:last-child { border-bottom: none; }
.ip-list li:hover { background: #f5f7ff; }

.ip-list li > .fa {
    color: var(--deped-red);
    font-size: 22px;
    flex-shrink: 0;
}

.ip-list li > div {
    flex: 1;
    min-width: 0;
}
.ip-list li > div > a {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.5;
    display: block;
}
.ip-list li > div > a:hover { color: var(--deped-blue); }

.memo-date {
    font-size: 12px;
    color: var(--text-light);
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.memo-date .fa { color: var(--deped-blue); font-size: 11px; }

.ip-view-btn {
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
.ip-list li:hover .ip-view-btn {
    opacity: 1;
    transform: translateX(3px);
}

/* Empty / no results */
.ip-empty-state,
.ip-no-results {
    text-align: center;
    padding: 72px 24px;
    color: var(--text-light);
}
.ip-empty-state .fa,
.ip-no-results .fa {
    font-size: 52px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 16px;
}
.ip-empty-state p,
.ip-no-results p { font-size: 15px; }

/* Responsive */
@media only screen and (max-width: 768px) {
    .ip-body { padding: 40px 0 60px; }
    .ip-container { padding: 0 16px; }
    .ip-view-btn { display: none; }
}
</style>


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