<?php
    $product_home_url = '';
    $ph_page = get_page_by_path('product-home');
    if (!$ph_page) $ph_page = get_page_by_path('product_home');
    $product_home_url = $ph_page ? get_permalink($ph_page->ID) : home_url('/product-home/');
    // $is_product_home = (is_page() && get_page_template_slug() === 'page-product_home.php');
    $is_product_home = is_page('product-home');
    $oceanly_site_header_class = ( Oceanly\Helpers::site_header_b_margin() ? 'site-header u-b-margin' : 'site-header' );

    $terms = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ));

    $terms_data = array();

    if (!empty($terms) && !is_wp_error($terms)) {
        $terms = array_values(array_filter($terms, function($t) {
            return $t->slug !== 'uncategorized' && $t->slug !== 'non-classe';
        }));
        foreach ($terms as $term) {
            $children = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => $term->term_id,
            ));
            $products = get_posts(array(
                'post_type'      => 'product',
                'posts_per_page' => 8,
                'post_status'    => 'publish',
                'tax_query'      => array(array(
                    'taxonomy'         => 'product_cat',
                    'field'            => 'term_id',
                    'terms'            => $term->term_id,
                    'include_children' => false,
                )),
            ));
            $terms_data[] = array(
                'term'         => $term,
                'has_children' => (!empty($children) && !is_wp_error($children)),
                'children'     => (!empty($children) && !is_wp_error($children)) ? $children : array(),
                 'products'     => $products,
            );
        }
    }
?>

<div class="sticky-header-wrap">

    <!-- HEADER ROW : LOGO + FILTRE + PANIER -->
    <div class="header-main-row">

        <div class="header-logo">
            <?php get_template_part( 'template-parts/header/branding' ); ?>
        </div>

        <div class="top-filter-search">
            <svg class="filter-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <input type="text" id="topFilterInput"
                placeholder="<?php echo $is_product_home ? 'Rechercher un produit...' : 'Rechercher une catégorie...'; ?>"
                aria-label="Rechercher"
                value="<?php echo $is_product_home ? esc_attr(isset($_GET['product_name']) ? $_GET['product_name'] : '') : ''; ?>"
            >
            <button class="filter-clear" id="filterClear" aria-label="Effacer">&#10005;</button>
        </div>

        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="top-cart-btn" aria-label="Panier">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            <?php $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
            <span class="cart-count<?php echo $cart_count > 0 ? ' has-items' : ''; ?>" id="topCartCount">
                <?php echo esc_html( $cart_count ); ?>
            </span>
        </a>

        <!-- Bouton compte utilisateur -->
        <div class="user-account-wrapper">
            <?php if ( is_user_logged_in() ) : 
                $current_user = wp_get_current_user();
            ?>
                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="user-account-btn logged-in">
                    <span class="user-icon">👤</span>
                    <span class="user-name"><?php echo esc_html( $current_user->display_name ); ?></span>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="user-account-btn logged-out">
                    <span class="user-icon">🔐</span>
                    <span class="user-name">Se connecter</span>
                </a>
            <?php endif; ?>
        </div>

    </div><!-- /.header-main-row -->

    <!-- MENU CATÉGORIES -->
    <nav class="custom-categories-menu" aria-label="Catégories produits">
        <div class="categories-scroll-wrapper">
            <button class="cat-arrow cat-prev" aria-label="Précédent">&#8249;</button>
            <ul class="main-categories" id="mainCategoriesList">
                <?php foreach ($terms_data as $data) :
                    $term = $data['term'];
                ?>
                    <li class="menu-item<?php echo $data['has_children'] ? ' has-children' : ''; ?>" 
                        data-name="<?php echo esc_attr(strtolower($term->name)); ?>">

                        <a href="<?php echo esc_url(get_term_link($term)); ?>">
                            <?php echo esc_html($term->name); ?>
                        </a>

                        <?php if (!empty($data['products'])) : ?>
                        <div class="cat-dropdown">
                            <ul class="cat-products-list">
                                <?php foreach ($data['products'] as $product) : ?>
                                <li>
                                    <a href="<?php echo get_permalink($product->ID); ?>">
                                        
                                        <span><?php echo esc_html($product->post_title); ?></span>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <?php if ($data['has_children']) : ?>
                        <ul class="sub-menu">
                            <?php foreach ($data['children'] as $child) : ?>
                            <li>
                                <a href="<?php echo esc_url(get_term_link($child)); ?>">
                                    <?php echo esc_html($child->name); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button class="cat-arrow cat-next" aria-label="Suivant">&#8250;</button>
        </div>
    </nav>

</div>

<!-- JS pour menu scroll + recherche + panier AJAX -->
<script>
(function() {
    document.querySelectorAll('.main-categories .menu-item').forEach(function (item) {
        const dropdown = item.querySelector('.cat-dropdown');
        if (!dropdown) return;

        item.addEventListener('mouseenter', function () {
            const rect = item.getBoundingClientRect();
            dropdown.style.top  = rect.bottom + 'px';
            dropdown.style.left = rect.left + 'px';
        });
    });  /* ← fin du forEach — supprimé le }); en trop qui était ici */

    const filterInput     = document.getElementById('topFilterInput');
    const filterClear     = document.getElementById('filterClear');
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

    if (filterInput) {
        filterInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
            if (e.key === 'Escape' && isMobile()) closeMobileSearch();
        });
    }

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
})();
</script>