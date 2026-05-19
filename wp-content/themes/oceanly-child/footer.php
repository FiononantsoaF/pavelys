<?php
/**
 * Footer personnalisé PavelyS
 * Surcharge du footer du thème parent
 */
?>

<footer id="pavelys-footer">

  <div class="footer-inner">

    <!-- Colonne 1 : Logo + Slogan + Contact -->
    <div class="footer-col footer-brand">

      <?php if ( has_custom_logo() ) : ?>
        <div class="footer-logo"><?php the_custom_logo(); ?></div>
      <?php else : ?>
        <h2 class="footer-site-name">
          <?php bloginfo( 'name' ); ?>
        </h2>
      <?php endif; ?>

      <p class="footer-slogan">
        <?php bloginfo( 'description' ); ?>
      </p>

      <address class="footer-contact">
        <span> <?php echo get_option( 'pavelys_adresse' ); ?></span>
        <span> <a href="tel:<?php echo get_option( 'pavelys_telephone' ); ?>">
          <?php echo get_option( 'pavelys_telephone' ); ?>
        </a></span>
        <span>✉ <a href="mailto:<?php echo antispambot( get_option( 'admin_email' ) ); ?>">
          <?php echo antispambot( get_option( 'admin_email' ) ); ?>
        </a></span>
      </address>

    </div>

    <!-- Colonne 2 : Catégories produits -->
    <div class="footer-col footer-categories">
      <h3><?php _e( 'Nos produits', 'pavelys' ); ?></h3>
      <?php
      wp_list_categories( array(
        'taxonomy'   => 'product_cat', // ou 'category' si blog
        'title_li'   => '',
        'orderby'    => 'name',
        'show_count' => false,
      ) );
      ?>
    </div>

    <!-- Colonne 3 : Liens de navigation -->
    <div class="footer-col footer-nav">
      <h3><?php _e( 'Navigation', 'pavelys' ); ?></h3>
      <?php
      wp_nav_menu( array(
        'theme_location' => 'footer-menu', // à déclarer dans functions.php
        'container'      => false,
        'menu_class'     => 'footer-links',
        'depth'          => 1,
      ) );
      ?>
    </div>

    <!-- Colonne 4 : Réseaux sociaux -->
    <div class="footer-col footer-social">
      <h3><?php _e( 'Suivez-nous', 'pavelys' ); ?></h3>
      <ul class="social-links">
        <?php if ( $fb = get_option('pavelys_facebook') ) : ?>
          <li><a href="<?php echo esc_url($fb); ?>" target="_blank" rel="noopener">Facebook</a></li>
        <?php endif; ?>
        <?php if ( $ig = get_option('pavelys_instagram') ) : ?>
          <li><a href="<?php echo esc_url($ig); ?>" target="_blank" rel="noopener">Instagram</a></li>
        <?php endif; ?>
        <?php if ( $li = get_option('pavelys_linkedin') ) : ?>
          <li><a href="<?php echo esc_url($li); ?>" target="_blank" rel="noopener">LinkedIn</a></li>
        <?php endif; ?>
      </ul>
    </div>

  </div><!-- /.footer-inner -->

  <!-- Barre copyright -->
  <div class="footer-bottom">
    <p class="footer-copyright">
      &copy; <?php echo date('Y'); ?>
      <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
      &mdash; <?php _e( 'Tous droits réservés', 'pavelys' ); ?>
    </p>
    <?php
    // Liens légaux via un menu WordPress
    // wp_nav_menu( array(
    //   'theme_location' => 'footer-legal',
    //   'container'      => false,
    //   'menu_class'     => 'legal-links',
    //   'depth'          => 1,
    // ) );
    ?>
  </div>

</footer><!-- /#pavelys-footer -->

<?php wp_footer(); // ← OBLIGATOIRE, ne jamais supprimer ?>
</body>
</html>