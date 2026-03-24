<?php get_header(); ?>

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