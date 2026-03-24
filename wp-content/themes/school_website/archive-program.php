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
<div class="archive-body pg-body">
    <div class="archive-container pg-container">

        <!-- Search + Count Bar -->
        <div class="filter-bar">
            <span class="count-label" id="pg-count">
                <i class="fa fa-th-list"></i>
                <?php echo $total; ?> program<?php echo $total !== 1 ? 's' : ''; ?>
            </span>
            <div class="search-wrap">
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
                       class="archive-card pg-card"
                       data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                        <div class="pg-card-icon">
                            <i class="fa fa-graduation-cap"></i>
                        </div>

                        <div class="pg-card-body">
                            <h3 class="pg-card-title"><?php the_title(); ?></h3>
                            <?php if ($excerpt) : ?>
                                <p class="archive-card-desc"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="pg-card-footer">
                            <span class="card-link">
                                View Program <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>

                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <!-- No search results -->
            <div class="empty-state" id="pg-no-results" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No programs matched your search.</p>
            </div>

        <?php else : ?>
            <div class="empty-state">
                <i class="fa fa-inbox"></i>
                <p>No programs have been published yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.pg-container -->
</div><!-- /.pg-body -->

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