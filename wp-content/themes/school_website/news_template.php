<?php
/**
 * Template Name: News Page
 *
 * Displays all News custom post type entries.
 * Custom Fields (ACF): news_image (image), news_content (wysiwyg/textarea)
 */
get_header(); ?>

<!-- ============================================================
     PAGE HERO BANNER
     ============================================================ -->
<div class="inner-page-hero">
    <div class="inner-page-hero-content">
        <p class="caption-eyebrow">Department of Education &ndash; Region XI</p>
        <h1>News &amp; Updates</h1>
        <span class="caption-divider"></span>
        <p class="caption-sub">Latest announcements and updates from the District of Banaybanay</p>
    </div>
</div>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">News &amp; Updates</span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="news-page-body">
    <div class="news-page-container">

        <!-- Search + Filter Bar -->
        <div class="news-filter-bar">
            <div class="news-filter-left">
                <span class="news-count-label">
                    <i class="fa fa-newspaper-o"></i>
                    <span id="news-count-text">Loading...</span>
                </span>
            </div>
            <div class="news-filter-right">
                <div class="news-search-wrap">
                    <input type="text" id="news-search-input" placeholder="Search news..." />
                    <i class="fa fa-search"></i>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        <?php
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $news_query = new WP_Query([
            'post_type'      => 'news',
            'posts_per_page' => 9,
            'paged'          => $paged,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ]);
        ?>

        <?php if ($news_query->have_posts()) : ?>

            <div class="news-page-grid" id="news-page-grid">

                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                    <?php
                    $news_image   = function_exists('get_field') ? get_field('news_image')   : null;
                    $news_content = function_exists('get_field') ? get_field('news_content') : '';
                    $excerpt      = $news_content
                        ? wp_trim_words(strip_tags($news_content), 22, '...')
                        : wp_trim_words(get_the_excerpt(), 22, '...');

                    $img_url = '';
                    $img_alt = get_the_title();
                    if ($news_image && is_array($news_image)) {
                        $img_url = esc_url($news_image['url']);
                        $img_alt = esc_attr($news_image['alt'] ?: get_the_title());
                    } elseif ($news_image && is_string($news_image)) {
                        $img_url = esc_url($news_image);
                    } elseif (has_post_thumbnail()) {
                        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    }
                    ?>

                    <article class="news-page-card" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">

                        <!-- Thumbnail -->
                        <a href="<?php the_permalink(); ?>" class="news-page-thumb" tabindex="-1" aria-hidden="true">
                            <?php if ($img_url) : ?>
                                <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>" loading="lazy">
                            <?php else : ?>
                                <div class="news-thumb-placeholder">
                                    <i class="fa fa-newspaper-o"></i>
                                </div>
                            <?php endif; ?>
                            <div class="news-thumb-overlay">
                                <span class="news-read-badge"><i class="fa fa-arrow-right"></i> Read More</span>
                            </div>
                        </a>

                        <!-- Card Body -->
                        <div class="news-page-card-body">

                            <!-- Meta -->
                            <div class="news-page-meta">
                                <span class="news-meta-date">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo get_the_date('F j, Y'); ?>
                                </span>
                                <?php
                                $cats = get_the_terms(get_the_ID(), 'news_category');
                                if ($cats && !is_wp_error($cats)) :
                                    $cat = $cats[0];
                                ?>
                                    <span class="news-meta-cat">
                                        <i class="fa fa-tag"></i>
                                        <?php echo esc_html($cat->name); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Title -->
                            <h3 class="news-page-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <!-- Excerpt -->
                            <?php if ($excerpt) : ?>
                                <p class="news-page-excerpt"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>

                            <!-- Read More -->
                            <a href="<?php the_permalink(); ?>" class="news-read-more">
                                Read Full Article <i class="fa fa-long-arrow-right"></i>
                            </a>

                        </div><!-- /.news-page-card-body -->

                    </article><!-- /.news-page-card -->

                <?php endwhile; ?>

            </div><!-- /#news-page-grid -->

            <!-- No results message (hidden by default, shown by JS) -->
            <div class="news-no-results" id="news-no-results" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No news articles matched your search.</p>
            </div>

            <!-- Pagination -->
            <?php if ($news_query->max_num_pages > 1) : ?>
                <nav class="news-pagination" id="news-pagination">
                    <?php
                    echo paginate_links([
                        'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format'    => '?paged=%#%',
                        'current'   => max(1, $paged),
                        'total'     => $news_query->max_num_pages,
                        'prev_text' => '<i class="fa fa-chevron-left"></i> Prev',
                        'next_text' => 'Next <i class="fa fa-chevron-right"></i>',
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

        <?php else : ?>

            <!-- Empty State -->
            <div class="news-no-results">
                <i class="fa fa-newspaper-o"></i>
                <p>No news articles have been published yet. Check back soon!</p>
            </div>

        <?php endif; ?>
        <?php wp_reset_postdata(); ?>

    </div><!-- /.news-page-container -->
</div><!-- /.news-page-body -->


<!-- ============================================================
     PAGE STYLES
     ============================================================ -->
<style>
/* ── Body ── */
.news-page-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.news-page-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Filter / Search Bar ── */
.news-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 36px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
    flex-wrap: wrap;
}

.news-count-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-mid);
}
.news-count-label .fa {
    color: var(--deped-blue);
    font-size: 18px;
}

.news-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.news-search-wrap input {
    padding: 10px 40px 10px 16px;
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
.news-search-wrap input:focus {
    border-color: var(--deped-blue);
    box-shadow: 0 0 0 3px rgba(0,56,168,0.08);
}
.news-search-wrap .fa {
    position: absolute;
    right: 13px;
    color: var(--text-light);
    font-size: 14px;
    pointer-events: none;
}

/* ── News Grid ── */
.news-page-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

/* ── News Card ── */
.news-page-card {
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.25s, transform 0.25s;
}
.news-page-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-5px);
}

/* Thumbnail */
.news-page-thumb {
    display: block;
    position: relative;
    height: 220px;
    overflow: hidden;
    background: var(--deped-light);
    flex-shrink: 0;
}
.news-page-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease, filter 0.4s ease;
    filter: brightness(0.9);
}
.news-page-card:hover .news-page-thumb img {
    transform: scale(1.06);
    filter: brightness(0.6);
}

/* Placeholder */
.news-thumb-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--deped-light) 0%, #d0d9ef 100%);
}
.news-thumb-placeholder .fa {
    font-size: 56px;
    color: var(--deped-blue);
    opacity: 0.2;
}

/* Thumbnail hover overlay */
.news-thumb-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,32,91,0.52);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s;
}
.news-page-card:hover .news-thumb-overlay { opacity: 1; }

.news-read-badge {
    background: var(--deped-yellow);
    color: var(--deped-dark);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 10px 22px;
    border-radius: 3px;
}

/* Card Body */
.news-page-card-body {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
    border-top: 3px solid var(--deped-blue);
}

/* Meta */
.news-page-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
.news-meta-date,
.news-meta-cat {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
}
.news-meta-date .fa { color: var(--deped-blue); }
.news-meta-cat .fa  { color: var(--deped-red); }

.news-meta-cat {
    background: var(--deped-light);
    color: var(--deped-blue);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding: 3px 9px;
    border-radius: 20px;
}

/* Title */
.news-page-title {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.45;
    margin: 0 0 10px;
    color: var(--deped-dark);
}
.news-page-title a { color: inherit; }
.news-page-title a:hover { color: var(--deped-blue); }

/* Excerpt */
.news-page-excerpt {
    font-size: 13px;
    color: var(--text-light);
    line-height: 1.7;
    margin-bottom: 18px;
    flex: 1;
}

/* Read More Link */
.news-read-more {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    font-weight: 700;
    color: var(--deped-blue);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: auto;
    border-bottom: 2px solid transparent;
    padding-bottom: 2px;
    transition: color 0.2s, border-color 0.2s;
    width: fit-content;
}
.news-read-more:hover {
    color: var(--deped-red);
    border-bottom-color: var(--deped-red);
}
.news-read-more .fa { transition: transform 0.2s; }
.news-read-more:hover .fa { transform: translateX(4px); }

/* ── Empty / No Results State ── */
.news-no-results {
    text-align: center;
    padding: 100px 24px;
    color: var(--text-light);
}
.news-no-results .fa {
    font-size: 60px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 20px;
}
.news-no-results p {
    font-size: 16px;
}

/* ── Pagination ── */
.news-pagination {
    margin-top: 56px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
}
.news-pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 14px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 600;
    color: var(--deped-blue);
    background: var(--white);
    border: 1px solid var(--border);
    transition: background 0.2s, color 0.2s, border-color 0.2s;
    text-decoration: none;
}
.news-pagination .page-numbers:hover {
    background: var(--deped-blue);
    color: var(--white);
    border-color: var(--deped-blue);
}
.news-pagination .page-numbers.current {
    background: var(--deped-blue);
    color: var(--white);
    border-color: var(--deped-blue);
}
.news-pagination .page-numbers.dots {
    border: none;
    background: transparent;
    color: var(--text-light);
    cursor: default;
}

/* ── Responsive ── */
@media only screen and (max-width: 1024px) {
    .news-page-grid { grid-template-columns: repeat(2, 1fr); gap: 22px; }
}
@media only screen and (max-width: 768px) {
    .news-page-body { padding: 40px 0 60px; }
    .news-page-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
    .news-page-thumb { height: 180px; }
    .news-filter-bar { flex-direction: column; align-items: flex-start; }
    .news-search-wrap input { width: 100%; }
    .news-search-wrap { width: 100%; }
}
@media only screen and (max-width: 480px) {
    .news-page-grid { grid-template-columns: 1fr; }
    .news-page-thumb { height: 200px; }
    .news-page-container { padding: 0 16px; }
}
</style>

<!-- ============================================================
     JAVASCRIPT — Live Search + Count
     ============================================================ -->
<script>
(function () {
    const grid        = document.getElementById('news-page-grid');
    const searchInput = document.getElementById('news-search-input');
    const noResults   = document.getElementById('news-no-results');
    const countText   = document.getElementById('news-count-text');
    const pagination  = document.getElementById('news-pagination');

    if (!grid) return;

    const cards      = Array.from(grid.querySelectorAll('.news-page-card'));
    const totalCount = cards.length;

    function updateCount(visible) {
        if (countText) {
            countText.textContent = visible === totalCount
                ? totalCount + ' article' + (totalCount !== 1 ? 's' : '') + ' found'
                : visible + ' of ' + totalCount + ' articles';
        }
    }

    updateCount(totalCount);

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let visibleCount = 0;

            cards.forEach(function (card) {
                const title    = card.dataset.title || '';
                const bodyText = card.querySelector('.news-page-excerpt')
                    ? card.querySelector('.news-page-excerpt').textContent.toLowerCase()
                    : '';

                const matches = !query || title.includes(query) || bodyText.includes(query);
                card.style.display = matches ? '' : 'none';
                if (matches) visibleCount++;
            });

            if (noResults)  noResults.style.display  = visibleCount === 0 ? 'block' : 'none';
            if (pagination) pagination.style.display  = query ? 'none' : '';
            updateCount(visibleCount);
        });
    }
})();
</script>

<?php get_footer(); ?>