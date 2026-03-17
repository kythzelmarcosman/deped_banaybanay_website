<?php get_header(); ?>

<style>
/* ── Google Font ── */
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@400;500&display=swap');

/* ── CSS Variables ── */
:root {
    --primary:   #1a3c5e;
    --accent:    #e07b39;
    --bg:        #f4f6f9;
    --card-bg:   #ffffff;
    --text:      #2d3748;
    --muted:     #718096;
    --border:    #e2e8f0;
    --radius:    12px;
    --shadow:    0 4px 20px rgba(0,0,0,.08);
    --shadow-hover: 0 8px 30px rgba(0,0,0,.14);
    --transition: .25s ease;
}

/* ── Reset / Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); }

/* ── Layout ── */
.home-body   { padding: 40px 0 60px; }
/* .container   { max-width: 1200px; margin: 0 auto; padding: 0 20px; } */

/* ── Section Heading ── */
#schools .section-heading {
    margin-bottom: 32px;
    border-left: 4px solid var(--accent);
    padding-left: 14px;
}
#schools .section-heading h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem, 3vw, 2rem);
    color: var(--primary);
    display: flex;
    align-items: center;
    gap: 10px;
}
#schools .section-heading h2 i { color: var(--accent); }

/* ── Grid ── */
.schools-row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 24px;
}

/* ── Card ── */
.school-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow var(--transition), transform var(--transition);
}
.school-card:hover {
    box-shadow: var(--shadow-hover);
    transform: translateY(-3px);
}

/* Card image */
.school-img {
    width: 100%;
    height: 190px;
    overflow: hidden;
    background: #dde3ec;
    flex-shrink: 0;
}
.school-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.school-card:hover .school-img img { transform: scale(1.04); }

/* Card body */
.school-info {
    /* padding: 20px; */
    display: flex;
    flex-direction: column;
    /* gap: 3px; */
    flex: 1;
}
.school-info h5 {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    color: var(--primary);
    line-height: 1.3;
}
.school-info p {
    font-size: .875rem;
    color: var(--text);
    line-height: 1.5;
    display: flex;
    align-items: flex-start;
    gap: 3px;
}
.school-info p strong { color: var(--primary); white-space: nowrap; }
.school-info p i { color: var(--accent); margin-top: 2px; flex-shrink: 0; }

/* Contact label */
.contact-label {
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--muted);
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid var(--border);
}

/* Map thumbnail */
.school-map {
    margin-top: 12px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid var(--border);
}
.school-map img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
    transition: transform .4s ease;
}
.school-map:hover img { transform: scale(1.03); }

/* ── Loading Overlay ── */
#schools-loading {
    display: none;
    text-align: center;
    padding: 40px 0;
    color: var(--muted);
    font-size: .9rem;
    grid-column: 1 / -1;
}
#schools-loading .spinner {
    width: 36px; height: 36px;
    border: 3px solid var(--border);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin .7s linear infinite;
    margin: 0 auto 10px;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Pagination ── */
.schools-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 40px;
    flex-wrap: wrap;
}
.schools-pagination button {
    min-width: 40px;
    height: 40px;
    padding: 0 14px;
    border: 2px solid var(--border);
    border-radius: 8px;
    background: var(--card-bg);
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    font-size: .875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition);
}
.schools-pagination button:hover:not(:disabled) {
    border-color: var(--accent);
    color: var(--accent);
}
.schools-pagination button.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}
.schools-pagination button:disabled {
    opacity: .4;
    cursor: default;
}

/* ── Mobile ── */
@media (max-width: 640px) {
    .home-body { padding: 24px 0 40px; }

    .schools-row {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .school-card {
        flex-direction: row;
        min-height: unset;
    }

    /* Stacked on very small screens */
    @media (max-width: 420px) {
        .school-card { flex-direction: column; }
        .school-img  { height: 160px; width: 100%; }
    }

    .school-img {
        width: 110px;
        height: auto;
        min-height: 140px;
        flex-shrink: 0;
        border-radius: 0;
    }

    .school-map img { height: 90px; }

    .schools-pagination button {
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        font-size: .8rem;
    }
}
</style>

<div class="home-body">
<div class="container">

<section id="schools" class="home-section">

    <div class="section-heading">
        <h2><i class="fa fa-building-o"></i> Schools</h2>
    </div>

    <div class="schools-row" id="schools-grid">

        <!-- Loading spinner (visible during AJAX) -->
        <div id="schools-loading">
            <div class="spinner"></div>
            Loading schools…
        </div>

        <?php
        $paged       = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $per_page    = 6; // ← adjust cards per page

        $args = array(
            'post_type'      => 'school',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
            'orderby'        => 'title',
            'order'          => 'ASC',
        );

        $query       = new WP_Query($args);
        $total_pages = $query->max_num_pages;

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();

                $school_image   = get_field('school_image');
                $school_id      = get_field('school_id');
                $school_head    = get_field('school_head');
                $address        = get_field('address');
                $map_image      = get_field('map_image');
                $map_link       = get_field('map_link');

                $contact        = get_field('contact_information');
                $email          = $contact['email']          ?? '';
                $contact_number = $contact['contact_number'] ?? '';
        ?>

        <div class="school-card">

            <div class="school-img">
                <?php if ( $school_image ) : ?>
                    <img src="<?php echo esc_url( $school_image['url'] ); ?>"
                         alt="<?php the_title_attribute(); ?>"
                         loading="lazy">
                <?php endif; ?>
            </div>

            <div class="school-info">

                <h5><?php the_title(); ?></h5>

                <?php if ( $school_id ) : ?>
                <p><strong>School ID:</strong> <?php echo esc_html( $school_id ); ?></p>
                <?php endif; ?>

                <?php if ( $school_head ) : ?>
                <p><strong>School Head:</strong> <?php echo esc_html( $school_head ); ?></p>
                <?php endif; ?>

                <?php if ( $contact_number || $email ) : ?>
                <p class="contact-label">Contact</p>
                <?php endif; ?>

                <?php if ( $contact_number ) : ?>
                <p><i class="fa fa-phone"></i> <?php echo esc_html( $contact_number ); ?></p>
                <?php endif; ?>

                <?php if ( $email ) : ?>
                <p><i class="fa fa-envelope"></i> <?php echo esc_html( $email ); ?></p>
                <?php endif; ?>

                <?php if ( $address ) : ?>
                <p><i class="fa fa-map-marker"></i> <?php echo esc_html( $address ); ?></p>
                <?php endif; ?>

                <?php if ( $map_image ) : ?>
                <div class="school-map">
                    <?php if ( $map_link ) : ?>
                        <a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url( $map_image['url'] ); ?>" alt="Map – <?php the_title_attribute(); ?>" loading="lazy">
                        </a>
                    <?php else : ?>
                        <img src="<?php echo esc_url( $map_image['url'] ); ?>" alt="Map – <?php the_title_attribute(); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div><!-- .school-info -->

        </div><!-- .school-card -->

        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No schools found.</p>';
        endif;
        ?>

    </div><!-- #schools-grid -->

    <!-- ── Pagination ── -->
    <?php if ( $total_pages > 1 ) : ?>
    <div class="schools-pagination" id="schools-pagination"
         data-total="<?php echo (int) $total_pages; ?>"
         data-current="<?php echo (int) $paged; ?>"
         data-per-page="<?php echo (int) $per_page; ?>"
         data-ajax-url="<?php echo esc_url( admin_url('admin-ajax.php') ); ?>"
         data-nonce="<?php echo wp_create_nonce('schools_pagination'); ?>">
    </div>
    <?php endif; ?>

</section>

</div><!-- .container -->
</div><!-- .home-body -->

<script>
(function () {
    const grid       = document.getElementById('schools-grid');
    const paginWrap  = document.getElementById('schools-pagination');
    const loading    = document.getElementById('schools-loading');

    if (!paginWrap) return;

    let current    = parseInt(paginWrap.dataset.current,  10);
    let total      = parseInt(paginWrap.dataset.total,    10);
    const perPage  = parseInt(paginWrap.dataset.perPage,  10);
    const ajaxUrl  = paginWrap.dataset.ajaxUrl;
    const nonce    = paginWrap.dataset.nonce;

    /* ── Build pagination buttons ── */
    function buildPagination(cur) {
        paginWrap.innerHTML = '';

        const makeBtn = (label, page, isActive = false, isDisabled = false) => {
            const btn = document.createElement('button');
            btn.innerHTML = label;
            if (isActive)   btn.classList.add('active');
            if (isDisabled) btn.disabled = true;
            if (!isDisabled && !isActive) {
                btn.addEventListener('click', () => goToPage(page));
            }
            return btn;
        };

        // Prev
        paginWrap.appendChild(makeBtn('&laquo;', cur - 1, false, cur === 1));

        // Page numbers (show up to 5 around current)
        const range = [];
        for (let p = Math.max(1, cur - 2); p <= Math.min(total, cur + 2); p++) range.push(p);
        if (range[0] > 1) {
            paginWrap.appendChild(makeBtn('1', 1));
            if (range[0] > 2) paginWrap.appendChild(makeBtn('…', null, false, true));
        }
        range.forEach(p => paginWrap.appendChild(makeBtn(p, p, p === cur)));
        if (range[range.length - 1] < total) {
            if (range[range.length - 1] < total - 1) paginWrap.appendChild(makeBtn('…', null, false, true));
            paginWrap.appendChild(makeBtn(total, total));
        }

        // Next
        paginWrap.appendChild(makeBtn('&raquo;', cur + 1, false, cur === total));
    }

    /* ── Fetch page via AJAX ── */
    function goToPage(page) {
        if (page < 1 || page > total || page === current) return;

        // Show spinner, hide cards
        loading.style.display = 'block';
        Array.from(grid.querySelectorAll('.school-card')).forEach(c => c.style.display = 'none');

        const params = new URLSearchParams({
            action:    'load_schools_page',
            paged:     page,
            per_page:  perPage,
            nonce:     nonce,
        });

        fetch(ajaxUrl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    params.toString(),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Replace grid content (keep loading div)
                Array.from(grid.querySelectorAll('.school-card')).forEach(c => c.remove());
                grid.insertAdjacentHTML('beforeend', data.data.html);
                current = page;
                buildPagination(current);
                window.scrollTo({ top: document.getElementById('schools').offsetTop - 20, behavior: 'smooth' });
            }
        })
        .catch(console.error)
        .finally(() => { loading.style.display = 'none'; });
    }

    // Initial render
    buildPagination(current);
})();
</script>

<?php get_footer(); ?>