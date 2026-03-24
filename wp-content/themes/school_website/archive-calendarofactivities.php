<?php
/**
 * Archive Template: Calendar of Activities
 * Custom Post Type: calendar_of_activities
 * ACF Fields:
 *   - description  (textarea)
 *   - date_picker  (date picker, return format: F j, Y)
 */
get_header();

$activities_query = new WP_Query([
    'post_type'      => 'calendarofactivities',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_key'       => 'date_picker',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);

// Group activities by "Month Year" (e.g. "January 2026")
$grouped = [];

if ($activities_query->have_posts()) {
    while ($activities_query->have_posts()) : $activities_query->the_post();
        $raw_date    = function_exists('get_field') ? get_field('date_picker') : '';
        $description = function_exists('get_field') ? get_field('description') : '';

        // ACF date picker with return format F j, Y → parse to timestamp
        $timestamp  = $raw_date ? strtotime($raw_date) : get_the_time('U');
        $month_year = $timestamp ? date('F Y', $timestamp) : 'Undated';
        $day        = $timestamp ? date('j',   $timestamp) : '';
        $day_name   = $timestamp ? date('D',   $timestamp) : '';

        $grouped[$month_year][] = [
            'title'       => get_the_title(),
            'description' => $description,
            'date_raw'    => $raw_date,
            'day'         => $day,
            'day_name'    => $day_name,
            'timestamp'   => $timestamp,
        ];
    endwhile;
    wp_reset_postdata();
}

$total              = $activities_query->found_posts;
$current_month_year = date('F Y');
?>

<!-- Breadcrumb -->
<div class="page-breadcrumb">
    <div class="container">
        <span><a href="<?php echo home_url('/'); ?>"><i class="fa fa-home"></i> Home</a></span>
        <span class="bc-sep"><i class="fa fa-angle-right"></i></span>
        <span class="bc-current">Calendar of Activities</span>
    </div>
</div>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="ca-body">
    <div class="ca-container">

        <!-- Top bar -->
        <div class="ca-topbar">
            <span class="ca-total">
                <i class="fa fa-calendar-check-o"></i>
                <?php echo $total; ?> activit<?php echo $total !== 1 ? 'ies' : 'y'; ?>
            </span>
            <div class="search-wrap">
                <input type="text" id="ca-search" placeholder="Search activities..." autocomplete="off">
                <i class="fa fa-search"></i>
            </div>
        </div>

        <?php if (!empty($grouped)) : ?>

            <div class="empty-state" id="ca-no-results" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No activities matched your search.</p>
            </div>

            <div id="ca-groups">
                <?php foreach ($grouped as $month_year => $activities) :
                    $is_current = ($month_year === $current_month_year);
                    $group_id   = 'ca-group-' . sanitize_title($month_year);
                ?>

                    <div class="ca-month-group" data-month="<?php echo esc_attr(strtolower($month_year)); ?>">

                        <button class="ca-month-header <?php echo $is_current ? 'ca-open' : ''; ?>"
                                aria-expanded="<?php echo $is_current ? 'true' : 'false'; ?>"
                                data-target="<?php echo $group_id; ?>">
                            <span class="ca-month-label">
                                <i class="fa fa-calendar"></i>
                                <?php echo esc_html($month_year); ?>
                            </span>
                            <span class="ca-month-count">
                                <?php echo count($activities); ?> activit<?php echo count($activities) !== 1 ? 'ies' : 'y'; ?>
                            </span>
                            <i class="fa fa-chevron-down ca-chevron"></i>
                        </button>

                        <ul class="ca-activity-list"
                            id="<?php echo $group_id; ?>"
                            <?php echo $is_current ? '' : 'style="display:none;"'; ?>>

                            <?php foreach ($activities as $activity) : ?>
                                <li class="ca-activity-item"
                                    data-title="<?php echo esc_attr(strtolower($activity['title'])); ?>">

                                    <div class="ca-date-badge">
                                        <span class="ca-date-day"><?php echo esc_html($activity['day']); ?></span>
                                        <span class="ca-date-dow"><?php echo esc_html($activity['day_name']); ?></span>
                                    </div>

                                    <div class="ca-activity-info">
                                        <span class="ca-activity-title"><?php echo esc_html($activity['title']); ?></span>
                                        <?php if ($activity['description']) : ?>
                                            <span class="ca-activity-desc">
                                                <?php echo esc_html(wp_trim_words(strip_tags($activity['description']), 20, '...')); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <span class="ca-activity-date">
                                        <i class="fa fa-calendar-o"></i>
                                        <?php echo esc_html($activity['date_raw']); ?>
                                    </span>

                                </li>
                            <?php endforeach; ?>

                        </ul>

                    </div><!-- /.ca-month-group -->

                <?php endforeach; ?>
            </div><!-- /#ca-groups -->

        <?php else : ?>
            <div class="empty-state">
                <i class="fa fa-calendar-times-o"></i>
                <p>No activities have been published yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.ca-container -->
</div><!-- /.ca-body -->


<!-- ============================================================
     JAVASCRIPT — Accordion + Live Search
     ============================================================ -->
<script>
(function () {

    // ── Accordion ──
    document.querySelectorAll('.ca-month-header').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = document.getElementById(this.dataset.target);
            if (!target) return;

            var isOpen = this.classList.contains('ca-open');
            if (isOpen) {
                this.classList.remove('ca-open');
                this.setAttribute('aria-expanded', 'false');
                target.style.display = 'none';
            } else {
                this.classList.add('ca-open');
                this.setAttribute('aria-expanded', 'true');
                target.style.display = '';
            }
        });
    });

    // ── Live Search ──
    var input    = document.getElementById('ca-search');
    var groups   = document.getElementById('ca-groups');
    var noResult = document.getElementById('ca-no-results');
    if (!input) return;

    input.addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();

        if (!q) {
            // Restore accordion state
            document.querySelectorAll('.ca-month-group').forEach(function (group) {
                group.style.display = '';
                var isOpen = group.querySelector('.ca-month-header.ca-open');
                var list   = group.querySelector('.ca-activity-list');
                if (list) list.style.display = isOpen ? '' : 'none';
                group.querySelectorAll('.ca-activity-item').forEach(function (item) {
                    item.style.display = '';
                });
            });
            if (groups)   groups.style.display  = '';
            if (noResult) noResult.style.display = 'none';
            return;
        }

        var anyVisible = false;

        document.querySelectorAll('.ca-month-group').forEach(function (group) {
            var items      = group.querySelectorAll('.ca-activity-item');
            var groupMatch = false;

            items.forEach(function (item) {
                var match = (item.dataset.title || '').includes(q);
                item.style.display = match ? '' : 'none';
                if (match) groupMatch = true;
            });

            group.style.display = groupMatch ? '' : 'none';

            if (groupMatch) {
                anyVisible = true;
                var list = group.querySelector('.ca-activity-list');
                var btn  = group.querySelector('.ca-month-header');
                if (list) list.style.display = '';
                if (btn)  btn.classList.add('ca-open');
            }
        });

        if (groups)   groups.style.display  = anyVisible ? '' : 'none';
        if (noResult) noResult.style.display = anyVisible ? 'none' : 'block';
    });

})();
</script>

<?php get_footer(); ?>