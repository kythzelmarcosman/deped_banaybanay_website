<?php
/**
 * Single News Post Template
 * Custom Post Type: news
 * ACF Fields: news_image (image), news_content (wysiwyg/textarea)
 */
get_header();

while (have_posts()) : the_post();

    $news_image   = function_exists('get_field') ? get_field('news_image')   : null;
    $news_content = function_exists('get_field') ? get_field('news_content') : '';

    $img_url = '';
    $img_alt = get_the_title();
    if ($news_image && is_array($news_image)) {
        $img_url = esc_url($news_image['url']);
        $img_alt = esc_attr($news_image['alt'] ?: get_the_title());
    } elseif ($news_image && is_string($news_image)) {
        $img_url = esc_url($news_image);
    } elseif (has_post_thumbnail()) {
        $img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }

    // Other news (exclude current)
    $other_news = new WP_Query([
        'post_type'      => 'news',
        'posts_per_page' => 8,
        'post__not_in'   => [get_the_ID()],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
?>
<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span><a href="<?php echo get_post_type_archive_link('news') ?: home_url('/news/'); ?>">News &amp; Updates</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current"><?php echo wp_trim_words(get_the_title(), 8, '...'); ?></span>
    </div>
</div>

<!-- ============================================================
     SINGLE NEWS BODY
     ============================================================ -->
<div class="single-news-body">
    <div class="single-news-container">

        <article class="single-news-article">

            <!-- Featured Image (first) -->
            <?php if ($img_url) : ?>
                <div class="sn-featured-image">
                    <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>">
                </div>
            <?php endif; ?>

            <!-- Article Header (title + meta below image) -->
            <header class="sn-article-header">

                <?php
                $cats = get_the_terms(get_the_ID(), 'news_category');
                if ($cats && !is_wp_error($cats)) : ?>
                    <div class="sn-categories">
                        <?php foreach ($cats as $cat) : ?>
                            <span class="sn-cat-badge">
                                <i class="fa fa-tag"></i> <?php echo esc_html($cat->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h1 class="sn-title"><?php the_title(); ?></h1>

                <div class="sn-meta-row">
                    <span class="sn-meta-item">
                        <i class="fa fa-calendar"></i>
                        <?php echo get_the_date('F j, Y'); ?>
                    </span>
                    <span class="sn-meta-item">
                        <i class="fa fa-clock-o"></i>
                        <?php echo get_the_date('g:i A'); ?>
                    </span>
                    <?php if (get_the_author()) : ?>
                        <span class="sn-meta-item">
                            <i class="fa fa-user"></i>
                            <?php the_author(); ?>
                        </span>
                    <?php endif; ?>
                </div>

            </header>

            <!-- Article Content -->
            <div class="sn-content">
                <?php
                if ($news_content) {
                    echo wp_kses_post(wpautop($news_content));
                } else {
                    the_content();
                }
                ?>
            </div>

            <!-- Article Footer: Share + Back -->
            <footer class="sn-article-footer">

                <a href="<?php echo get_post_type_archive_link('news') ?: home_url('/news/'); ?>" class="back-btn">
                    <i class="fa fa-arrow-left"></i> Back to News
                </a>

                <div class="share-row">
                    <span class="share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer" class="share-btn share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                       target="_blank" rel="noopener noreferrer" class="share-btn share-tw" title="Share on Twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="share-btn share-em" title="Share via Email">
                        <i class="fa fa-envelope"></i>
                    </a>
                </div>

            </footer>

            <!-- Prev / Next Navigation -->
            <nav class="sn-post-nav">
                <?php
                $prev = get_previous_post(false, '', 'news_category');
                $next = get_next_post(false, '', 'news_category');
                ?>
                <div class="sn-nav-prev">
                    <?php if ($prev) : ?>
                        <a href="<?php echo get_permalink($prev); ?>">
                            <span class="sn-nav-dir"><i class="fa fa-chevron-left"></i> Previous</span>
                            <span class="sn-nav-title"><?php echo esc_html(get_the_title($prev)); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="sn-nav-next">
                    <?php if ($next) : ?>
                        <a href="<?php echo get_permalink($next); ?>">
                            <span class="sn-nav-dir">Next <i class="fa fa-chevron-right"></i></span>
                            <span class="sn-nav-title"><?php echo esc_html(get_the_title($next)); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>

        </article>

    </div>
</div>


<!-- ============================================================
     OTHER NEWS — Horizontal Scrollable Row
     ============================================================ -->
<?php if ($other_news->have_posts()) : ?>
<section class="sn-other-news">
    <div class="sn-other-container">

        <div class="sn-other-heading">
            <h2><i class="fa fa-newspaper-o"></i> More News</h2>
            <a href="<?php echo get_post_type_archive_link('news') ?: home_url('/news/'); ?>" class="sn-other-viewall">
                View All <i class="fa fa-arrow-right"></i>
            </a>
        </div>

        <div class="sn-other-scroll-wrapper">

            <button class="sn-scroll-btn sn-scroll-left" aria-label="Scroll left">
                <i class="fa fa-chevron-left"></i>
            </button>

            <div class="sn-other-scroll" id="sn-other-scroll">
                <?php while ($other_news->have_posts()) : $other_news->the_post();
                    $o_img = function_exists('get_field') ? get_field('news_image') : null;
                    $o_cnt = function_exists('get_field') ? get_field('news_content') : '';
                    $o_url = '';
                    $o_alt = get_the_title();
                    if ($o_img && is_array($o_img)) {
                        $o_url = esc_url($o_img['url']);
                        $o_alt = esc_attr($o_img['alt'] ?: get_the_title());
                    } elseif ($o_img && is_string($o_img)) {
                        $o_url = esc_url($o_img);
                    } elseif (has_post_thumbnail()) {
                        $o_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    }
                    $o_excerpt = $o_cnt
                        ? wp_trim_words(strip_tags($o_cnt), 14, '...')
                        : wp_trim_words(get_the_excerpt(), 14, '...');
                ?>
                    <a href="<?php the_permalink(); ?>" class="sn-other-card">

                        <div class="sn-other-thumb">
                            <?php if ($o_url) : ?>
                                <img src="<?php echo $o_url; ?>" alt="<?php echo $o_alt; ?>" loading="lazy">
                            <?php else : ?>
                                <div class="sn-other-placeholder">
                                    <i class="fa fa-newspaper-o"></i>
                                </div>
                            <?php endif; ?>
                            <div class="sn-other-thumb-overlay"></div>
                        </div>

                        <div class="sn-other-info">
                            <span class="sn-other-date">
                                <i class="fa fa-calendar"></i> <?php echo get_the_date('M j, Y'); ?>
                            </span>
                            <h4 class="sn-other-title"><?php the_title(); ?></h4>
                            <?php if ($o_excerpt) : ?>
                                <p class="sn-other-excerpt"><?php echo esc_html($o_excerpt); ?></p>
                            <?php endif; ?>
                        </div>

                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>

            <button class="sn-scroll-btn sn-scroll-right" aria-label="Scroll right">
                <i class="fa fa-chevron-right"></i>
            </button>

        </div>

    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     JAVASCRIPT — Scroll Buttons
     ============================================================ -->
<script>
(function () {
    var track = document.getElementById('sn-other-scroll');
    if (!track) return;
    var btnLeft  = document.querySelector('.sn-scroll-left');
    var btnRight = document.querySelector('.sn-scroll-right');
    var amount   = 580;
    if (btnLeft)  btnLeft.addEventListener('click',  function () { track.scrollBy({ left: -amount, behavior: 'smooth' }); });
    if (btnRight) btnRight.addEventListener('click', function () { track.scrollBy({ left:  amount, behavior: 'smooth' }); });
})();
</script>

<?php endwhile; ?>
<?php get_footer(); ?>