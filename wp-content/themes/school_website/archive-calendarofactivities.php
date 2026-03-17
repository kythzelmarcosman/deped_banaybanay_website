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
            <div class="ca-search-wrap">
                <input type="text" id="ca-search" placeholder="Search activities..." autocomplete="off">
                <i class="fa fa-search"></i>
            </div>
        </div>

        <?php if (!empty($grouped)) : ?>

            <div class="ca-no-results" id="ca-no-results" style="display:none;">
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
            <div class="ca-empty">
                <i class="fa fa-calendar-times-o"></i>
                <p>No activities have been published yet.</p>
            </div>
        <?php endif; ?>

    </div><!-- /.ca-container -->
</div><!-- /.ca-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.ca-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.ca-container {
    max-width: 860px;
    margin: 0 auto;
    padding: 0 28px;
}

/* Top bar */
.ca-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border);
    flex-wrap: wrap;
}

.ca-total {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-mid);
}
.ca-total .fa { color: var(--deped-blue); font-size: 16px; }

.ca-search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.ca-search-wrap input {
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
.ca-search-wrap input:focus {
    border-color: var(--deped-blue);
    box-shadow: 0 0 0 3px rgba(0,56,168,0.08);
}
.ca-search-wrap > .fa {
    position: absolute;
    right: 13px;
    color: var(--text-light);
    font-size: 13px;
    pointer-events: none;
}

/* Month group */
.ca-month-group {
    margin-bottom: 14px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

/* Accordion button */
.ca-month-header {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 22px;
    background: var(--deped-blue);
    border: none;
    cursor: pointer;
    text-align: left;
    transition: background 0.2s;
}
.ca-month-header:hover,
.ca-month-header.ca-open { background: var(--deped-dark); }

.ca-month-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 700;
    color: var(--white);
    flex: 1;
}
.ca-month-label .fa { font-size: 14px; opacity: 0.8; }

.ca-month-count {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255,255,255,0.65);
    white-space: nowrap;
}

.ca-chevron {
    color: rgba(255,255,255,0.7);
    font-size: 13px;
    flex-shrink: 0;
    transition: transform 0.25s;
}
.ca-month-header.ca-open .ca-chevron { transform: rotate(180deg); }

/* Activity list */
.ca-activity-list {
    list-style: none;
    margin: 0;
    padding: 0;
    background: var(--white);
}

.ca-activity-item {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 16px 22px;
    border-bottom: 1px solid #f0f3f9;
    transition: background 0.15s;
}
.ca-activity-item:last-child { border-bottom: none; }
.ca-activity-item:hover { background: #f8f9ff; }

/* Date badge */
.ca-date-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    background: var(--deped-light);
    border-radius: 8px;
    border-bottom: 3px solid var(--deped-blue);
    flex-shrink: 0;
}
.ca-date-day {
    font-size: 20px;
    font-weight: 800;
    color: var(--deped-blue);
    line-height: 1;
}
.ca-date-dow {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-light);
    margin-top: 2px;
}

/* Activity info */
.ca-activity-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.ca-activity-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    line-height: 1.4;
}
.ca-activity-desc {
    font-size: 12px;
    color: var(--text-light);
    line-height: 1.5;
}

/* Full date */
.ca-activity-date {
    font-size: 12px;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    flex-shrink: 0;
}
.ca-activity-date .fa { color: var(--deped-blue); }

/* Empty / no results */
.ca-empty,
.ca-no-results {
    text-align: center;
    padding: 80px 24px;
    background: var(--white);
    border-radius: 8px;
    box-shadow: var(--shadow-sm);
    color: var(--text-light);
}
.ca-empty .fa,
.ca-no-results .fa {
    font-size: 52px;
    color: var(--deped-blue);
    opacity: 0.18;
    display: block;
    margin-bottom: 16px;
}
.ca-empty p,
.ca-no-results p { font-size: 15px; }

/* Responsive */
@media only screen and (max-width: 768px) {
    .ca-body     { padding: 40px 0 60px; }
    .ca-container { padding: 0 16px; }
    .ca-topbar   { flex-direction: column; align-items: flex-start; }
    .ca-search-wrap, .ca-search-wrap input { width: 100%; }
    .ca-activity-date { display: none; }
}
@media only screen and (max-width: 480px) {
    .ca-month-header  { padding: 14px 16px; }
    .ca-activity-item { padding: 14px 16px; gap: 12px; }
    .ca-date-badge    { width: 44px; height: 44px; }
    .ca-date-day      { font-size: 17px; }
}
</style>


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