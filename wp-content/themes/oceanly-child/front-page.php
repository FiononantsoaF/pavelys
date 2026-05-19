<?php
get_header();

function pavelys_get_all_products( $limit = -1, $cat_id = null ) {
    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
    ];
    if ( $cat_id ) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $cat_id,
        ]];
    }
    return get_posts( $args );
}

$all_products = pavelys_get_all_products();
shuffle( $all_products );
$selection1 = array_slice( $all_products, 0, 4 );

$categories = get_terms([
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'exclude'    => get_option('default_product_cat'),
]);

$selection2 = [];
if ( ! is_wp_error( $categories ) ) {
    foreach ( $categories as $cat ) {
        $prods = pavelys_get_all_products( 4, $cat->term_id );
        if ( ! empty( $prods ) ) {
            $selection2[] = [ 'cat' => $cat, 'products' => $prods ];
        }
    }
}
?>

<style>
/* ── Base & centrage ──────────────────────────────── */
.pavelys-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 32px;
    box-sizing: border-box;
}

/* ── Sélections ───────────────────────────────────── */
.pavelys-selections {
    padding: 80px 0 60px;
    background: #faf8f8;
    width: 100%;
}
.selection-block { margin-bottom: 72px; }
.selection-header { margin-bottom: 32px; }
.selection-badge {
    display: inline-block;
    background: #6b8e6e;
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 10px;
}
.selection-desc { color: #666; font-size: .95rem; margin: 0; }

.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}
@media (max-width: 900px) { .products-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .products-grid { grid-template-columns: 1fr 1fr; gap: 12px; } }

.product-card { border-radius: 12px; overflow: hidden; }
.product-card-inner {
    display: block; text-decoration: none; color: inherit;
    background: #fff; border-radius: 12px; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .25s ease, box-shadow .25s ease;
    height: 100%;
}
.product-card-inner:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.12); }
.product-img-wrap { position: relative; aspect-ratio: 4/3; overflow: hidden; background: #f0ede8; }
.product-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
.product-card-inner:hover .product-img-wrap img { transform: scale(1.06); }
.product-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; background: #e8e4de; }
.product-overlay { position: absolute; inset: 0; background: rgba(107,142,110,0); display: flex; align-items: flex-end; justify-content: center; padding-bottom: 16px; transition: background .3s ease; }
.product-card-inner:hover .product-overlay { background: rgba(107,142,110,.45); }
.product-cta { color: #fff; font-size: .82rem; font-weight: 600; letter-spacing: .06em; background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.6); padding: 6px 16px; border-radius: 20px; opacity: 0; transform: translateY(8px); transition: opacity .3s ease, transform .3s ease; backdrop-filter: blur(4px); }
.product-card-inner:hover .product-cta { opacity: 1; transform: translateY(0); }
.product-info { padding: 14px 16px 16px; }
.product-name { font-size: .92rem; font-weight: 600; color: #1a1a1a; margin: 0 0 6px; line-height: 1.3; }
.product-price { font-size: .88rem; color: #6b8e6e; font-weight: 600; }

.category-group { margin-bottom: 52px; }
.category-group-header { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #e0ddd8; padding-bottom: 10px; }
.category-name { font-size: 1.1rem; font-weight: 700; color: #2c2c2c; margin: 0; text-transform: uppercase; letter-spacing: .06em; }
.cat-see-more { font-size: .82rem; color: #6b8e6e; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px; transition: gap .2s; }
.cat-see-more:hover { gap: 8px; }

/* ── Story section ────────────────────────────────── */
.pavelys-story { padding: 100px 0; background: #754211; color: #272624; width: 100%; }
.story-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 64px; align-items: center; }
@media (max-width: 768px) { .story-grid { grid-template-columns: 1fr; gap: 40px; } }

.story-label { display: inline-block; font-size: .72rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: #9abf9d; margin-bottom: 16px; }
.story-title { font-size: clamp(1.8rem, 3.5vw, 2.6rem); font-weight: 700; color: #f0ede6; margin: 0 0 24px; line-height: 1.15; }
.story-text p { line-height: 1.7; color: #a8a49c; margin-bottom: 14px; }
.story-pillars { display: flex; gap: 20px; flex-wrap: wrap; margin-top: 32px; }
.pillar { display: flex; align-items: center; gap: 8px; background: rgba(154,191,157,.12); border: 1px solid rgba(154,191,157,.25); padding: 8px 16px; border-radius: 30px; }
.pillar-icon { font-size: 1.1rem; }
.pillar-text { font-size: .82rem; font-weight: 600; color: #9abf9d; letter-spacing: .04em; }
.story-visual { display: flex; align-items: center; justify-content: center; }
.story-deco-card { background: rgba(255,255,255,.04); border: 1px solid rgba(154,191,157,.2); border-radius: 16px; padding: 40px 36px; position: relative; }
.story-deco-card::before { content: '\201C'; position: absolute; top: -20px; left: 20px; font-size: 5rem; color: rgba(154,191,157,.3); line-height: 1; font-family: Georgia, serif; }
.story-quote { font-size: 1.1rem; line-height: 1.65; color: white; font-style: italic; margin: 0 0 20px; border: none; padding: 0; }
.story-deco-card cite { font-size: .82rem; font-style: normal; color: #9abf9d; font-weight: 600; letter-spacing: .06em; }

/* ── Scroll Reveal ────────────────────────────────── */
.reveal {
    opacity: 0 !important;
    transform: translateY(36px);
    transition: opacity 0.65s cubic-bezier(.22,1,.36,1),
                transform 0.65s cubic-bezier(.22,1,.36,1);
    will-change: opacity, transform;
}
.reveal.visible {
    opacity: 1 !important;
    transform: translateY(0);
}
.reveal.exit {
    opacity: 0 !important;
    transform: translateY(-20px);
    transition: opacity 0.35s ease-in, transform 0.35s ease-in;
}
</style>

<div class="ph-page">

    <!-- NOS SÉLECTIONS -->
    <section class="pavelys-selections" id="nos-selections">
        <div class="pavelys-container">
            <?php if ( ! empty( $selection1 ) ) : ?>
            <div class="selection-block selection-1">
                <div class="selection-header">
                    <span class="selection-badge">Nos Sélections</span>
                    <p class="selection-desc">Une sélection renouvelée de produits d'exception issus de Madagascar.</p>
                </div>
                <div class="products-grid">
                    <?php foreach ( $selection1 as $product ) :
                        $pid     = $product->ID;
                        $wc_prod = wc_get_product( $pid );
                        if ( ! $wc_prod ) continue;
                        $img     = get_the_post_thumbnail_url( $pid, 'medium' );
                        $price   = $wc_prod->get_price_html();
                        $link    = get_permalink( $pid );
                        $name    = get_the_title( $pid );
                    ?>
                    <article class="product-card">
                        <a href="<?php echo esc_url( $link ); ?>" class="product-card-inner">
                            <div class="product-img-wrap">
                                <?php if ( $img ) : ?>
                                <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
                                <?php else : ?>
                                <div class="product-img-placeholder"><span>🌿</span></div>
                                <?php endif; ?>
                                <div class="product-overlay"><span class="product-cta">Voir le produit</span></div>
                            </div>
                            <div class="product-info">
                                <h4 class="product-name"><?php echo esc_html( $name ); ?></h4>
                                <div class="product-price"><?php echo $price; ?></div>
                            </div>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ( ! empty( $selection2 ) ) : ?>
            <div class="selection-block selection-2">
                <div class="selection-header">
                    <p class="selection-desc">Explorez nos univers produits, chacun porteur d'un savoir-faire unique.</p>
                </div>
                <?php foreach ( $selection2 as $group ) :
                    $cat      = $group['cat'];
                    $cat_link = get_term_link( $cat );
                ?>
                <div class="category-group">
                    <div class="category-group-header">
                        <h4 class="category-name"><?php echo esc_html( $cat->name ); ?></h4>
                        <?php if ( ! is_wp_error( $cat_link ) ) : ?>
                        <a href="<?php echo esc_url( $cat_link ); ?>" class="cat-see-more">
                            Voir les produits <span aria-hidden="true">↗</span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="products-grid">
                        <?php foreach ( $group['products'] as $product ) :
                            $pid     = $product->ID;
                            $wc_prod = wc_get_product( $pid );
                            if ( ! $wc_prod ) continue;
                            $img     = get_the_post_thumbnail_url( $pid, 'medium' );
                            $price   = $wc_prod->get_price_html();
                            $link    = get_permalink( $pid );
                            $name    = get_the_title( $pid );
                        ?>
                        <article class="product-card">
                            <a href="<?php echo esc_url( $link ); ?>" class="product-card-inner">
                                <div class="product-img-wrap">
                                    <?php if ( $img ) : ?>
                                    <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
                                    <?php else : ?>
                                    <div class="product-img-placeholder"><span>🌿</span></div>
                                    <?php endif; ?>
                                    <div class="product-overlay"><span class="product-cta">Voir le produit</span></div>
                                </div>
                                <div class="product-info">
                                    <h4 class="product-name"><?php echo esc_html( $name ); ?></h4>
                                    <div class="product-price"><?php echo $price; ?></div>
                                </div>
                            </a>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- PROJET PAVELYS -->
    <section class="pavelys-story" id="projet-pavelys">
        <div class="pavelys-container">
            <div class="story-grid">
                <div class="story-text">
                    <span class="story-label reveal">Notre Projet</span>
                    <h2 class="story-title reveal" style="transition-delay:0.08s">L'aventure PavelyS</h2>
                    <p class="story-lead reveal" style="transition-delay:0.16s">
                        Plus qu'un produit, une histoire à porter.
                        PavelyS est née d'un attachement profond à Madagascar.
                        A ses matières, à ses savoir-faire, et surtout aux femmes et aux hommes qui les font vivre au quotidien.
                    </p>
                    <p class="reveal" style="transition-delay:0.22s">
                        Ici rien n'est standardisé.
                        Chaque pièce est le fruit d'un geste, d'un temps, d'une attention.
                    </p>
                    <p class="reveal" style="transition-delay:0.28s">
                        Nous avons fait le choix de vous proposer une sélection exigeante, loin des productions industrielles, pour vous offrir des créations authentiques, durables et porteuses de sens.
                        Porter, offrir ou utiliser un produit PavelyS, c'est faire entrer chez soi un peu de cette richesse : celle d'un artisanat vivant, sincère et profondément humain.
                        PavelyS, c'est la rencontre entre une histoire et la vôtre.
                    </p>
                    <div class="story-pillars reveal" style="transition-delay:0.34s">
                        <div class="pillar"><span class="pillar-icon"></span><span class="pillar-text">Authenticité</span></div>
                        <div class="pillar"><span class="pillar-icon"></span><span class="pillar-text">Esthétique &amp; Engagement</span></div>
                        <div class="pillar"><span class="pillar-icon"></span><span class="pillar-text">Respect et Engagement</span></div>
                        <div class="pillar"><span class="pillar-icon">🇲🇬</span><span class="pillar-text">Artisanat malgache</span></div>
                    </div>
                </div>
                <div class="story-visual reveal" style="transition-delay:0.42s">
                    <div class="story-deco-card">
                        <blockquote class="story-quote">
                            PavelyS, une sélection engagée, entre Madagascar et vous.
                            Des pièces vraies, pour des histoires qui le sont autant.
                        </blockquote>
                        <cite>Signature de marque</cite>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
(function() {
    const sortSel = document.getElementById('ph_sort');
    if (sortSel) {
        sortSel.addEventListener('change', function() {
            if (this.value) window.location.href = this.value;
        });
    }
    const headerInput = document.getElementById('topFilterInput');
    const pageInput   = document.getElementById('ph_product_name');
    if (headerInput && pageInput) {
        headerInput.value = pageInput.value;
        headerInput.addEventListener('input', function() {
            pageInput.value = this.value;
        });
    }
})();

// === SCROLL REVEAL ===
const revealItems = document.querySelectorAll('.reveal');

function checkReveal() {
    revealItems.forEach(function(el) {
        var rect = el.getBoundingClientRect();
        var inView = rect.top < window.innerHeight * 0.88 && rect.bottom > 0;
        if (inView) {
            el.classList.add('visible');
            el.classList.remove('exit');
        } else if (rect.top > window.innerHeight) {
            el.classList.remove('visible');
            el.classList.remove('exit');
        } else if (rect.bottom < 0) {
            el.classList.add('exit');
            el.classList.remove('visible');
        }
    });
}

window.addEventListener('scroll', checkReveal, { passive: true });
checkReveal();
</script>

<?php get_footer(); ?>