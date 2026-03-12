<?php
/**
 * The template for displaying the footer
 *
 * @package School_Website
 */
?>

	<footer id="colophon" class="site-footer deped-footer">

		<div class="footer-main">
			<div class="footer-container">

				<!-- Brand -->
				<div class="footer-brand">
					<div class="footer-logo">
						<img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo/DepEd-Circle.png" alt="DepEd Logo">
					</div>
					<div class="footer-brand-text">
						<span class="footer-agency">Department of Education</span>
						<span class="footer-district">District of Banaybanay</span>
						<span class="footer-division">Division of Davao Oriental &bull; Region XI</span>
					</div>
				</div>

				<!-- Quick Links -->
				<div class="footer-col">
					<h4 class="footer-col-title">Official Links</h4>
					<ul class="footer-links">
						<li>
							<a href="https://www.deped.gov.ph" target="_blank" rel="noopener noreferrer">
								<i class="fa fa-external-link"></i> DepEd Central Office
							</a>
						</li>
						<li>
							<a href="https://depedro11.com" target="_blank" rel="noopener noreferrer">
								<i class="fa fa-external-link"></i> Region XI Office
							</a>
						</li>
						<li>
							<a href="https://davaoorientaldepeddivision.com" target="_blank" rel="noopener noreferrer">
								<i class="fa fa-external-link"></i> Division of Davao Oriental
							</a>
						</li>
					</ul>
				</div>

				<!-- Social Media -->
				<div class="footer-col">
					<h4 class="footer-col-title">Follow Us</h4>
					<p class="footer-social-desc">Stay updated with the latest news and announcements from our district.</p>
					<div class="footer-socials">
						<a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" class="footer-social-btn footer-fb">
							<i class="fa fa-facebook"></i>
							<span>Official Facebook Page</span>
						</a>
					</div>
				</div>

			</div><!-- /.footer-container -->
		</div><!-- /.footer-main -->

		<!-- Bottom Bar -->
		<div class="footer-bottom">
			<div class="footer-container">
				<span class="footer-copyright">
					&copy; <?php echo date('Y'); ?> Department of Education &ndash; District of Banaybanay. All rights reserved.
				</span>
				<span class="footer-powered">
					Powered by WordPress and friends <i class="fa fa-heart"></i>
				</span>
			</div>
		</div><!-- /.footer-bottom -->

	</footer><!-- #colophon -->
</div><!-- #page -->

<!-- ============================================================
     FOOTER STYLES
     ============================================================ -->
<style>
.deped-footer {
	background: var(--deped-dark);
	color: rgba(255,255,255,0.75);
	font-size: 14px;
	margin-top: 0;
}

/* Main footer */
.footer-main {
	padding: 56px 0 40px;
	border-bottom: 1px solid rgba(255,255,255,0.08);
}

.footer-container {
	max-width: 1200px;
	margin: 0 auto;
	padding: 0 28px;
	display: grid;
	grid-template-columns: 1.6fr 1fr 1fr;
	gap: 48px;
	align-items: start;
}

/* Brand */
.footer-brand {
	display: flex;
	align-items: flex-start;
	gap: 16px;
}

.footer-logo img {
	width: 75px;
	height: 75px;
	object-fit: contain;
	flex-shrink: 0;
}

.footer-brand-text {
	display: flex;
	flex-direction: column;
	gap: 3px;
}

.footer-agency {
	font-size: 17px;
	font-weight: 800;
	color: var(--white);
	line-height: 1.2;
	letter-spacing: 0.2px;
}

.footer-district {
	font-size: 14px;
	font-weight: 700;
	color: var(--deped-yellow);
}

.footer-division {
	font-size: 12px;
	color: rgba(255,255,255,0.5);
	margin-top: 2px;
}

/* Columns */
.footer-col-title {
	font-size: 11px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 1.2px;
	color: var(--deped-yellow);
	margin: 0 0 18px;
	padding-bottom: 10px;
	border-bottom: 1px solid rgba(255,255,255,0.1);
}

.footer-links {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.footer-links li a {
	display: flex;
	align-items: center;
	gap: 8px;
	color: rgba(255,255,255,0.65);
	font-size: 13px;
	transition: color 0.2s, padding-left 0.2s;
}
.footer-links li a:hover {
	color: var(--white);
	padding-left: 4px;
}
.footer-links li a .fa {
	font-size: 11px;
	color: var(--deped-yellow);
	flex-shrink: 0;
}

/* Social */
.footer-social-desc {
	font-size: 13px;
	color: rgba(255,255,255,0.5);
	line-height: 1.6;
	margin: 0 0 16px;
}

.footer-social-btn {
	display: inline-flex;
	align-items: center;
	gap: 10px;
	padding: 10px 18px;
	border-radius: 5px;
	font-size: 13px;
	font-weight: 700;
	transition: transform 0.2s, opacity 0.2s;
	color: var(--white) !important;
}
.footer-social-btn:hover {
	transform: translateY(-2px);
	opacity: 0.88;
}
.footer-social-btn .fa { font-size: 16px; }
.footer-fb { background: #1877f2; }

/* Bottom bar */
.footer-bottom {
	padding: 18px 0;
}

.footer-bottom .footer-container {
	grid-template-columns: 1fr;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	flex-wrap: wrap;
}

.footer-copyright {
	font-size: 12px;
	color: rgba(255,255,255,0.45);
}

.footer-powered {
	font-size: 12px;
	color: rgba(255,255,255,0.3);
	display: flex;
	align-items: center;
	gap: 5px;
}
.footer-powered .fa {
	color: var(--deped-red);
	font-size: 11px;
}

/* ── Responsive ── */
@media only screen and (max-width: 1024px) {
	.footer-container {
		grid-template-columns: 1fr 1fr;
		gap: 36px;
	}
	.footer-brand {
		grid-column: 1 / -1;
	}
}

@media only screen and (max-width: 640px) {
	.footer-main { padding: 40px 0 32px; }
	.footer-container {
		grid-template-columns: 1fr;
		gap: 32px;
		padding: 0 20px;
	}
	.footer-brand { grid-column: auto; }
	.footer-bottom .footer-container {
		flex-direction: column;
		align-items: flex-start;
		padding: 0 20px;
	}
}
</style>

<?php wp_footer(); ?>

</body>
</html>