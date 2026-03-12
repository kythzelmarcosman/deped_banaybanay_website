<?php
/**
 * Template Name: Contact Us Page
 *
 * Displays district contact information, email, phone/hotline,
 * and a clickable Google Maps screenshot.
 */
get_header();
?>

<!-- ============================================================
     MAIN BODY
     ============================================================ -->
<div class="ct-body">
    <div class="ct-container">

        <div class="section-heading">
                <h2>Contact Us</h2>
        </div>

        <!-- ── Contact Cards ── -->
        <div class="ct-cards">

            <!-- Email -->
            <div class="ct-card">
                <div class="ct-card-icon ct-icon-blue">
                    <i class="fa fa-envelope"></i>
                </div>
                <div class="ct-card-content">
                    <h3>Email Address</h3>
                    <a href="mailto:banaybanay@deped.gov.ph">banaybanay@deped.gov.ph</a>
                </div>
            </div>

            <!-- Phone / Hotline -->
            <div class="ct-card">
                <div class="ct-card-icon ct-icon-yellow">
                    <i class="fa fa-phone"></i>
                </div>
                <div class="ct-card-content">
                    <h3>Phone / Hotline</h3>
                    <a href="tel:+63XXXXXXXXXX">+63 XXX XXX XXXX</a>
                    <span class="ct-card-note">Monday – Friday, 8:00 AM – 5:00 PM</span>
                </div>
            </div>

            <!-- Address -->
            <div class="ct-card">
                <div class="ct-card-icon ct-icon-red">
                    <i class="fa fa-map-marker"></i>
                </div>
                <div class="ct-card-content">
                    <h3>Office Address</h3>
                    <p>District of Banaybanay<br>
                       Banaybanay, Davao Oriental<br>
                       Philippines</p>
                </div>
            </div>

        </div><!-- /.ct-cards -->

        <!-- ── Map ── -->
        <div class="ct-map-section">
            <h2 class="ct-map-title">
                <i class="fa fa-map"></i> Find Us on the Map
            </h2>
            <a href="https://maps.google.com/?q=Banaybanay,Davao+Oriental,Philippines"
               target="_blank"
               rel="noopener noreferrer"
               class="ct-map-link"
               title="Open in Google Maps">

                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-banaybanay.jpg"
                     alt="Map of Banaybanay, Davao Oriental"
                     class="ct-map-img">

                <div class="ct-map-overlay">
                    <div class="ct-map-overlay-inner">
                        <i class="fa fa-map-marker"></i>
                        <span>Open in Google Maps</span>
                    </div>
                </div>

            </a>
            <p class="ct-map-caption">
                <i class="fa fa-info-circle"></i>
                Click the map to get directions via Google Maps.
            </p>
        </div><!-- /.ct-map-section -->

    </div><!-- /.ct-container -->
</div><!-- /.ct-body -->


<!-- ============================================================
     STYLES
     ============================================================ -->
<style>
.ct-body {
    background: #f0f3f9;
    padding: 56px 0 80px;
}

.ct-container {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 28px;
}

/* ── Contact Cards ── */
.ct-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 48px;
}

.ct-card {
    background: var(--white);
    border-radius: 10px;
    box-shadow: var(--shadow-sm);
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 16px;
    border-top: 4px solid var(--deped-blue);
    transition: box-shadow 0.25s, transform 0.25s;
}
.ct-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
}

.ct-card-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    flex-shrink: 0;
}
.ct-icon-blue   { background: var(--deped-light); color: var(--deped-blue); }
.ct-icon-yellow { background: #fff8e1; color: #e6a800; }
.ct-icon-red    { background: #fff0f0; color: var(--deped-red); }

.ct-card-content h3 {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--text-light);
    margin: 0 0 6px;
}

.ct-card-content a,
.ct-card-content p {
    font-size: 15px;
    font-weight: 600;
    color: var(--deped-dark);
    line-height: 1.6;
    margin: 0;
    word-break: break-word;
}
.ct-card-content a:hover { color: var(--deped-blue); }

.ct-card-note {
    display: block;
    font-size: 12px;
    font-weight: 400;
    color: var(--text-light);
    margin-top: 4px;
}

/* ── Map ── */
.ct-map-section {
    background: var(--white);
    border-radius: 10px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.ct-map-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--deped-dark);
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 22px 28px;
    border-bottom: 1px solid var(--border);
    margin: 0;
}
.ct-map-title .fa { color: var(--deped-blue); }

.ct-map-link {
    display: block;
    position: relative;
    overflow: hidden;
    line-height: 0;
}

.ct-map-img {
    width: 100%;
    height: 380px;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease, filter 0.3s ease;
    filter: saturate(0.9);
}
.ct-map-link:hover .ct-map-img {
    transform: scale(1.03);
    filter: saturate(1.1) brightness(0.85);
}

.ct-map-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 32, 91, 0);
    transition: background 0.3s;
}
.ct-map-link:hover .ct-map-overlay {
    background: rgba(0, 32, 91, 0.35);
}

.ct-map-overlay-inner {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--white);
    color: var(--deped-blue);
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    padding: 14px 26px;
    border-radius: 50px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateY(8px);
    transition: opacity 0.3s, transform 0.3s;
}
.ct-map-link:hover .ct-map-overlay-inner {
    opacity: 1;
    transform: translateY(0);
}
.ct-map-overlay-inner .fa { font-size: 16px; color: var(--deped-red); }

.ct-map-caption {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    color: var(--text-light);
    padding: 14px 28px;
    border-top: 1px solid var(--border);
    margin: 0;
}
.ct-map-caption .fa { color: var(--deped-blue); }

/* ── Responsive ── */
@media only screen and (max-width: 768px) {
    .ct-body { padding: 40px 0 60px; }
    .ct-container { padding: 0 16px; }
    .ct-cards { grid-template-columns: 1fr; gap: 16px; }
    .ct-card { flex-direction: row; text-align: left; padding: 22px 20px; }
    .ct-card-icon { width: 50px; height: 50px; font-size: 20px; flex-shrink: 0; }
    .ct-map-img { height: 260px; }
}

@media only screen and (max-width: 480px) {
    .ct-card { flex-direction: column; text-align: center; }
    .ct-map-img { height: 200px; }
    .ct-map-title { font-size: 14px; padding: 16px 18px; }
    .ct-map-caption { padding: 12px 18px; }
}
</style>

<?php get_footer(); ?>