<?php
/**
 * Archive Template: Featured Schools
 * Custom Post Type: featured_schools
 * ACF Fields: image (image), description (textarea)
 */
get_header();

$paged = get_query_var('paged') ? get_query_var('paged') : 1;

$schools_query = new WP_Query([
    'post_type'      => 'featured-school',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

$total = $schools_query->found_posts;
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">Featured Schools</span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="archive-body fs-body">
    <div class="archive-container fs-container">

        <!-- Filter Bar -->
        <div class="filter-bar fs-filter-bar">
            <span class="count-label" id="fs-count">
                <i class="fa fa-university"></i>
                <span id="fs-count-text"><?php echo $total; ?> school<?php echo $total !== 1 ? 's' : ''; ?></span>
            </span>
            <div class="search-wrap fs-search-wrap">
                <input type="text" id="fs-search-input" placeholder="Search schools..." autocomplete="off">
                <i class="fa fa-search"></i>
            </div>
        </div>

        <?php if ($schools_query->have_posts()) : ?>

            <div class="fs-grid" id="fs-grid">
                <?php while ($schools_query->have_posts()) : $schools_query->the_post();

                    $school_image = function_exists('get_field') ? get_field('image')       : null;
                    $description  = function_exists('get_field') ? get_field('description') : '';
                    $excerpt      = $description
                        ? wp_trim_words(strip_tags($description), 20, '...')
                        : '';

                    $img_url = '';
                    $img_alt = get_the_title();
                    if ($school_image && is_array($school_image)) {
                        $img_url = esc_url($school_image['url']);
                        $img_alt = !empty($school_image['alt']) ? esc_attr($school_image['alt']) : $img_alt;
                    } elseif ($school_image && is_string($school_image)) {
                        $img_url = esc_url($school_image);
                    }

                    $fallback = get_template_directory_uri() . '/assets/images/school-placeholder.jpg';
                ?>
                    <a href="<?php the_permalink(); ?>"
                       class="archive-card fs-card"
                       data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                        <div class="fs-card-img-wrap">
                            <img src="<?php echo $img_url ?: $fallback; ?>"
                                 alt="<?php echo $img_alt; ?>"
                                 class="fs-card-img">
                            <div class="fs-card-img-overlay">
                                <i class="fa fa-eye"></i>
                            </div>
                        </div>

                        <div class="archive-card-body fs-card-body">
                            <h3 class="archive-card-title fs-card-title"><?php the_title(); ?></h3>
                            <?php if ($excerpt) : ?>
                                <p class="archive-card-desc"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="archive-card-footer">
                            <span class="card-link">
                                View School <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>

                    </a>

                <?php endwhile; wp_reset_postdata(); ?>
            </div><!-- /.fs-grid -->

            <!-- No search results -->
            <div class="empty-state" id="fs-no-results" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No schools matched your search.</p>
            </div>

            <!-- Pagination -->
            <?php if ($schools_query->max_num_pages > 1) : ?>
                <div class="archive-pagination" id="fs-pagination">
                    <?php
                    echo paginate_links([
                        'total'     => $schools_query->max_num_pages,
                        'current'   => $paged,
                        'prev_text' => '<i class="fa fa-angle-left"></i> Prev',
                        'next_text' => 'Next <i class="fa fa-angle-right"></i>',
                    ]);
                    ?>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <div class="empty-state">
                <i class="fa fa-university"></i>
                <p>No featured schools have been published yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.fs-container -->
</div><!-- /.fs-body -->




<!-- ============================================================
     JAVASCRIPT — Live Search
     ============================================================ -->
<script>
(function () {
    var input      = document.getElementById('fs-search-input');
    var grid       = document.getElementById('fs-grid');
    var noResult   = document.getElementById('fs-no-results');
    var countText  = document.getElementById('fs-count-text');
    var pagination = document.getElementById('fs-pagination');
    if (!input || !grid) return;

    var cards = grid.querySelectorAll('.fs-card');

    input.addEventListener('input', function () {
        var q       = this.value.toLowerCase().trim();
        var visible = 0;

        cards.forEach(function (card) {
            var match = !q || (card.dataset.title || '').includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        grid.style.display     = visible === 0 ? 'none'  : '';
        noResult.style.display = visible === 0 ? 'block' : 'none';
        if (pagination) pagination.style.display = q ? 'none' : '';

        if (countText) {
            countText.textContent = visible + ' school' + (visible !== 1 ? 's' : '');
        }
    });
})();
</script>

<?php get_footer(); ?>