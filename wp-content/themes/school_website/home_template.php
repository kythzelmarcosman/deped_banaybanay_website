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
                    <a href="<?php echo esc_url(home_url('/school-forms')); ?>">
                        <span class="hql-icon"><i class="fa fa-clipboard"></i></span>
                        <span class="hql-label">School<br>Forms</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/calendar')); ?>">
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

                <!-- LEFT: Main Content Column -->
                <div class="main-content-col">

                    <!-- Latest News -->
                    <section id="latest-news" class="home-section">
                        <div class="section-heading">
                            <h2><i class="fa fa-newspaper-o"></i> Latest News</h2>
                            <a href="<?php echo esc_url(home_url('/news')); ?>" class="view-all-link">View All <i class="fa fa-arrow-right"></i></a>
                        </div>
                        <div class="news-grid">
                            <?php
                            $news_args = array(
                                'post_type'      => 'news', // use your News post type
                                'posts_per_page' => 3,
                                'post_status'    => 'publish',
                            );
                            $news_query = new WP_Query($news_args);

                            if ($news_query->have_posts()) :
                                while ($news_query->have_posts()) : $news_query->the_post();
                                    $news_image   = get_field('news_image');
                                    $news_content = get_field('news_content');
                                    $news_date    = get_the_date('M j, Y'); // creation date
                            ?>
                                    <div class="news-card">
                                        <?php if ($news_image): ?>
                                            <div class="news-thumb">
                                                <a href="<?php the_permalink(); ?>">
                                                    <img src="<?php echo esc_url($news_image['url']); ?>" alt="<?php the_title(); ?>">
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="news-body">
                                            <span class="news-date"><i class="fa fa-calendar"></i> <?php echo esc_html($news_date); ?></span>
                                            <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                            <p><?php echo wp_trim_words($news_content, 10, '...'); ?></p>
                                        </div>
                                    </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else : ?>
                                <div class="news-card">
                                    <div class="news-body">
                                        <h4>No news found</h4>
                                        <p>There are currently no news articles published in this section.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

<section id="issuances" class="home-section">
    <div class="section-heading">
        <h2><i class="fa fa-file-text-o"></i> District Issuances</h2>
        <a href="<?php echo esc_url(home_url('/issuances')); ?>" class="view-all-link">View All <i class="fa fa-arrow-right"></i></a>
    </div>

    <?php
    $issuances_query = new WP_Query([
        'post_type'      => 'issuance',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
    ?>

    <div class="issuances-panel">

        <div class="issuances-search">
            <input type="text" id="memo-search" placeholder="Search issuances..." autocomplete="off">
            <button type="button"><i class="fa fa-search"></i></button>
        </div>

        <?php if ($issuances_query->have_posts()) : ?>
            <ul class="issuances-list" id="issuances-home-list">
                <?php while ($issuances_query->have_posts()) : $issuances_query->the_post(); ?>
                    <li data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                        <i class="fa fa-file-pdf-o"></i>
                        <div>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <span class="memo-date"><?php echo get_the_date('F j, Y'); ?></span>
                        </div>
                    </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>

            <div id="issuances-home-noresults" style="display:none;" class="issuances-empty">
                <i class="fa fa-search"></i>
                <div>No issuances matched your search.</div>
            </div>
        <?php else : ?>
            <div class="issuances-empty">
                <i class="fa fa-inbox"></i>
                <div>No issuances have been published yet.</div>
            </div>
        <?php endif; ?>

    </div><!-- /.issuances-panel -->
</section>

<script>
(function () {
    var input    = document.getElementById('memo-search');
    var list     = document.getElementById('issuances-home-list');
    var noResult = document.getElementById('issuances-home-noresults');
    if (!input || !list) return;

    input.addEventListener('input', function () {
        var q       = this.value.toLowerCase().trim();
        var items   = list.querySelectorAll('li');
        var visible = 0;
        items.forEach(function (li) {
            var match = !q || (li.dataset.title || '').includes(q);
            li.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        list.style.display      = visible === 0 ? 'none'  : '';
        noResult.style.display  = visible === 0 ? 'block' : 'none';
    });
})();
</script>

<style>
.issuances-empty {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 24px 20px;
    color: var(--text-light);
    font-style: italic;
    font-size: 14px;
}
.issuances-empty .fa {
    color: var(--deped-blue);
    opacity: 0.3;
    font-size: 20px;
}
</style>

                    <!-- Schools Directory -->
                    <section id="schools" class="home-section">
                        <div class="section-heading">
                            <h2><i class="fa fa-building-o"></i> Schools Directory</h2>
                            <a href="<?php echo esc_url(home_url('/schools')); ?>" class="view-all-link">Full Directory <i class="fa fa-arrow-right"></i></a>
                        </div>

                        <div class="schools-scroll-wrapper">
                        <button class="scroll-btn scroll-left"><i class="fa fa-chevron-left"></i></button>

                        <div class="schools-scroll">
                            <!-- School cards loop -->
                            <?php
                            $school_args = array(
                                'post_type' => 'school',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC'
                            );
                            $school_query = new WP_Query($school_args);
                            if($school_query->have_posts()) :
                                while($school_query->have_posts()) : $school_query->the_post();
                                    $contact = get_field('contact_information');
                                    $email = $contact['email'] ?? '';
                                    $contact_number = $contact['contact_number'] ?? '';
                                    $school_image = get_field('school_image');
                            ?>
                            <div class="school-card">
                                <div class="school-img">
                                    <?php if($school_image): ?>
                                        <img src="<?php echo esc_url($school_image['url']); ?>" alt="<?php the_title(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="school-info">
                                    <h5><?php the_title(); ?></h5>
                                    <?php if($contact_number): ?><p><i class="fa fa-phone"></i> <?php echo esc_html($contact_number); ?></p><?php endif; ?>
                                    <?php if($email): ?><p><i class="fa fa-envelope"></i> <?php echo esc_html($email); ?></p><?php endif; ?>
                                </div>
                            </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>

                        <button class="scroll-btn scroll-right"><i class="fa fa-chevron-right"></i></button>
                    </div>
                    </section>

                </div><!-- /.main-content-col -->

                <!-- RIGHT: Sidebar -->
                <aside class="sidebar-col">

                    <!-- Featured Schools -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget-title">Featured Schools</h3>
                        <div class="featured-schools-grid">
                            <a href="#" class="featured-school-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio/item1.jpg" alt="Banaybanay Elementary">
                                <span>Banaybanay Elementary</span>
                            </a>
                            <a href="#" class="featured-school-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio/item2.jpg" alt="Banaybanay Schools">
                                <span>Banaybanay Schools</span>
                            </a>
                            <a href="#" class="featured-school-item">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/portfolio/item3.jpg" alt="District Profile">
                                <span>District Profile</span>
                            </a>
                        </div>
                    </div>

                    <!-- Programs & Projects -->
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget-title">Programs &amp; Projects</h3>
                        <?php
                        $programs_query = new WP_Query([
                            'post_type'      => 'program',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                        ]);
                        ?>
                        <?php if ($programs_query->have_posts()) : ?>
                            <ul class="transparency-list">
                                <?php while ($programs_query->have_posts()) : $programs_query->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-graduation-cap"></i>
                                            <?php the_title(); ?>
                                        </a>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php else : ?>
                            <ul class="transparency-list">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-inbox"></i>
                                        No programs yet.
                                    </a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Transparency Seal -->
                    <div class="sidebar-widget transparency-widget">
                        <h3 class="sidebar-widget-title">Transparency Seal</h3>
                        <?php
                        $transparency_query = new WP_Query([
                            'post_type'      => 'transparency',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                        ]);
                        ?>
                        <?php if ($transparency_query->have_posts()) : ?>
                            <ul class="transparency-list">
                                <?php while ($transparency_query->have_posts()) : $transparency_query->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-file-text-o"></i>
                                            <?php the_title(); ?>
                                        </a>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php else : ?>
                            <ul class="transparency-list">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-inbox"></i>
                                        No documents yet.
                                    </a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>

                </aside><!-- /.sidebar-col -->

            </div><!-- /.home-layout -->
        </div><!-- /.container -->
    </div><!-- /.home-body -->

<script type="text/javascript">
// Issuance tabs
document.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
        document.querySelectorAll('.issuances-list').forEach(function(l) { l.classList.add('issuances-hidden'); });
        btn.classList.add('active');
        var tab = document.getElementById('tab-' + btn.dataset.tab);
        if (tab) tab.classList.remove('issuances-hidden');
    });
});

// Memo search
var searchInput = document.getElementById('memo-search');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        var val = this.value.toLowerCase();
        document.querySelectorAll('.issuances-list:not(.issuances-hidden) li').forEach(function(li) {
            li.style.display = li.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
}
</script>

<?php get_footer(); ?>