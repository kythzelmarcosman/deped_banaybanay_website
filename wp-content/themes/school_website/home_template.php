<?php /* Template Name: Home Page */

get_header(); ?>

    <!-- Hero Section: Slider + Quick Links side by side -->
    <div class="hero-section">

        <!-- Hero Slider -->
        <div class="flexslider hero-slider">
            <?php 
        $hero = get_field('hero_images');
            if( $hero ): ?>
            <ul class="slides">
                <li>
                    <?php if( $hero['image_1'] ): ?>
                        <img src="<?php echo esc_url($hero['image_1']['url']); ?>" alt="">
                    <?php endif; ?>
                    <div class="flex-caption">
                        <div class="caption-inner">
                            <p class="caption-eyebrow">Department of Education</p>
                            <h2>Empowering Learners,<br>Building Futures</h2>
                            <span class="caption-divider"></span>
                            <p class="caption-sub">Welcome to the District of Banaybanay</p>
                            <a href="#about" class="hero-btn">Explore Our District</a>
                        </div>
                    </div>
                </li>
                <li>
                    <?php if( $hero['image_2'] ): ?>
                        <img src="<?php echo esc_url($hero['image_2']['url']); ?>" alt="">
                    <?php endif; ?>
                    <div class="flex-caption">
                        <div class="caption-inner">
                            <p class="caption-eyebrow">Region XI | Division of Davao Oriental</p>
                            <h2>Quality Education<br>For Every Child</h2>
                            <span class="caption-divider"></span>
                            <p class="caption-sub">Building a Better Community Through Learning</p>
                            <a href="<?php echo esc_url(home_url('/schools')); ?>" class="hero-btn">View Schools</a>
                        </div>
                    </div>
                </li>
            </ul>
            <?php endif; ?>
        </div>

        <!-- Quick Links Panel (right side of slider) -->
        <div class="hero-quicklinks">
            <div class="hero-ql-title"><i class="fa fa-th-large"></i> Quick Links</div>
            <ul class="hero-ql-list">
                <li>
                    <a href="<?php echo esc_url(home_url('/issuances')); ?>">
                        <span class="hql-icon"><i class="fa fa-file-text"></i></span>
                        <span class="hql-label">District<br>Memoranda</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/school-form')); ?>">
                        <span class="hql-icon"><i class="fa fa-clipboard"></i></span>
                        <span class="hql-label">School<br>Forms</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/calendar-of-activity')); ?>">
                        <span class="hql-icon"><i class="fa fa-calendar"></i></span>
                        <span class="hql-label">Calendar of<br>Activities</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/news')); ?>">
                        <span class="hql-icon"><i class="fa fa-bullhorn"></i></span>
                        <span class="hql-label">Latest<br>News</span>
                    </a>
                </li>
            </ul>
        </div>

    </div><!-- /.hero-section -->

<!-- Main Home Body -->
    <div class="home-body">
        <div class="container">
            <div class="home-layout">

                <!-- LEFT: Latest News — Static 3 cards, no slider -->
                <div class="main-content-col">
                    <section id="latest-news" class="home-section">
                        <div class="section-heading">
                            <h2><i class="fa fa-newspaper-o"></i> Latest News</h2>
                            <a href="<?php echo esc_url(home_url('/news')); ?>" class="view-all-link">View All <i class="fa fa-arrow-right"></i></a>
                        </div>

                        <?php
                        $news_args = array(
                            'post_type'      => 'news',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                        );
                        $news_query = new WP_Query($news_args);
                        ?>

                        <?php if ($news_query->have_posts()) : ?>
                            <div class="swiper news-swiper">
                                <div class="swiper-wrapper">
                                    <?php while ($news_query->have_posts()) : $news_query->the_post();
                                        $news_image   = get_field('news_image');
                                        $news_content = get_field('news_content');
                                        $news_date    = get_the_date('M j, Y');
                                    ?>
                                        <div class="swiper-slide">
                                            <div class="news-card">
                                                <?php if ($news_image) : ?>
                                                    <div class="news-thumb">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <img src="<?php echo esc_url($news_image['url']); ?>" alt="<?php the_title_attribute(); ?>">
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="news-body">
                                                    <span class="news-date">
                                                        <i class="fa fa-calendar"></i>
                                                        <?php echo esc_html($news_date); ?>
                                                    </span>
                                                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                    <p><?php echo wp_trim_words($news_content, 10, '...'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>

                                <div class="swiper-button-prev news-prev"></div>
                                <div class="swiper-button-next news-next"></div>
                                <div class="swiper-pagination news-pagination"></div>
                            </div>

                        <?php else : ?>
                            <div class="news-card">
                                <div class="news-body">
                                    <h4>No news found</h4>
                                    <p>There are currently no news articles published.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                    </section>
                </div><!-- /.main-content-col -->

                <!-- RIGHT: Featured Schools — Scrollable 2x2 grid -->
                <aside class="sidebar-col">
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget-title">Featured Schools</h3>

                        <?php
                        $featured_schools_query = new WP_Query([
                            'post_type'      => 'featured-school',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ]);
                        ?>

                        <?php if ($featured_schools_query->have_posts()) : ?>
                            <div class="swiper schools-swiper">
                                <div class="swiper-wrapper">
                                    <?php while ($featured_schools_query->have_posts()) : $featured_schools_query->the_post();
                                        $school_image = function_exists('get_field') ? get_field('image') : null;
                                        $description  = function_exists('get_field') ? get_field('description') : '';
                                        $image_url    = '';
                                        $image_alt    = get_the_title();

                                        if ($school_image && is_array($school_image)) {
                                            $image_url = esc_url($school_image['url']);
                                            $image_alt = !empty($school_image['alt']) ? esc_attr($school_image['alt']) : $image_alt;
                                        } elseif ($school_image && is_string($school_image)) {
                                            $image_url = esc_url($school_image);
                                        }

                                        $fallback = get_template_directory_uri() . '/assets/images/school-placeholder.jpg';
                                    ?>
                                        <div class="swiper-slide">
                                            <a href="<?php the_permalink(); ?>" class="featured-school-item" title="<?php the_title_attribute(); ?>">
                                                <img src="<?php echo $image_url ?: $fallback; ?>" alt="<?php echo $image_alt; ?>">
                                                <div class="featured-school-caption">
                                                    <span class="caption-name"><?php the_title(); ?></span>
                                                    <?php if ($description) : ?>
                                                        <span class="caption-desc"><?php echo esc_html($description); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>

                                <div class="swiper-button-prev schools-prev"></div>
                                <div class="swiper-button-next schools-next"></div>
                                <div class="swiper-pagination schools-pagination"></div>
                            </div>
                        <?php else : ?>
                            <p class="sidebar-empty">No featured schools yet.</p>
                        <?php endif; ?>
                    </div>
                </aside>

            </div><!-- /.home-layout -->
        </div><!-- /.container -->
    </div><!-- /.home-body -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    // News Swiper — 3 cards per page, 2x3 grid layout
    new Swiper('.news-swiper', {
        slidesPerView: 3,
        slidesPerGroup: 3,
        spaceBetween: 16,
        loop: true,
        observer: true,
        observeParents: true,
        grid: {
            rows: 2,
            fill: 'row',
        },
        pagination: {
            el: '.news-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.news-next',
            prevEl: '.news-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 1,
                slidesPerGroup: 1,
                grid: { rows: 2, fill: 'row' },
                spaceBetween: 12,
            },
            640: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                grid: { rows: 2, fill: 'row' },
                spaceBetween: 14,
            },
            1024: {
                slidesPerView: 3,
                slidesPerGroup: 3,
                grid: { rows: 2, fill: 'row' },
                spaceBetween: 16,
            },
        },
    });

    // Schools Swiper — 2x2 grid layout
    new Swiper('.schools-swiper', {
        slidesPerView: 2,
        slidesPerGroup: 2,
        spaceBetween: 8,
        loop: true,
        observer: true,
        observeParents: true,
        grid: {
            rows: 2,
            fill: 'row',
        },
        pagination: {
            el: '.schools-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.schools-next',
            prevEl: '.schools-prev',
        },
        breakpoints: {
            0: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                grid: { rows: 2, fill: 'row' },
                spaceBetween: 6,
            },
            768: {
                slidesPerView: 2,
                slidesPerGroup: 2,
                grid: { rows: 2, fill: 'row' },
                spaceBetween: 8,
            },
        },
    });

});
</script>

<?php get_footer(); ?>