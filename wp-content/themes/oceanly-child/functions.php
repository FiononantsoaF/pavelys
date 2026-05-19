<?php
function oceanly_child_enqueue_styles() {
    wp_enqueue_style(
        'oceanly-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'oceanly-child-style',
        get_stylesheet_uri(),
        array('oceanly-parent-style'),
        wp_get_theme()->get('Version') 
    );
} 
add_action('wp_enqueue_scripts', 'oceanly_child_enqueue_styles');
add_action('init', function() {
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/product_home') !== false) {
        $uri = str_replace('/product_home', '/product_home', $_SERVER['REQUEST_URI']);
        wp_redirect(home_url($uri), 301);
        exit;
    }
});

add_filter('elementor/frontend/the_content', function($content) {
    if (is_page() && get_page_template_slug() === 'page-product_home.php') {
        return ''; 
    }
    return $content;
});


add_filter('elementor/load_scripts_on_demand', function($load) {
    if (is_page() && get_page_template_slug() === 'page-product_home.php') {
        return false;
    }
    return $load;
});

function my_product_home_styles() {
    if ( ! is_page(53) ) return;

    wp_enqueue_style(
        'product-home-style',
        get_stylesheet_directory_uri() . '/assets/css/product-home.css',
        array(),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'my_product_home_styles');

function my_frontpage_styles() {
    if ( ! is_front_page() ) return;

    wp_enqueue_style(
        'frontpage-style',
        get_stylesheet_directory_uri() . '/assets/css/front-page.css',
        array(),
        '1.0'
    );
}
add_action( 'wp_enqueue_scripts', 'my_frontpage_styles' );

add_action( 'wp_head', function() {
    if ( is_front_page() ) {
        echo '<style>
            .oceanly-hero,
            .hero-section,
            .site-hero,
            .hentry .entry-header,
            .hentry .entry-content,
            .hentry .entry-footer,
            .recent-posts,
            .post-loop { display: none !important; }
        </style>';
    }
}, 1 );

add_action( 'wp_head', function() {
    echo '<style>
        /* Retire overflow du header Oceanly qui bloquait le sticky */
        #masthead.site-header {
            overflow: visible;
            position: relative;
        }
        /* Sticky positionné par rapport au viewport */
        .sticky-header-outer {
            position: sticky;
            top: 0;
            z-index: 9999;
            width: 100%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,.12);
        }
        /* Empêche html/body d\'avoir overflow:hidden qui casse sticky */
        html, body, #page.site {
            overflow-x: clip;
        }
    </style>';
});


add_action( 'wp_enqueue_scripts', 'pavelys_child_enqueue_styles' );
function pavelys_child_enqueue_styles() {
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'child-style',
        get_stylesheet_uri(),
        array( 'parent-style' )
    );
}

add_action( 'after_setup_theme', 'pavelys_register_menus' );
function pavelys_register_menus() {
    register_nav_menus( array(
        'footer-menu'  => __( 'Menu footer principal', 'pavelys' ),
        'footer-legal' => __( 'Menu liens légaux', 'pavelys' ),
    ) );
}

wp_enqueue_style(
    'pavelys-footer',
    get_stylesheet_directory_uri() . '/assets/css/footer-pavelys.css',
    array(),
    '1.0.0'
);

add_action( 'wp_enqueue_scripts', 'oceanly_child_enqueue_styles' );

wp_enqueue_style(
    'oceanly-parent-style',
    get_template_directory_uri() . '/style.css'
);
wp_enqueue_style(
    'oceanly-child-style',
    get_stylesheet_uri(),
    array( 'oceanly-parent-style' )
);
// add_action( 'wp_enqueue_scripts', function() {
//     if ( is_cart() ) {
//         wp_enqueue_style(
//             'pavelys-woo-cart',
//             get_stylesheet_directory_uri() . '/assets/css/woocommerce-cart.css',
//             array( 'woocommerce-general' ),
//             '1.0.0'
//         );
//     }
// });
// add_action( 'wp_enqueue_scripts', function() {
//     if ( is_cart() ) {
//         wp_dequeue_script( 'wc-cart-fragments' );
//     }
// }, 99 );
add_filter( 'woocommerce_locate_template', function( $template, $template_name, $template_path ) {
    $child_template = get_stylesheet_directory() . '/woocommerce/' . $template_name;
    if ( file_exists( $child_template ) ) {
        return $child_template;
    }
    return $template;
}, 10, 3 );

add_action( 'wp_enqueue_scripts', function () {
    if ( is_cart() ) {
        wp_dequeue_script( 'wc-cart-fragments' );
        wp_deregister_script( 'wc-cart-fragments' );
    }
}, 99 );
 
/* ------------------------------------------------------------------
   3. Bloquer aussi le remplacement par fragments AJAX
------------------------------------------------------------------ */
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    if ( is_cart() ) {
        return array();
    }
    return $fragments;
}, 99 );
 
/* ------------------------------------------------------------------
   4. Forcer WooCommerce à utiliser notre template cart.php enfant
------------------------------------------------------------------ */
add_filter( 'woocommerce_locate_template', function ( $template, $template_name, $template_path ) {
    if ( $template_name === 'cart/cart.php' ) {
        $child_template = get_stylesheet_directory() . '/woocommerce/cart/cart.php';
        if ( file_exists( $child_template ) ) {
            return $child_template;
        }
    }
    return $template;
}, 99, 3 );


add_filter('woocommerce_cart_fragments_params', function($params) {
    $params['ajax_url'] = admin_url('admin-ajax.php');
    return $params;
});

add_filter('woocommerce_cart_fragments_params', function($params) {
    $params['ajax_url'] = admin_url('admin-ajax.php');
    return $params;
});

// Prevent cart caching issues
add_filter('woocommerce_cart_hash_key', function($key) {
    return $key . '_' . get_current_user_id();
});

