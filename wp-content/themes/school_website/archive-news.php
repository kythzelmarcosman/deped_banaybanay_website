<?php
get_header(); ?>

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
        <div class="filter-bar news-filter-bar">
            <div class="news-filter-left">
                <span class="count-label news-count-label">
                    <i class="fa fa-newspaper-o"></i>
                    <span id="news-count-text">Loading...</span>
                </span>
            </div>
            <div class="news-filter-right">
                <div class="search-wrap news-search-wrap">
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
                <nav class="archive-pagination news-pagination" id="news-pagination">
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