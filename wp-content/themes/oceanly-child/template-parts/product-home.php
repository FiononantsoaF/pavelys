<?php
/**
 * Template Name: Product Home
 * Description: Page de recherche et d'affichage de tous les produits
 * @package Oceanly Child
 */


// ── Paramètres de recherche ──────────────────────────────────────────────────
$search_name    = isset($_GET['product_name']) ? sanitize_text_field($_GET['product_name']) : '';
$search_cat     = isset($_GET['product_cat'])  ? sanitize_text_field($_GET['product_cat'])  : '';
$paged          = isset($_GET['paged']) ? max(1, (int)$_GET['paged']) : 1;
$posts_per_page = 24;

// Mapping orderby GET → WP_Query args
$orderby_map = array(
    'price'       => array('orderby' => 'meta_value_num', 'meta_key' => '_price', 'order' => 'ASC'),
    'price-desc'  => array('orderby' => 'meta_value_num', 'meta_key' => '_price', 'order' => 'DESC'),
    'title'       => array('orderby' => 'title', 'order' => 'ASC'),
    'popularity'  => array('orderby' => 'meta_value_num', 'meta_key' => 'total_sales', 'order' => 'DESC'),
    ''            => array('orderby' => 'date', 'order' => 'DESC'),
);
$current_order = isset($_GET['orderby']) ? sanitize_key($_GET['orderby']) : '';
$order_args    = isset($orderby_map[$current_order]) ? $orderby_map[$current_order] : $orderby_map[''];

// ── Query WooCommerce ─────────────────────────────────────────────────────────
$args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
    'orderby'        => $order_args['orderby'],
    'order'          => $order_args['order'],
);
if (!empty($order_args['meta_key'])) {
    $args['meta_key'] = $order_args['meta_key'];
}
if (!empty($search_name)) {
    $args['s'] = $search_name;
}
if (!empty($search_cat)) {
    $args['tax_query'] = array(array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => $search_cat,
    ));
}

$products_query = new WP_Query($args);
$total_products = $products_query->found_posts;
$total_pages    = $products_query->max_num_pages;

// ── Catégories pour le select ─────────────────────────────────────────────────
$all_cats = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
));
$all_cats = array_filter((array)$all_cats, function($t) {
    return $t->slug !== 'uncategorized' && $t->slug !== 'non-classe';
});

// URL de base — on force l'URL absolue de la page courante sans aucun paramètre
global $post;
$base_url = trailingslashit( get_permalink( $post->ID ) );
?>
<?php get_header(); ?>
<div class="ph-page">

    <!-- BARRE DE RECHERCHE -->
    <div class="ph-search-bar">
        <form class="ph-search-inner" method="GET" action="<?php echo esc_url( $base_url ); ?>" id="ph_search_form">
            <span class="ph-search-title">Produits</span>

            <!-- Nom -->
            <div class="ph-field">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input
                    type="text"
                    name="product_name"
                    id="ph_product_name"
                    placeholder="Nom du produit..."
                    value="<?php echo esc_attr($search_name); ?>"
                    autocomplete="off"
                >
            </div>

            <!-- Catégorie -->
            <div class="ph-field ph-field-select">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h7"/>
                </svg>
                <select name="product_cat" id="ph_product_cat">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($all_cats as $cat) : ?>
                        <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($search_cat, $cat->slug); ?>>
                            <?php echo esc_html($cat->name); ?> (<?php echo (int)$cat->count; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Bouton recherche -->
            <button type="submit" class="ph-btn-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Rechercher
            </button>

            <!-- Reset uniquement si filtre actif -->
            <?php if (!empty($search_name) || !empty($search_cat)) : ?>
            <a href="<?php echo esc_url($base_url); ?>" class="ph-btn-reset">✕ Effacer</a>
            <?php endif; ?>

        </form>
    </div>

    <!-- Tags filtres actifs -->
    <?php if (!empty($search_name) || !empty($search_cat)) : ?>
    <div class="ph-active-filters">
        <?php if (!empty($search_name)) : ?>
        <span class="ph-tag">
            🔍 "<?php echo esc_html($search_name); ?>"
            <a href="<?php echo esc_url(remove_query_arg('product_name')); ?>">✕</a>
        </span>
        <?php endif; ?>
        <?php if (!empty($search_cat)) :
            $cat_obj = get_term_by('slug', $search_cat, 'product_cat');
        ?>
        <span class="ph-tag">
            📁 <?php echo esc_html($cat_obj ? $cat_obj->name : $search_cat); ?>
            <a href="<?php echo esc_url(remove_query_arg('product_cat')); ?>">✕</a>
        </span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- RÉSULTATS + TRI -->
    <div class="ph-results-bar">
        <div class="ph-results-count">
            <?php if ($total_products > 0) : ?>
                <strong><?php echo number_format($total_products); ?></strong>
                produit<?php echo $total_products > 1 ? 's' : ''; ?> trouvé<?php echo $total_products > 1 ? 's' : ''; ?>
                <?php if ($paged > 1) : ?> — page <?php echo $paged; ?>/<?php echo $total_pages; ?><?php endif; ?>
            <?php else : ?>
                Aucun résultat
            <?php endif; ?>
        </div>

        <div class="ph-sort-select">
            <span>Trier par</span>
            <select id="ph_sort">
                <?php
                $sort_options = array(
                    ''           => 'Plus récents',
                    'price'      => 'Prix croissant',
                    'price-desc' => 'Prix décroissant',
                    'title'      => 'Nom A→Z',
                    'popularity' => 'Popularité',
                );
                foreach ($sort_options as $val => $label) :
                    $url = add_query_arg(array_merge(
                        array_filter(array('product_name' => $search_name, 'product_cat' => $search_cat)),
                        array('orderby' => $val)
                    ), $base_url);
                    echo '<option value="' . esc_url($url) . '"' . selected($current_order, $val, false) . '>'
                        . esc_html($label) . '</option>';
                endforeach;
                ?>
            </select>
        </div>
    </div>

    <!-- GRILLE PRODUITS -->
    <div class="ph-grid-wrap">
        <?php if ($products_query->have_posts()) : ?>
        <div class="ph-grid">
            <?php while ($products_query->have_posts()) : $products_query->the_post();
                global $product;
                $product = wc_get_product(get_the_ID());
                if (!$product) continue;

                $img_url    = get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_thumbnail') ?: wc_placeholder_img_src('woocommerce_thumbnail');
                $gallery_ids = $product->get_gallery_image_ids();
                $img_hover   = !empty($gallery_ids)
                    ? wp_get_attachment_image_url($gallery_ids[0], 'woocommerce_thumbnail')
                    : null;
                $price_html = $product->get_price_html();
                $cats       = wp_get_post_terms(get_the_ID(), 'product_cat', array('fields' => 'names'));
                $cat_name   = !empty($cats) ? $cats[0] : '';
                $is_sale    = $product->is_on_sale();
                $is_new     = (time() - strtotime(get_the_date('Y-m-d')) < 60 * 86400);
                $add_url    = $product->is_type('simple')
                    ? add_query_arg('add-to-cart', get_the_ID(), $base_url)
                    : get_permalink();
            ?>
            <div class="ph-card">
                <a href="<?php the_permalink(); ?>" class="ph-card-img-wrap">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="ph-img-main">
                    <?php if ($img_hover) : ?>
                        <img src="<?php echo esc_url($img_hover); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="ph-img-hover">
                    <?php endif; ?>
                    <?php if ($is_sale) : ?>
                        <span class="ph-card-badge sale">Promo</span>
                    <?php elseif ($is_new) : ?>
                        <span class="ph-card-badge new">Nouveau</span>
                    <?php endif; ?>
                </a>

                <div class="ph-card-actions">
                    <a href="<?php the_permalink(); ?>" class="ph-card-action-btn" title="Voir">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                    </a>
                </div>

                <div class="ph-card-body">
                    <?php if ($cat_name) : ?>
                        <span class="ph-card-cat"><?php echo esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <a href="<?php the_permalink(); ?>" class="ph-card-name"><?php the_title(); ?></a>
                </div>

                <div class="ph-card-footer">
                    <div class="ph-card-price"><?php echo $price_html; ?></div>
                    <a href="<?php echo esc_url($add_url); ?>"
                       class="ph-add-cart <?php echo $product->is_type('simple') ? 'add_to_cart_button ajax_add_to_cart' : ''; ?>"
                       <?php if ($product->is_type('simple')) : ?>
                           data-product_id="<?php echo get_the_ID(); ?>" data-quantity="1"
                       <?php endif; ?>>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <?php echo $product->is_type('simple') ? 'Ajouter' : 'Voir'; ?>
                    </a>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- PAGINATION -->
        <?php if ($total_pages > 1) : ?>
        <div class="ph-pagination">
            <?php
            $q = array_filter(array(
                'product_name' => $search_name,
                'product_cat'  => $search_cat,
                'orderby'      => $current_order ?: '',
            ));
            if ($paged > 1) : ?>
                <a href="<?php echo esc_url(add_query_arg(array_merge($q, array('paged' => $paged-1)), $base_url)); ?>" class="ph-page-btn">&#8249;</a>
            <?php endif;
            $range = 2;
            for ($i = 1; $i <= $total_pages; $i++) :
                if ($i === 1 || $i === $total_pages || abs($i - $paged) <= $range) : ?>
                    <a href="<?php echo esc_url(add_query_arg(array_merge($q, array('paged' => $i)), $base_url)); ?>"
                       class="ph-page-btn <?php echo $i === $paged ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php elseif (abs($i - $paged) === $range + 1) :
                    echo '<span class="ph-page-btn disabled">…</span>';
                endif;
            endfor;
            if ($paged < $total_pages) : ?>
                <a href="<?php echo esc_url(add_query_arg(array_merge($q, array('paged' => $paged+1)), $base_url)); ?>" class="ph-page-btn">&#8250;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <div class="ph-no-results">
            <div class="ph-no-results-icon">🔍</div>
            <h2>Aucun produit trouvé</h2>
            <p>
                <?php if (!empty($search_name) || !empty($search_cat)) : ?>
                    Essayez avec d'autres termes ou
                    <a href="<?php echo esc_url($base_url); ?>" style="color:var(--accent)">voir tous les produits</a>.
                <?php else : ?>
                    Aucun produit disponible pour le moment.
                <?php endif; ?>
            </p>
        </div>
        <?php endif; ?>
    </div>

</div>

<script>
    (function() {
        // Tri → navigation avec paramètres conservés
        const sortSel = document.getElementById('ph_sort');
        if (sortSel) {
            sortSel.addEventListener('change', function() {
                if (this.value) window.location.href = this.value;
            });
        }

        // ✅ CORRECTION : synchronisation header ↔ page sans auto-recherche
        const headerInput = document.getElementById('topFilterInput');
        const pageInput   = document.getElementById('ph_product_name');

        if (headerInput && pageInput) {
            headerInput.value = pageInput.value;
            headerInput.addEventListener('input', function() {
                pageInput.value = this.value;
            });
        }
    })();

    document.addEventListener('DOMContentLoaded', function() {

        const items = document.querySelectorAll('.reveal');

        function checkReveal() {
            items.forEach(el => {
                const rect = el.getBoundingClientRect();
                const inView = rect.top < window.innerHeight * 0.88 && rect.bottom > 0;
                if (inView) {
                    el.classList.add('visible');
                    el.classList.remove('exit');
                } else if (rect.top > window.innerHeight) {
                    el.classList.remove('visible', 'exit');
                } else if (rect.bottom < 0) {
                    el.classList.add('exit');
                    el.classList.remove('visible');
                }
            });
        }

        window.addEventListener('scroll', checkReveal);
        checkReveal(); // vérification au chargement

    });
</script>

<?php get_footer(); ?>