<?php
/*
Template Name: News Single Page
Template Post Type: News
*/
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

<!-- ============================================================
     PAGE HERO BANNER
     ============================================================ -->
<div class="inner-page-hero">
    <div class="inner-page-hero-content">
        <p class="caption-eyebrow">Department of Education &ndash; Region XI</p>
        <h1>News &amp; Updates</h1>
        <span class="caption-divider"></span>
        <p class="caption-sub">District of Banaybanay, Division of Davao Oriental</p>
    </div>
</div>

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

                <a href="<?php echo get_post_type_archive_link('news') ?: home_url('/news/'); ?>" class="sn-back-btn">
                    <i class="fa fa-arrow-left"></i> Back to News
                </a>

                <div class="sn-share">
                    <span class="sn-share-label"><i class="fa fa-share-alt"></i> Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                       target="_blank" rel="noopener noreferrer" class="sn-share-btn sn-share-fb" title="Share on Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                       target="_blank" rel="noopener noreferrer" class="sn-share-btn sn-share-tw" title="Share on Twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>"
                       class="sn-share-btn sn-share-em" title="Share via Email">
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
     STYLES
     ============================================================ -->
<style>
/* ── Page Body ── */
.single-news-body {
    background: none;
    padding: 56px 0 48px;
}

.single-news-container {
    max-width: 100%;
    margin: 0;
    padding: 0 48px;
}

/* ── Article Card ── */
.single-news-article {
    background: none;
    border-radius: 0;
    overflow: hidden;
    box-shadow: none;
}

/* Header */
.sn-article-header {
    padding: 28px 0 24px;
    border-bottom: 1px solid var(--border);
}

.sn-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.sn-cat-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--deped-light);
    color: var(--deped-blue);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 5px 12px;
    border-radius: 20px;
    border: 1px solid rgba(0,56,168,0.15);
}
.sn-cat-badge .fa { font-size: 10px; }

.sn-title {
    font-size: 30px;
    font-weight: 800;
    color: var(--deped-dark);
    line-height: 1.3;
    margin: 0 0 20px;
}

.sn-meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.sn-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: var(--text-light);
}
.sn-meta-item .fa { color: var(--deped-blue); }

/* Featured Image */
.sn-featured-image {
    width: 100%;
}
.sn-featured-image img {
    width: 100%;
    height: auto;
    display: block;
}

/* Content */
.sn-content {
    padding: 28px 0;
    border-radius: 0;
    box-shadow: none;
    border-bottom: 1px solid var(--border);
}

/* Footer */
.sn-article-footer {
    padding: 24px 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    border-bottom: 1px solid var(--border);
}

.sn-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--deped-blue);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 10px 20px;
    border: 2px solid var(--deped-blue);
    border-radius: 4px;
    transition: background 0.2s, color 0.2s;
}
.sn-back-btn:hover {
    background: var(--deped-blue);
    color: var(--white) !important;
}

.sn-share {
    display: flex;
    align-items: center;
    gap: 10px;
}
.sn-share-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
}
.sn-share-label .fa { color: var(--deped-blue); }

.sn-share-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: var(--white) !important;
    transition: transform 0.2s, opacity 0.2s;
}
.sn-share-btn:hover { transform: scale(1.12); opacity: 0.85; }
.sn-share-fb { background: #1877f2; }
.sn-share-tw { background: #1da1f2; }
.sn-share-em { background: var(--deped-red); }

/* Prev / Next Nav */
.sn-post-nav {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
.sn-nav-prev,
.sn-nav-next { padding: 20px 0; }
.sn-nav-prev { border-right: 1px solid var(--border); }
.sn-nav-next  { text-align: right; }

.sn-nav-prev a,
.sn-nav-next a {
    display: flex;
    flex-direction: column;
    gap: 5px;
    color: var(--text-mid);
    text-decoration: none;
    transition: color 0.2s;
}
.sn-nav-prev a:hover,
.sn-nav-next a:hover { color: var(--deped-blue); }

.sn-nav-dir {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 6px;
}
.sn-nav-next .sn-nav-dir { justify-content: flex-end; }

.sn-nav-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--deped-dark);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ============================================================
   OTHER NEWS — Scrollable
   ============================================================ */
.sn-other-news {
    background: var(--deped-dark);
    padding: 56px 0 72px;
}

.sn-other-container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 0 28px;
}

.sn-other-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-left: 5px solid var(--deped-yellow);
    padding-left: 16px;
    margin-bottom: 32px;
}
.sn-other-heading h2 {
    font-size: 20px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--white);
    margin: 0;
}
.sn-other-heading h2 .fa {
    color: var(--deped-yellow);
    margin-right: 10px;
}
.sn-other-viewall {
    font-size: 13px;
    font-weight: 600;
    color: var(--deped-yellow);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.sn-other-viewall:hover { color: var(--white); }

/* Scroll wrapper */
.sn-other-scroll-wrapper {
    display: flex;
    align-items: center;
    gap: 0;
}

.sn-other-scroll {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 8px 4px 16px;
    flex: 1;
    scrollbar-width: thin;
    scrollbar-color: var(--deped-yellow) rgba(255,255,255,0.1);
}
.sn-other-scroll::-webkit-scrollbar { height: 5px; }
.sn-other-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.08); border-radius: 3px; }
.sn-other-scroll::-webkit-scrollbar-thumb { background: var(--deped-yellow); border-radius: 3px; }

/* Arrow buttons */
.sn-scroll-btn {
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: var(--white);
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s, border-color 0.2s;
}
.sn-scroll-btn:hover {
    background: var(--deped-yellow);
    border-color: var(--deped-yellow);
    color: var(--deped-dark);
}
.sn-scroll-left { margin-right: 12px; }
.sn-scroll-right { margin-left: 12px; }

/* Cards */
.sn-other-card {
    flex: 0 0 260px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    transition: background 0.25s, transform 0.25s, box-shadow 0.25s;
}
.sn-other-card:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.4);
}

.sn-other-thumb {
    position: relative;
    height: 160px;
    overflow: hidden;
    flex-shrink: 0;
    background: rgba(0,0,0,0.2);
}
.sn-other-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.35s ease, filter 0.35s ease;
    filter: brightness(0.8);
}
.sn-other-card:hover .sn-other-thumb img {
    transform: scale(1.06);
    filter: brightness(1);
}
.sn-other-thumb-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,32,91,0.3);
    opacity: 0;
    transition: opacity 0.25s;
}
.sn-other-card:hover .sn-other-thumb-overlay { opacity: 1; }

.sn-other-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sn-other-placeholder .fa {
    font-size: 40px;
    color: rgba(255,255,255,0.15);
}

.sn-other-info {
    padding: 16px 18px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    border-top: 3px solid var(--deped-yellow);
}

.sn-other-date {
    font-size: 11px;
    color: rgba(255,255,255,0.5);
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 8px;
}
.sn-other-date .fa { color: var(--deped-yellow); }

.sn-other-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--white);
    line-height: 1.45;
    margin: 0 0 8px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.sn-other-card:hover .sn-other-title { color: var(--deped-yellow); }

.sn-other-excerpt {
    font-size: 12px;
    color: rgba(255,255,255,0.5);
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .single-news-body { padding: 40px 0 40px; }
    .single-news-container { padding: 0 20px; }
    .sn-article-header { padding: 24px 22px 20px; }
    .sn-title { font-size: 22px; }
    .sn-content { padding: 24px 22px; }
    .sn-article-footer { padding: 18px 0; flex-direction: column; align-items: flex-start; }
    .sn-post-nav .sn-nav-prev,
    .sn-post-nav .sn-nav-next { padding: 16px 20px; }
    .sn-other-news { padding: 40px 0 56px; }
    .sn-other-container { padding: 0 16px; }
    .sn-other-card { flex: 0 0 220px; }
    .sn-other-thumb { height: 130px; }
}
@media only screen and (max-width: 480px) {
    .sn-title { font-size: 19px; }
    .sn-post-nav { grid-template-columns: 1fr; }
    .sn-nav-prev { border-right: none; border-bottom: 1px solid var(--border); }
    .sn-nav-next { text-align: left; }
    .sn-nav-next .sn-nav-dir { justify-content: flex-start; }
    .sn-other-card { flex: 0 0 200px; }
    .sn-scroll-btn { display: none; }
}
</style>

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