<?php
get_header();

$programs_query = new WP_Query([
    'post_type'      => 'program',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

$total = $programs_query->found_posts;
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">Programs</span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="pg-body">
    <div class="pg-container">

        <!-- Search + Count Bar -->
        <div class="pg-filter-bar">
            <span class="pg-count-label" id="pg-count">
                <i class="fa fa-th-list"></i>
                <?php echo $total; ?> program<?php echo $total !== 1 ? 's' : ''; ?>
            </span>
            <div class="pg-search-wrap">
                <input type="text" id="pg-search" placeholder="Search programs..." autocomplete="off">
                <i class="fa fa-search"></i>
            </div>
        </div>

        <!-- Programs Grid -->
        <?php if ($programs_query->have_posts()) : ?>

            <div class="pg-grid" id="pg-grid">
                <?php while ($programs_query->have_posts()) : $programs_query->the_post();
                    $description = function_exists('get_field') ? get_field('program_description') : '';
                    $excerpt     = $description ? wp_trim_words(strip_tags($description), 24, '...') : '';
                ?>
                    <a href="<?php the_permalink(); ?>"
                       class="pg-card"
                       data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                        <div class="pg-card-icon">
                            <i class="fa fa-graduation-cap"></i>
                        </div>

                        <div class="pg-card-body">
                            <h3 class="pg-card-title"><?php the_title(); ?></h3>
                            <?php if ($excerpt) : ?>
                                <p class="pg-card-desc"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="pg-card-footer">
                            <span class="pg-card-link">
                                View Program <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <!-- No search results -->
            <div class="pg-no-results" id="pg-no-results" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No programs matched your search.</p>
            </div>

        <?php else : ?>
            <div class="pg-no-results">
                <i class="fa fa-inbox"></i>
                <p>No programs have been published yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.pg-container -->
</div><!-- /.pg-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.pg-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.pg-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 28px;
}

/* Filter bar */
.pg-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 36px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
    flex-wrap: wrap;
}

.pg-count-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-mid);
}
.pg-count-label .fa { color: var(--deped-blue); font-size: 16px; }

.pg-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.pg-search-wrap input {
    padding: 10px 38px 10px 16px;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: 13px;
    font-family: inherit;
    color: var(--text-dark);
    background: var(--white);
    width: 260px;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.pg-search-wrap input:focus {
    border-color: var(--deped-blue);
    box-shadow: 0 0 0 3px rgba(0,56,168,0.08);
}
.pg-search-wrap .fa {
    position: absolute;
    right: 13px;
    color: var(--text-light);
    font-size: 13px;
    pointer-events: none;
}

/* Grid */
.pg-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* Card */
.pg-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    text-decoration: none;
    transition: box-shadow 0.25s, transform 0.25s;
    border-top: 4px solid var(--deped-blue);
}
.pg-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-5px);
}

.pg-card-icon {
    background: var(--deped-light);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 24px 20px;
}
.pg-card-icon .fa {
    font-size: 42px;
    color: var(--deped-blue);
    opacity: 0.35;
    transition: opacity 0.2s;
}
.pg-card:hover .pg-card-icon .fa { opacity: 0.7; }

.pg-card-body {
    padding: 20px 24px;
    flex: 1;
}

.pg-card-title {
    font-size: 17px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.35;
    margin: 0 0 10px;
}
.pg-card:hover .pg-card-title { color: var(--deped-blue); }

.pg-card-desc {
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.7;
    margin: 0;
}

.pg-card-footer {
    padding: 14px 24px 20px;
    border-top: 1px solid var(--border);
}

.pg-card-link {
    font-size: 12px;
    font-weight: 700;
    color: var(--deped-blue);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: gap 0.2s;
}
.pg-card:hover .pg-card-link { gap: 11px; }
.pg-card-link .fa { font-size: 11px; }

/* No results / empty */
.pg-no-results {
    text-align: center;
    padding: 80px 24px;
    color: var(--text-light);
}
.pg-no-results .fa {
    font-size: 52px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 16px;
}
.pg-no-results p { font-size: 15px; }

/* Responsive */
@media only screen and (max-width: 1024px) {
    .pg-grid { grid-template-columns: repeat(2, 1fr); }
}
@media only screen and (max-width: 768px) {
    .pg-body { padding: 40px 0 60px; }
    .pg-container { padding: 0 16px; }
    .pg-filter-bar { flex-direction: column; align-items: flex-start; }
    .pg-search-wrap { width: 100%; }
    .pg-search-wrap input { width: 100%; }
}
@media only screen and (max-width: 480px) {
    .pg-grid { grid-template-columns: 1fr; }
}
</style>

<!-- ============================================================
     JAVASCRIPT — Live Search
     ============================================================ -->
<script>
(function () {
    var input    = document.getElementById('pg-search');
    var grid     = document.getElementById('pg-grid');
    var noResult = document.getElementById('pg-no-results');
    var countEl  = document.getElementById('pg-count');
    if (!input || !grid) return;

    var cards = grid.querySelectorAll('.pg-card');

    input.addEventListener('input', function () {
        var q       = this.value.toLowerCase().trim();
        var visible = 0;

        cards.forEach(function (card) {
            var match = !q || (card.dataset.title || '').includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        grid.style.display      = visible === 0 ? 'none'  : '';
        noResult.style.display  = visible === 0 ? 'block' : 'none';

        if (countEl) {
            countEl.innerHTML = '<i class="fa fa-th-list"></i> ' + visible + ' program' + (visible !== 1 ? 's' : '');
        }
    });
})();
</script>

<?php get_footer(); ?>