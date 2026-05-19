<?php
/**
 * The header for our theme.
 * @package Oceanly Child
 */

	$oceanly_site_header_class = ( Oceanly\Helpers::site_header_b_margin() ? 'site-header u-b-margin' : 'site-header' );

	$product_home_url = '';
	$ph_page = get_page_by_path('product-home');
	if (!$ph_page) $ph_page = get_page_by_path('product_home');
	$product_home_url = $ph_page ? get_permalink($ph_page->ID) : home_url('/product-home/');

	$is_product_home = is_page('product-home');
	global $post;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'oceanly' ); ?></a>

	<!-- ─── HEADER : uniquement le hero/image, PAS le sticky ─── -->
	<header id="masthead" class="<?php echo esc_attr( $oceanly_site_header_class ); ?>">
		<?php do_action( 'oceanly_after_header_start' ); ?>

		<?php do_action( 'oceanly_before_header_end' ); ?>
	</header>
	<!-- ─── FIN HEADER ─── -->

	<!-- ─── STICKY HEADER : EN DEHORS du <header>, colle au viewport ─── -->
	<div class="sticky-header-outer">
		<?php get_template_part('template-parts/header/sticky', 'header'); ?>
	</div>

	<?php if ( !$is_product_home && !is_cart() && !is_checkout() && !is_account_page() && !is_product_category()  && !is_shop() && !is_product()) : ?>
		<?php get_template_part('template-parts/image-home'); ?>
	<?php endif; ?>
	<!-- ===== JAVASCRIPT ===== -->
	<script>
		(function() {

			const filterInput     = document.getElementById('topFilterInput');
			const filterClear     = document.getElementById('filterClear');
			const menuItems       = document.querySelectorAll('#mainCategoriesList .menu-item');
			const filterWrap      = document.querySelector('.top-filter-search');
			const productHomeUrl  = <?php echo json_encode($product_home_url); ?>;
			const isProductHome   = <?php echo $is_product_home ? 'true' : 'false'; ?>;

			function isMobile() { return window.innerWidth <= 768; }

			function buildSearchUrl(name, cat) {
				let url = productHomeUrl;
				const params = new URLSearchParams();
				if (name) params.set('product_name', name);
				if (cat)  params.set('product_cat', cat);
				const qs = params.toString();
				return qs ? url + (url.includes('?') ? '&' : '?') + qs : url;
			}

			if (filterInput) {
				filterInput.addEventListener('input', function() {
					const val = this.value.toLowerCase().trim();
					filterClear.classList.toggle('visible', val.length > 0);
				});

				filterInput.addEventListener('keydown', function(e) {
					if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
					if (e.key === 'Escape' && isMobile()) { closeMobileSearch(); }
				});
			}

			const filterIcon = document.querySelector('.filter-icon-btn');
			if (filterIcon) {
				filterIcon.addEventListener('click', function(e) {
					e.stopPropagation();
					if (isMobile() && !filterWrap.classList.contains('mobile-open')) {
						filterWrap.classList.add('mobile-open');
						filterInput.style.display = 'block';
						filterInput.style.opacity = '1';
						filterInput.focus();
						return;
					}
					doSearch();
				});
			}

			function doSearch() {
				const val = filterInput ? filterInput.value.trim() : '';
				if (isProductHome) {
					const pageInput = document.getElementById('ph_product_name');
					if (pageInput) {
						pageInput.value = val;
						pageInput.closest('form').submit();
					} else {
						window.location.href = buildSearchUrl(val, '');
					}
				} else {
					window.location.href = buildSearchUrl(val, '');
				}
			}

			/* ── Menu scroll arrows ── */
			const menuList = document.getElementById('mainCategoriesList');
			const menuPrev = document.querySelector('.cat-prev');
			const menuNext = document.querySelector('.cat-next');

			function checkMenuArrows() {
				if (!menuList) return;
				const over = menuList.scrollWidth > menuList.clientWidth + 4;
				if (menuPrev) menuPrev.classList.toggle('visible', over && menuList.scrollLeft > 4);
				if (menuNext) menuNext.classList.toggle('visible', over && menuList.scrollLeft < menuList.scrollWidth - menuList.clientWidth - 4);
			}
			if (menuPrev) menuPrev.addEventListener('click', () => { menuList.scrollBy({left:-200,behavior:'smooth'}); setTimeout(checkMenuArrows,350); });
			if (menuNext) menuNext.addEventListener('click', () => { menuList.scrollBy({left:200,behavior:'smooth'}); setTimeout(checkMenuArrows,350); });
			if (menuList) menuList.addEventListener('scroll', checkMenuArrows);
			window.addEventListener('resize', checkMenuArrows);
			window.addEventListener('load', checkMenuArrows);
			checkMenuArrows();

			/* ── Hero slider ── */
			const slides  = document.querySelectorAll('.hero-slide');
			const dots    = document.querySelectorAll('.hero-dot');
			const hPrev   = document.querySelector('.hero-prev');
			const hNext   = document.querySelector('.hero-next');
			let current   = 0;
			let autoTimer = null;

			function goToSlide(n) {
				if (!slides.length) return;
				slides[current].classList.remove('active');
				if (dots[current]) dots[current].classList.remove('active');
				current = (n + slides.length) % slides.length;
				slides[current].classList.add('active');
				if (dots[current]) dots[current].classList.add('active');
			}

			function startAuto() { autoTimer = setInterval(() => goToSlide(current + 1), 10000); }
			function resetAuto()  { clearInterval(autoTimer); startAuto(); }

			if (hPrev) hPrev.addEventListener('click', () => { goToSlide(current - 1); resetAuto(); });
			if (hNext) hNext.addEventListener('click', () => { goToSlide(current + 1); resetAuto(); });
			dots.forEach((dot, i) => dot.addEventListener('click', () => { goToSlide(i); resetAuto(); }));
			if (slides.length > 1) startAuto();

			/* ── Panier AJAX ── */
			document.body.addEventListener('wc_fragments_refreshed', function() {
				const count = document.querySelector('#topCartCount');
				if (count) {
					const wcCount = document.querySelector('.cart-contents-count');
					if (wcCount) {
						count.textContent = wcCount.textContent;
						count.classList.toggle('has-items', parseInt(wcCount.textContent) > 0);
					}
				}
			});

			/* ── Mobile search ── */
			function closeMobileSearch() {
				if (!filterWrap) return;
				filterWrap.classList.remove('mobile-open');
				if (filterInput) {
					filterInput.style.display = 'none';
					filterInput.style.opacity = '0';
					filterInput.value = '';
				}
				if (filterClear) filterClear.classList.remove('visible');
			}

			if (filterWrap) {
				filterWrap.addEventListener('click', function(e) {
					if (!isMobile()) return;
					if (!filterWrap.classList.contains('mobile-open')) {
						filterWrap.classList.add('mobile-open');
						filterInput.style.display = 'block';
						filterInput.style.opacity = '1';
						filterInput.focus();
						e.stopPropagation();
					}
				});
			}

			document.addEventListener('click', function(e) {
				if (!isMobile()) return;
				if (filterWrap && !filterWrap.contains(e.target)) {
					closeMobileSearch();
				}
			});

		})();
	</script>

	<div id="content" class="site-content">