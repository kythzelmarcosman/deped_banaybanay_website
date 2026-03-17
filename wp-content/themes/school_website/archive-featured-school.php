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
<div class="fs-body">
    <div class="fs-container">

        <!-- Filter Bar -->
        <div class="fs-filter-bar">
            <span class="fs-count-label" id="fs-count">
                <i class="fa fa-university"></i>
                <span id="fs-count-text"><?php echo $total; ?> school<?php echo $total !== 1 ? 's' : ''; ?></span>
            </span>
            <div class="fs-search-wrap">
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
                       class="fs-card"
                       data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                        <div class="fs-card-img-wrap">
                            <img src="<?php echo $img_url ?: $fallback; ?>"
                                 alt="<?php echo $img_alt; ?>"
                                 class="fs-card-img">
                            <div class="fs-card-img-overlay">
                                <i class="fa fa-eye"></i>
                            </div>
                        </div>

                        <div class="fs-card-body">
                            <h3 class="fs-card-title"><?php the_title(); ?></h3>
                            <?php if ($excerpt) : ?>
                                <p class="fs-card-desc"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="fs-card-footer">
                            <span class="fs-card-link">
                                View School <i class="fa fa-arrow-right"></i>
                            </span>
                        </div>

                    </a>

                <?php endwhile; wp_reset_postdata(); ?>
            </div><!-- /.fs-grid -->

            <!-- No search results -->
            <div class="fs-no-results" id="fs-no-results" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No schools matched your search.</p>
            </div>

            <!-- Pagination -->
            <?php if ($schools_query->max_num_pages > 1) : ?>
                <div class="fs-pagination" id="fs-pagination">
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
            <div class="fs-empty">
                <i class="fa fa-university"></i>
                <p>No featured schools have been published yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.fs-container -->
</div><!-- /.fs-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.fs-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.fs-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 28px;
}

/* Filter Bar */
.fs-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 36px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
    flex-wrap: wrap;
}

.fs-count-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-mid);
}
.fs-count-label .fa { color: var(--deped-blue); font-size: 16px; }

.fs-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.fs-search-wrap input {
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
.fs-search-wrap input:focus {
    border-color: var(--deped-blue);
    box-shadow: 0 0 0 3px rgba(0,56,168,0.08);
}
.fs-search-wrap > .fa {
    position: absolute;
    right: 13px;
    color: var(--text-light);
    font-size: 13px;
    pointer-events: none;
}

/* Grid */
.fs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

/* Card */
.fs-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    text-decoration: none;
    transition: box-shadow 0.25s, transform 0.25s;
}
.fs-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-5px);
}

.fs-card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 200px;
}
.fs-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
}
.fs-card:hover .fs-card-img { transform: scale(1.06); }

.fs-card-img-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 32, 91, 0);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}
.fs-card:hover .fs-card-img-overlay { background: rgba(0, 32, 91, 0.4); }
.fs-card-img-overlay .fa {
    font-size: 32px;
    color: var(--white);
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.3s, transform 0.3s;
}
.fs-card:hover .fs-card-img-overlay .fa {
    opacity: 1;
    transform: scale(1);
}

.fs-card-body {
    padding: 20px 22px;
    flex: 1;
}

.fs-card-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.35;
    margin: 0 0 8px;
}
.fs-card:hover .fs-card-title { color: var(--deped-blue); }

.fs-card-desc {
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.7;
    margin: 0;
}

.fs-card-footer {
    padding: 12px 22px 18px;
    border-top: 1px solid var(--border);
}

.fs-card-link {
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
.fs-card:hover .fs-card-link { gap: 11px; }
.fs-card-link .fa { font-size: 11px; }

/* Pagination */
.fs-pagination {
    margin-top: 48px;
    display: flex;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
}
.fs-pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    background: var(--white);
    transition: background 0.2s, color 0.2s, border-color 0.2s;
}
.fs-pagination .page-numbers:hover,
.fs-pagination .page-numbers.current {
    background: var(--deped-blue);
    color: var(--white);
    border-color: var(--deped-blue);
}

/* Empty / no results */
.fs-empty,
.fs-no-results {
    text-align: center;
    padding: 80px 24px;
    color: var(--text-light);
}
.fs-empty .fa,
.fs-no-results .fa {
    font-size: 52px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 16px;
}
.fs-empty p,
.fs-no-results p { font-size: 15px; }

/* Responsive */
@media only screen and (max-width: 1024px) {
    .fs-grid { grid-template-columns: repeat(2, 1fr); }
}
@media only screen and (max-width: 768px) {
    .fs-body { padding: 40px 0 60px; }
    .fs-container { padding: 0 16px; }
    .fs-filter-bar { flex-direction: column; align-items: flex-start; }
    .fs-search-wrap, .fs-search-wrap input { width: 100%; }
    .fs-card-img-wrap { height: 180px; }
}
@media only screen and (max-width: 480px) {
    .fs-grid { grid-template-columns: 1fr; }
}
</style>

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