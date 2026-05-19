<?php error_log('cart.php surcharge chargée'); ?>
<?php
/**
 * Cart Page — Surcharge PavelyS / Oceanly Child
 * Emplacement : wp-content/themes/VOTRE-THEME-ENFANT/woocommerce/cart/cart.php
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<style>
/* ============================================================
   PAVELYS CART — Styles intégrés (à déplacer dans style.css)
   ============================================================ */

.pavelys-cart-wrapper {
  max-width: 960px;
  margin: 0 auto;
  padding: 2rem 1rem;
  font-family: 'Georgia', serif;
  color: #1a1a1a;
}

/* ---- En-tête ---- */
.pavelys-cart-header {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  margin-bottom: 2rem;
  border-bottom: 2px solid #1a1a1a;
  padding-bottom: .75rem;
}
.pavelys-cart-header h1 {
  font-size: 1.6rem;
  font-weight: 700;
  letter-spacing: -.5px;
  margin: 0;
}
.pavelys-cart-header .cart-item-count {
  font-size: .85rem;
  color: #666;
  font-family: 'Courier New', monospace;
}

/* ---- Panier vide ---- */
.pavelys-cart-empty {
  text-align: center;
  padding: 5rem 2rem;
}
.pavelys-cart-empty .cart-empty-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}
.pavelys-cart-empty p {
  font-size: 1.1rem;
  color: #555;
  margin-bottom: 1.5rem;
}
.pavelys-btn-primary {
  display: inline-block;
  background: #1a1a1a;
  color: #fff !important;
  padding: 12px 28px;
  font-size: .9rem;
  font-family: 'Courier New', monospace;
  letter-spacing: .5px;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: opacity .2s;
}
.pavelys-btn-primary:hover { opacity: .8; }

/* ---- Disposition deux colonnes ---- */
.pavelys-cart-layout {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 2rem;
  align-items: start;
}
@media (max-width: 780px) {
  .pavelys-cart-layout { grid-template-columns: 1fr; }
}

/* ---- Tableau produits ---- */
.pavelys-cart-table {
  width: 100%;
  border-collapse: collapse;
}
.pavelys-cart-table thead th {
  font-family: 'Courier New', monospace;
  font-size: .7rem;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  color: #888;
  padding: 0 0 .75rem;
  border-bottom: 1px solid #e0e0e0;
  text-align: left;
  font-weight: 400;
}
.pavelys-cart-table thead th.product-remove,
.pavelys-cart-table thead th.product-thumbnail { width: 40px; }
.pavelys-cart-table thead th.product-subtotal,
.pavelys-cart-table thead th.product-price { text-align: right; }

/* ---- Ligne produit ---- */
.pavelys-cart-table tbody tr.cart_item td {
  padding: 1.25rem 0;
  border-bottom: 1px solid #f0f0f0;
  vertical-align: middle;
}
.pavelys-cart-table .product-remove {
  width: 32px;
  padding-right: 8px !important;
}
a.pavelys-remove {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  font-size: 12px;
  color: #aaa;
  border: 1px solid #ddd;
  text-decoration: none;
  border-radius: 50%;
  transition: all .2s;
}
a.pavelys-remove:hover {
  color: #c0392b;
  border-color: #c0392b;
}

/* ---- Miniature ---- */
.pavelys-cart-table .product-thumbnail {
  width: 72px;
  padding-right: 16px !important;
}
.pavelys-cart-table .product-thumbnail img {
  width: 64px;
  height: 64px;
  object-fit: cover;
  display: block;
  border: 1px solid #eee;
}
a.pavelys-thumb-link { display: block; line-height: 0; }

/* ---- Nom produit ---- */
.pavelys-cart-table .product-name {
  padding-right: 16px !important;
}
.pavelys-cart-table .product-name a.pavelys-product-link {
  font-size: .95rem;
  font-weight: 700;
  color: #1a1a1a;
  text-decoration: none;
}
.pavelys-cart-table .product-name a.pavelys-product-link:hover {
  text-decoration: underline;
}

.pavelys-cart-table .product-name .variation {
  font-size: .8rem;
  color: #888;
  margin-top: 4px;
}

.pavelys-cart-table .product-name .backorder_notification {
  font-size: .75rem;
  font-family: 'Courier New', monospace;
  color: #e67e22;
  margin-top: 4px;
}

/* ---- Prix & Sous-total ---- */
.pavelys-cart-table .product-price,
.pavelys-cart-table .product-subtotal {
  text-align: right;
  font-family: 'Courier New', monospace;
  font-size: .9rem;
}
.pavelys-cart-table .product-subtotal {
  font-weight: 700;
}

/* ---- Quantité ---- */
.pavelys-cart-table .product-quantity {
  text-align: center;
}
.pavelys-cart-table .product-quantity .quantity {
  display: inline-flex;
  align-items: center;
  border: 1px solid #ddd;
  overflow: hidden;
}
.pavelys-cart-table .product-quantity .qty {
  width: 44px;
  border: none;
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
  text-align: center;
  font-family: 'Courier New', monospace;
  font-size: .85rem;
  padding: 6px 4px;
  background: #fff;
  -moz-appearance: textfield;
}
.pavelys-cart-table .product-quantity .qty::-webkit-inner-spin-button,
.pavelys-cart-table .product-quantity .qty::-webkit-outer-spin-button { -webkit-appearance: none; }

/* ---- Actions : coupon + mise à jour ---- */
.pavelys-cart-actions-row td {
  padding-top: 1.5rem !important;
  border-bottom: none !important;
}
.pavelys-cart-actions-inner {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}
.pavelys-coupon {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
  min-width: 240px;
}
.pavelys-coupon label {
  font-family: 'Courier New', monospace;
  font-size: .75rem;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: #888;
  white-space: nowrap;
}
.pavelys-coupon input.input-text {
  flex: 1;
  border: 1px solid #ddd;
  padding: 9px 12px;
  font-size: .85rem;
  font-family: 'Courier New', monospace;
  outline: none;
  transition: border-color .2s;
}
.pavelys-coupon input.input-text:focus { border-color: #1a1a1a; }
.pavelys-btn-secondary {
  background: #1a1a1a !important;
  color: #fff !important;
  border: none !important;
  padding: 9px 18px !important;
  font-family: 'Courier New', monospace !important;
  font-size: .8rem !important;
  letter-spacing: .5px;
  cursor: pointer;
  white-space: nowrap;
  transition: opacity .2s;
}
.pavelys-btn-secondary:hover { opacity: .8 !important; }
.pavelys-btn-outline {
  background: transparent !important;
  color: #1a1a1a !important;
  border: 1px solid #1a1a1a !important;
  padding: 9px 18px !important;
  font-family: 'Courier New', monospace !important;
  font-size: .8rem !important;
  letter-spacing: .5px;
  cursor: pointer;
  white-space: nowrap;
  transition: all .2s;
}
.pavelys-btn-outline:hover {
  background: #1a1a1a !important;
  color: #fff !important;
}

/* ---- Colonne récap (droite) ---- */
.pavelys-cart-aside {
  position: sticky;
  top: 100px;
}
.pavelys-cart-aside .cart-collaterals {
  background: #f8f7f4;
  border: 1px solid #e8e8e8;
  padding: 1.5rem;
}

/* Totaux WooCommerce dans la colonne de droite */
.pavelys-cart-aside .cart_totals h2 {
  font-size: .75rem;
  font-family: 'Courier New', monospace;
  letter-spacing: 2px;
  text-transform: uppercase;
  color: #888;
  font-weight: 400;
  margin: 0 0 1.2rem;
  border-bottom: 1px solid #e0e0e0;
  padding-bottom: .75rem;
}
.pavelys-cart-aside .cart_totals table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.5rem;
}
.pavelys-cart-aside .cart_totals table th,
.pavelys-cart-aside .cart_totals table td {
  padding: 8px 0;
  font-size: .85rem;
  border: none;
  vertical-align: middle;
}
.pavelys-cart-aside .cart_totals table th {
  color: #888;
  font-weight: 400;
  font-family: 'Courier New', monospace;
  font-size: .75rem;
  text-align: left;
}
.pavelys-cart-aside .cart_totals table td {
  text-align: right;
  font-family: 'Courier New', monospace;
}
.pavelys-cart-aside .cart_totals table .order-total th,
.pavelys-cart-aside .cart_totals table .order-total td {
  font-weight: 700;
  font-size: 1rem;
  border-top: 1px solid #e0e0e0;
  padding-top: 12px;
}
.pavelys-cart-aside .wc-proceed-to-checkout .checkout-button {
  display: block;
  width: 100%;
  background: #1a1a1a;
  color: #fff;
  text-align: center;
  padding: 14px;
  font-family: 'Courier New', monospace;
  font-size: .85rem;
  letter-spacing: 1px;
  text-transform: uppercase;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: opacity .2s;
}
.pavelys-cart-aside .wc-proceed-to-checkout .checkout-button:hover { opacity: .8; }

/* Shipping calculator */
.pavelys-cart-aside .shipping-calculator-button {
  font-size: .8rem;
  color: #888;
  font-family: 'Courier New', monospace;
  text-decoration: underline;
  cursor: pointer;
}

/* ============================================================ */
</style>

<div class="pavelys-cart-wrapper">

  <?php if ( WC()->cart->is_empty() ) : ?>

    <div class="pavelys-cart-empty">
      <div class="cart-empty-icon">🛒</div>
      <p><?php esc_html_e( 'Votre panier est vide.', 'oceanly' ); ?></p>
      <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"
         class="pavelys-btn-primary">
        <?php esc_html_e( 'Continuer mes achats', 'oceanly' ); ?>
      </a>
    </div>

  <?php else : ?>

    <!-- En-tête -->
    <div class="pavelys-cart-header">
      <h1><?php esc_html_e( 'Panier', 'oceanly' ); ?></h1>
      <span class="cart-item-count">
        <?php
          $count = WC()->cart->get_cart_contents_count();
          /* translators: %d = nombre d'articles */
          printf( _n( '%d article', '%d articles', $count, 'oceanly' ), $count );
        ?>
      </span>
    </div>

    <!-- Disposition 2 colonnes -->
    <div class="pavelys-cart-layout">

      <!-- Colonne gauche : liste des produits -->
      <div class="pavelys-cart-main">
        <form class="woocommerce-cart-form"
              action="<?php echo esc_url( wc_get_cart_url() ); ?>"
              method="post">

          <?php do_action( 'woocommerce_before_cart_table' ); ?>

          <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents pavelys-cart-table"
                 cellspacing="0">
            <thead>
              <tr>
                <th class="product-remove"></th>
                <th class="product-thumbnail"></th>
                <th class="product-name"><?php esc_html_e( 'Produit', 'oceanly' ); ?></th>
                <th class="product-price"><?php esc_html_e( 'Prix', 'oceanly' ); ?></th>
                <th class="product-quantity"><?php esc_html_e( 'Qté', 'oceanly' ); ?></th>
                <th class="product-subtotal"><?php esc_html_e( 'Sous-total', 'oceanly' ); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php do_action( 'woocommerce_before_cart_contents' ); ?>

              <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0
                  && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :

                  $product_permalink = apply_filters( 'woocommerce_cart_item_permalink',
                    $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '',
                    $cart_item, $cart_item_key );
              ?>

              <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                <!-- Supprimer -->
                <td class="product-remove">
                  <?php echo apply_filters(
                    'woocommerce_cart_item_remove_link',
                    sprintf(
                      '<a role="button" href="%s" class="remove pavelys-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&#x2715;</a>',
                      esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                      esc_attr( sprintf( __( 'Supprimer %s', 'oceanly' ), wp_strip_all_tags( $product_name ) ) ),
                      esc_attr( $product_id ),
                      esc_attr( $_product->get_sku() )
                    ),
                    $cart_item_key
                  ); ?>
                </td>

                <!-- Miniature -->
                <td class="product-thumbnail">
                  <?php $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
                  echo $product_permalink
                    ? sprintf( '<a href="%s" class="pavelys-thumb-link">%s</a>', esc_url( $product_permalink ), $thumbnail )
                    : $thumbnail; ?>
                </td>

                <!-- Nom -->
                <td class="product-name" data-title="<?php esc_attr_e( 'Produit', 'oceanly' ); ?>">
                  <?php if ( ! $product_permalink ) {
                    echo wp_kses_post( $product_name );
                  } else {
                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name',
                      sprintf( '<a href="%s" class="pavelys-product-link">%s</a>', esc_url( $product_permalink ), $_product->get_name() ),
                      $cart_item, $cart_item_key ) );
                  }
                  do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
                  echo wc_get_formatted_cart_item_data( $cart_item );
                  if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                    echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification',
                      '<p class="backorder_notification">' . esc_html__( 'Disponible en précommande', 'oceanly' ) . '</p>',
                      $product_id ) );
                  } ?>
                </td>

                <!-- Prix unitaire -->
                <td class="product-price" data-title="<?php esc_attr_e( 'Prix', 'oceanly' ); ?>">
                  <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                </td>

                <!-- Quantité -->
                <td class="product-quantity" data-title="<?php esc_attr_e( 'Qté', 'oceanly' ); ?>">
                  <?php
                  $min_qty = $_product->is_sold_individually() ? 1 : 0;
                  $max_qty = $_product->is_sold_individually() ? 1 : $_product->get_max_purchase_quantity();
                  echo apply_filters(
                    'woocommerce_cart_item_quantity',
                    woocommerce_quantity_input( array(
                      'input_name'   => "cart[{$cart_item_key}][qty]",
                      'input_value'  => $cart_item['quantity'],
                      'max_value'    => $max_qty,
                      'min_value'    => $min_qty,
                      'product_name' => $product_name,
                    ), $_product, false ),
                    $cart_item_key,
                    $cart_item
                  ); ?>
                </td>

                <!-- Sous-total -->
                <td class="product-subtotal" data-title="<?php esc_attr_e( 'Sous-total', 'oceanly' ); ?>">
                  <?php echo apply_filters( 'woocommerce_cart_item_subtotal',
                    WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
                    $cart_item, $cart_item_key ); ?>
                </td>

              </tr>

              <?php endif; endforeach; ?>

              <?php do_action( 'woocommerce_cart_contents' ); ?>

              <!-- Actions : coupon + mise à jour -->
              <tr class="pavelys-cart-actions-row">
                <td colspan="6" class="actions">
                  <div class="pavelys-cart-actions-inner">

                    <?php if ( wc_coupons_enabled() ) : ?>
                    <div class="pavelys-coupon">
                      <label for="coupon_code"><?php esc_html_e( 'Code promo', 'oceanly' ); ?></label>
                      <input type="text"
                             name="coupon_code"
                             class="input-text"
                             id="coupon_code"
                             value=""
                             placeholder="<?php esc_attr_e( 'Entrez votre code…', 'oceanly' ); ?>" />
                      <button type="submit"
                              class="button pavelys-btn-secondary"
                              name="apply_coupon"
                              value="Apply coupon">
                        <?php esc_html_e( 'Appliquer', 'oceanly' ); ?>
                      </button>
                      <?php do_action( 'woocommerce_cart_coupon' ); ?>
                    </div>
                    <?php endif; ?>

                    <button type="submit"
                            class="button pavelys-btn-outline"
                            name="update_cart"
                            value="Update cart">
                      ↻ <?php esc_html_e( 'Mettre à jour', 'oceanly' ); ?>
                    </button>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                  </div>
                </td>
              </tr>

              <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </tbody>
          </table>

          <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
      </div><!-- /.pavelys-cart-main -->

      <!-- Colonne droite : récapitulatif & totaux -->
      <aside class="pavelys-cart-aside">
        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
        <div class="cart-collaterals">
          <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        </div>
      </aside>

    </div><!-- /.pavelys-cart-layout -->

  <?php endif; ?>

</div><!-- /.pavelys-cart-wrapper -->

<?php do_action( 'woocommerce_after_cart' ); ?>