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
    // Tab groups — keys must match the ACF select field values exactly
    $issuance_types = [
        'District Memoranda' => [ 'label' => 'District Memos',    'tab' => 'district' ],
        'Division Memoranda' => [ 'label' => 'Division Memos',    'tab' => 'division' ],
        'DepEd Advisory'      => [ 'label' => 'DepEd Advisories',  'tab' => 'advisory' ],
    ];

    // Single query — fetch all recent issuances at once
    $issuances_query = new WP_Query([
        'post_type'      => 'issuance',
        'posts_per_page' => 60,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    // Group by Type ACF field
    $grouped = array_fill_keys(array_keys($issuance_types), []);

    if ($issuances_query->have_posts()) {
        while ($issuances_query->have_posts()) {
            $issuances_query->the_post();
            $type = function_exists('get_field') ? get_field('type') : '';
            if ($type && isset($grouped[$type])) {
                $grouped[$type][] = [
                    'title'     => get_the_title(),
                    'permalink' => get_permalink(),
                    'date'      => get_the_date('F j, Y'),
                ];
            }
        }
        wp_reset_postdata();
    }

    // First tab that has items becomes the default active tab
    $first_active = array_key_first($issuance_types);
    foreach ($issuance_types as $key => $info) {
        if (!empty($grouped[$key])) { $first_active = $key; break; }
    }
    ?>

    <div class="issuances-panel">

        <div class="issuances-search">
            <input type="text" id="memo-search" placeholder="Search issuances...">
            <button type="button"><i class="fa fa-search"></i></button>
        </div>

        <div class="issuances-tabs">
            <?php foreach ($issuance_types as $key => $info) : ?>
                <button
                    class="tab-btn <?php echo $key === $first_active ? 'active' : ''; ?>"
                    data-tab="<?php echo esc_attr($info['tab']); ?>"
                >
                    <?php echo esc_html($info['label']); ?>
                    <?php if (!empty($grouped[$key])) : ?>
                        <span class="tab-count"><?php echo count($grouped[$key]); ?></span>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <?php foreach ($issuance_types as $key => $info) :
            $items     = $grouped[$key];
            $is_active = ($key === $first_active);
        ?>
            <ul
                class="issuances-list <?php echo $is_active ? '' : 'issuances-hidden'; ?>"
                id="tab-<?php echo esc_attr($info['tab']); ?>"
            >
                <?php if (!empty($items)) : ?>
                    <?php foreach ($items as $item) : ?>
                        <li>
                            <i class="fa fa-file-pdf-o"></i>
                            <div>
                                <a href="<?php echo esc_url($item['permalink']); ?>">
                                    <?php echo esc_html($item['title']); ?>
                                </a>
                                <span class="memo-date"><?php echo esc_html($item['date']); ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="issuances-empty">
                        <i class="fa fa-inbox"></i>
                        <div>No <?php echo esc_html($info['label']); ?> available yet.</div>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endforeach; ?>

    </div><!-- /.issuances-panel -->
</section>

<?php /* ── Add this CSS once (e.g. in style.css) ── */ ?>
<style>
.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    border-radius: 10px;
    background: var(--deped-blue);
    color: var(--white);
    font-size: 11px;
    font-weight: 700;
    margin-left: 6px;
    line-height: 1;
}
.tab-btn.active .tab-count {
    background: var(--white);
    color: var(--deped-blue);
}
.issuances-empty {
    color: var(--text-light);
    font-style: italic;
    padding: 20px !important;
}
.issuances-empty .fa {
    color: var(--deped-blue);
    opacity: 0.25;
    font-size: 20px !important;
    margin-top: 0 !important;
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
                        <div class="programs-grid">
                            <a href="#" class="program-item">
                                <span class="prog-icon prog-k12">K-12</span>
                                <span>K-12</span>
                            </a>
                            <a href="#" class="program-item">
                                <span class="prog-icon"><i class="fa fa-book"></i></span>
                                <span>Curriculum</span>
                            </a>
                            <a href="#" class="program-item">
                                <span class="prog-icon prog-als">ALS</span>
                                <span>ALS</span>
                            </a>
                            <a href="#" class="program-item">
                                <span class="prog-icon prog-sbm">SBM</span>
                                <span>SBM</span>
                            </a>
                            <a href="#" class="program-item">
                                <span class="prog-icon"><i class="fa fa-users"></i></span>
                                <span>Brigada Eskwela</span>
                            </a>
                            <a href="#" class="program-item">
                                <span class="prog-icon"><i class="fa fa-institution"></i></span>
                                <span>SGC</span>
                            </a>
                        </div>
                    </div>

                    <!-- Transparency Seal -->
                    <div class="sidebar-widget transparency-widget">
                        <h3 class="sidebar-widget-title">Transparency Seal</h3>
                        <ul class="transparency-list">
                            <li><a href="#"><i class="fa fa-id-card-o"></i> Citizens Charter</a></li>
                            <li><a href="#"><i class="fa fa-bar-chart"></i> Financial Reports</a></li>
                            <li><a href="#"><i class="fa fa-shopping-cart"></i> Procurement</a></li>
                            <li><a href="#"><i class="fa fa-sitemap"></i> Org. Structure</a></li>
                        </ul>
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