<?php
$terms = get_terms(array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'parent'     => 0,
    'orderby'    => 'name',
    'order'      => 'ASC',
));

if (!empty($terms) && !is_wp_error($terms)) {

    $terms = array_values(array_filter($terms, function($t) {
        return $t->slug !== 'uncategorized' && $t->slug !== 'non-classe';
    }));

    $terms_data = array();

    foreach ($terms as $term) {

        $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
        $image_url = $thumbnail_id
            ? wp_get_attachment_url($thumbnail_id)
            : wc_placeholder_img_src('full');

        $children = get_terms(array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'parent'     => $term->term_id,
        ));

        $terms_data[] = array(
            'term'         => $term,
            'image'        => $image_url,
            'has_children' => (!empty($children) && !is_wp_error($children)),
            'children'     => (!empty($children) && !is_wp_error($children)) ? $children : array(),
        );
    }
?>
<?php if (!is_page_template('template-parts/product-home.php') && !is_page_template('product-home.php')) : ?>
    <div class="hero-slider" id="heroSlider">
        <div class="hero-slides-track" id="heroTrack">
            <?php foreach ($terms_data as $i => $data) :
                $term = $data['term'];
            ?>
            <div class="hero-slide <?php echo ($i === 0) ? 'active' : ''; ?>" data-index="<?php echo esc_attr($i); ?>">
                <img src="<?php echo esc_url($data['image']); ?>" alt="<?php echo esc_attr($term->name); ?>">
                <div class="hero-slide-overlay">
                    <h2 class="hero-slide-title">
                        <?php echo esc_html($term->name); ?>
                    </h2>
                    <a class="hero-slide-btn" href="<?php echo esc_url(get_term_link($term)); ?>">
                        Découvrir la collection
                    </a>
                </div>
            </div>

            <?php endforeach; ?>

        </div>

        <button class="hero-arrow hero-prev" aria-label="Précédent">&#10094;</button>
        <button class="hero-arrow hero-next" aria-label="Suivant">&#10095;</button>

        <div class="hero-dots" id="heroDots">
            <?php foreach ($terms_data as $i => $data) : ?>
                <button class="hero-dot <?php echo ($i === 0) ? 'active' : ''; ?>" data-slide="<?php echo esc_attr($i); ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php } ?>