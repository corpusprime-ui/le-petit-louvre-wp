<?php
/**
 * Le Petit Louvre — CPT "Les Menus"
 *
 * Permet de créer et gérer plusieurs menus (La Carte, Noël, Été, Nouvel An…)
 * depuis le back-office WP Admin > Les Menus.
 *
 * Contenu de ce fichier :
 *   1. Enregistrement du CPT lpl_menu
 *   2. Colonnes admin (Statut · Période · PDF)
 *   3. Champs ACF du CPT (Infos, Entrées, À Partager, Plats, Desserts, Notes)
 *   4. Fonctions helpers (get_active_menu_id, get_menu_items, render_section_items)
 *   5. Création automatique du premier menu "La Carte" (one-shot admin_init)
 */

if ( ! defined( 'ABSPATH' ) ) exit;


/* ══════════════════════════════════════════════════════════════
   1. ENREGISTREMENT DU CPT
══════════════════════════════════════════════════════════════ */
add_action( 'init', function () {
    register_post_type( 'lpl_menu', [
        'labels' => [
            'name'               => 'Les Menus',
            'singular_name'      => 'Menu',
            'add_new'            => 'Ajouter un menu',
            'add_new_item'       => 'Ajouter un menu',
            'edit_item'          => 'Modifier le menu',
            'new_item'           => 'Nouveau menu',
            'all_items'          => 'Tous les menus',
            'search_items'       => 'Rechercher',
            'not_found'          => 'Aucun menu trouvé',
            'not_found_in_trash' => 'Aucun menu dans la corbeille',
            'menu_name'          => 'Les Menus',
        ],
        'public'             => false,   // pas de page front-end automatique
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'page',
        'map_meta_cap'       => true,
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-book-alt',
        'supports'           => [ 'title' ],
        'show_in_rest'       => false,
    ] );
} );


/* ══════════════════════════════════════════════════════════════
   2. COLONNES ADMIN
══════════════════════════════════════════════════════════════ */
add_filter( 'manage_lpl_menu_posts_columns', function ( $cols ) {
    unset( $cols['date'] );
    return array_merge( $cols, [
        'menu_statut'  => 'Statut',
        'menu_periode' => 'Période / Occasion',
        'menu_pdf'     => 'PDF',
        'date'         => 'Date',
    ] );
} );

add_action( 'manage_lpl_menu_posts_custom_column', function ( $col, $post_id ) {
    switch ( $col ) {
        case 'menu_statut':
            $statut = get_field( 'menu_statut', $post_id );
            $labels = [
                'actif'      => '<span style="color:#22863a;font-weight:600;">● Actif</span>',
                'inactif'    => '<span style="color:#999;">● Inactif</span>',
                'saisonnier' => '<span style="color:#e36209;font-weight:600;">● Saisonnier</span>',
            ];
            echo $labels[ $statut ] ?? '—';
            break;

        case 'menu_periode':
            $p = get_field( 'menu_periode', $post_id );
            echo $p ? '<strong>' . esc_html( $p ) . '</strong>' : '<span style="color:#aaa">—</span>';
            break;

        case 'menu_pdf':
            if ( function_exists( 'lpl_menu_pdf_path' ) && file_exists( lpl_menu_pdf_path( $post_id ) ) ) {
                echo '<a href="' . esc_url( lpl_menu_pdf_url( $post_id ) ) . '" target="_blank" style="color:#0073aa;">📄 Voir le PDF</a>';
            } else {
                echo '<span style="color:#aaa;font-size:12px;">Non généré<br>(sauvegardez le menu)</span>';
            }
            break;
    }
}, 10, 2 );

/* Rendre la colonne "Statut" triable */
add_filter( 'manage_edit-lpl_menu_sortable_columns', function ( $cols ) {
    $cols['menu_statut'] = 'menu_statut';
    return $cols;
} );


/* ══════════════════════════════════════════════════════════════
   3. CHAMPS ACF DU CPT
   Protégé par un if : si ACF n'est pas actif, on saute les
   enregistrements de champs mais on définit quand même les
   fonctions helpers (sections 4 & 5) plus bas.
══════════════════════════════════════════════════════════════ */
if ( function_exists( 'acf_add_local_field_group' ) ) :

$menu_cpt_location = [
    [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'lpl_menu' ] ],
];

/* ── Sous-champs réutilisables pour chaque section ── */
function lpl_menu_item_subfields( string $prefix ): array {
    return [
        [
            'key'          => 'field_' . $prefix . '_nom',
            'label'        => 'Nom du plat',
            'name'         => 'nom',
            'type'         => 'text',
            'column_width' => 30,
        ],
        [
            'key'          => 'field_' . $prefix . '_badge',
            'label'        => 'Badge',
            'name'         => 'badge',
            'type'         => 'text',
            'instructions' => 'Ex : Nouveauté (laisser vide si aucun)',
            'column_width' => 15,
        ],
        [
            'key'          => 'field_' . $prefix . '_desc',
            'label'        => 'Description / Ingrédients',
            'name'         => 'description',
            'type'         => 'text',
            'instructions' => 'Optionnel',
            'column_width' => 40,
        ],
        [
            'key'          => 'field_' . $prefix . '_prix',
            'label'        => 'Prix',
            'name'         => 'prix',
            'type'         => 'text',
            'instructions' => 'Ex : 13 ou 25',
            'column_width' => 15,
        ],
        [
            'key'           => 'field_' . $prefix . '_photo',
            'label'         => 'Photo du plat (optionnel)',
            'name'          => 'photo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'instructions'  => 'Laissez vide si pas de photo. Apparaît comme pastille 📷 sur La Carte.',
            'column_width'  => 0,
        ],
    ];
}

/* ─── Groupe A : Informations ─── */
acf_add_local_field_group( [
    'key'        => 'group_menu_infos',
    'title'      => '① Informations du menu',
    'menu_order' => 5,
    'location'   => $menu_cpt_location,
    'fields'     => [
        [
            'key'           => 'field_menu_statut',
            'label'         => 'Statut',
            'name'          => 'menu_statut',
            'type'          => 'select',
            'choices'       => [
                'actif'      => '🟢 Actif — affiché sur la page La Carte',
                'saisonnier' => '🟡 Saisonnier — accessible mais non affiché par défaut',
                'inactif'    => '⚫ Inactif — archivé',
            ],
            'default_value' => 'inactif',
            'instructions'  => 'Un seul menu peut être "Actif" à la fois. Il remplace le contenu de la page La Carte.',
        ],
        [
            'key'           => 'field_menu_periode',
            'label'         => 'Période / Occasion',
            'name'          => 'menu_periode',
            'type'          => 'text',
            'default_value' => '',
            'instructions'  => 'Ex : Été 2025, Noël 2025, Disponible toute l\'année (affiché sous le titre)',
        ],
        [
            'key'           => 'field_menu_description',
            'label'         => 'Courte description (optionnelle)',
            'name'          => 'menu_description',
            'type'          => 'text',
            'instructions'  => 'Ex : Menu de fêtes · 3 plats · 65€',
        ],
    ],
] );

/* ─── Groupe B : Entrées ─── */
acf_add_local_field_group( [
    'key'        => 'group_menu_entrees',
    'title'      => '② Entrées',
    'menu_order' => 10,
    'location'   => $menu_cpt_location,
    'fields'     => [
        [
            'key'          => 'field_menu_entrees',
            'label'        => 'Liste des entrées',
            'name'         => 'menu_entrees',
            'type'         => 'repeater',
            'min'          => 0,
            'layout'       => 'table',
            'button_label' => 'Ajouter une entrée',
            'sub_fields'   => lpl_menu_item_subfields( 'ent' ),
        ],
    ],
] );

/* ─── Groupe C : À Partager ─── */
acf_add_local_field_group( [
    'key'        => 'group_menu_partager',
    'title'      => '③ À Partager',
    'menu_order' => 20,
    'location'   => $menu_cpt_location,
    'fields'     => [
        [
            'key'          => 'field_menu_partager',
            'label'        => 'Liste des plats à partager',
            'name'         => 'menu_partager',
            'type'         => 'repeater',
            'min'          => 0,
            'layout'       => 'table',
            'button_label' => 'Ajouter un plat à partager',
            'sub_fields'   => lpl_menu_item_subfields( 'par' ),
        ],
    ],
] );

/* ─── Groupe D : Plats ─── */
acf_add_local_field_group( [
    'key'        => 'group_menu_plats',
    'title'      => '④ Plats',
    'menu_order' => 30,
    'location'   => $menu_cpt_location,
    'fields'     => [
        [
            'key'          => 'field_menu_plats',
            'label'        => 'Liste des plats',
            'name'         => 'menu_plats',
            'type'         => 'repeater',
            'min'          => 0,
            'layout'       => 'table',
            'button_label' => 'Ajouter un plat',
            'sub_fields'   => lpl_menu_item_subfields( 'plat' ),
        ],
    ],
] );

/* ─── Groupe E : Desserts ─── */
acf_add_local_field_group( [
    'key'        => 'group_menu_desserts',
    'title'      => '⑤ Desserts & Café Gourmand',
    'menu_order' => 40,
    'location'   => $menu_cpt_location,
    'fields'     => [
        [
            'key'          => 'field_menu_desserts_items',
            'label'        => 'Liste des desserts',
            'name'         => 'menu_desserts_items',
            'type'         => 'repeater',
            'min'          => 0,
            'layout'       => 'table',
            'button_label' => 'Ajouter un dessert',
            'sub_fields'   => lpl_menu_item_subfields( 'des' ),
        ],
        [
            'key'           => 'field_menu_glaces_boules',
            'label'         => 'Glaces & Sorbets — Détail boules',
            'name'          => 'menu_glaces_boules',
            'type'          => 'text',
            'default_value' => '1 boule, 2 boules, 3 boules',
        ],
        [
            'key'           => 'field_menu_glaces_parfums',
            'label'         => 'Glaces & Sorbets — Parfums (un groupe par ligne)',
            'name'          => 'menu_glaces_parfums',
            'type'          => 'textarea',
            'rows'          => 4,
            'default_value' => "Vanille, chocolat, café, caramel au beurre d'isigny, noix de coco, fraise\nTagada, rhum raisin, pistache\nMangue, framboise, pêche de vigne, passion, citron vert Yuzu, melon",
        ],
        [
            'key'           => 'field_menu_cafe_prix',
            'label'         => 'Café ou Thé Gourmand — Prix',
            'name'          => 'menu_cafe_prix',
            'type'          => 'text',
            'default_value' => '10/12',
            'instructions'  => 'Ex : 10/12 (petit / grand)',
        ],
    ],
] );

/* ─── Groupe F : Note de bas de page ─── */
acf_add_local_field_group( [
    'key'        => 'group_menu_notes',
    'title'      => '⑥ Note de bas de page',
    'menu_order' => 50,
    'location'   => $menu_cpt_location,
    'fields'     => [
        [
            'key'           => 'field_menu_footnote',
            'label'         => 'Note affichée en bas de la carte',
            'name'          => 'menu_footnote',
            'type'          => 'text',
            'default_value' => 'Prix nets en € · Service compris · Chèque non accepté · CB minimum 5€',
        ],
    ],
] );


endif; // acf_add_local_field_group


/* ══════════════════════════════════════════════════════════════
   4. FONCTIONS HELPERS
   Toujours disponibles même si ACF est désactivé.
══════════════════════════════════════════════════════════════ */

/**
 * Retourne l'ID du menu CPT marqué "actif" (premier trouvé).
 */
function lpl_get_active_menu_id(): int {
    $q = new WP_Query( [
        'post_type'      => 'lpl_menu',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_key'       => 'menu_statut',
        'meta_value'     => 'actif',
        'no_found_rows'  => true,
        'fields'         => 'ids',
    ] );
    return ! empty( $q->posts ) ? (int) $q->posts[0] : 0;
}

/**
 * Lit un repeater ACF d'un menu CPT et retourne un tableau d'items normalisé.
 * Chaque item : [ 'nom', 'badge', 'desc', 'prix', 'photo', 'photo_thumb' ]
 *   photo       → URL grande taille pour la lightbox
 *   photo_thumb → URL miniature (WP thumbnail 150px) pour l'affichage carte (72px)
 */
function lpl_get_menu_items( string $field_name, int $post_id ): array {
    $items = [];
    if ( ! function_exists( 'have_rows' ) || ! have_rows( $field_name, $post_id ) ) return $items;
    while ( have_rows( $field_name, $post_id ) ) {
        the_row();
        $photo_field  = get_sub_field( 'photo' );
        $photo_full   = '';
        $photo_thumb  = '';
        if ( is_array( $photo_field ) && ! empty( $photo_field['url'] ) ) {
            // WP large (1024px) pour lightbox, thumbnail (150px, 2× retina à 72px) pour vignette
            $photo_full  = $photo_field['sizes']['large']     ?? $photo_field['url'];
            $photo_thumb = $photo_field['sizes']['thumbnail'] ?? $photo_field['url'];
        } elseif ( is_string( $photo_field ) && $photo_field ) {
            $photo_full  = $photo_field;
            $photo_thumb = $photo_field;
        }
        $items[] = [
            'nom'         => sanitize_text_field( get_sub_field( 'nom' )         ?? '' ),
            'badge'       => sanitize_text_field( get_sub_field( 'badge' )       ?? '' ),
            'desc'        => sanitize_text_field( get_sub_field( 'description' ) ?? '' ),
            'prix'        => sanitize_text_field( get_sub_field( 'prix' )        ?? '' ),
            'photo'       => esc_url( $photo_full ),
            'photo_thumb' => esc_url( $photo_thumb ),
        ];
    }
    return $items;
}

/**
 * Affiche une liste d'items au format carte (HTML).
 * Utilisé dans page-carte.php pour chaque section.
 */
function lpl_render_section_items( array $items ): void {
    foreach ( $items as $item ) {
        if ( empty( $item['nom'] ) ) continue;
        $has_photo   = ! empty( $item['photo'] );
        $photo_full  = $has_photo ? esc_url( $item['photo'] )                                          : '';
        $photo_thumb = $has_photo ? esc_url( $item['photo_thumb'] ?? $item['photo'] )                  : '';
        $photo_alt   = esc_attr( $item['nom'] ); ?>
        <div class="carte-item py-2<?php echo $has_photo ? ' carte-item--has-photo' : ''; ?>">
          <div class="d-flex align-items-start gap-3">

            <?php if ( $has_photo ) : ?>
            <button class="carte-thumb-btn" data-photo="<?php echo $photo_full; ?>" data-name="<?php echo $photo_alt; ?>" aria-label="Voir la photo de <?php echo $photo_alt; ?>">
              <img src="<?php echo $photo_thumb; ?>"
                   alt="<?php echo $photo_alt; ?>"
                   class="carte-thumb-img"
                   width="72" height="72"
                   loading="lazy"
                   decoding="async">
            </button>
            <?php endif; ?>

            <div class="carte-item-content flex-grow-1 min-w-0">
              <div class="carte-item-top d-flex align-items-center gap-2">
                <span class="carte-dish"><?php echo esc_html( $item['nom'] ); ?>
                  <?php if ( ! empty( $item['badge'] ) ) : ?>
                    <span class="carte-badge"><?php echo esc_html( $item['badge'] ); ?></span>
                  <?php endif; ?>
                </span>
                <span class="carte-dots flex-grow-1 d-none d-sm-block" aria-hidden="true"></span>
                <span class="carte-price flex-shrink-0 ms-auto ms-sm-0"><?php echo esc_html( $item['prix'] ); ?>&thinsp;€</span>
              </div>
              <?php if ( ! empty( $item['desc'] ) ) : ?>
                <p class="carte-desc mb-0"><?php echo esc_html( $item['desc'] ); ?></p>
              <?php endif; ?>
            </div>

          </div>
        </div>
        <?php
    }
}


/* ══════════════════════════════════════════════════════════════
   5. CRÉATION AUTOMATIQUE DU PREMIER MENU "LA CARTE"
   One-shot : ne s'exécute qu'une seule fois via l'option WP.
══════════════════════════════════════════════════════════════ */
add_action( 'admin_init', function () {

    if ( ! function_exists( 'update_field' ) )       return;
    if ( get_option( 'lpl_menu_v1_initialized' ) )   return;
    if ( ! current_user_can( 'manage_options' ) )    return;

    /* Créer le post CPT */
    $post_id = wp_insert_post( [
        'post_title'  => 'La Carte',
        'post_type'   => 'lpl_menu',
        'post_status' => 'publish',
        'post_name'   => 'la-carte',
    ] );

    if ( ! $post_id || is_wp_error( $post_id ) ) return;

    /* ── Infos ── */
    update_field( 'menu_statut',      'actif',                    $post_id );
    update_field( 'menu_periode',     'Disponible toute l\'année', $post_id );
    update_field( 'menu_description', 'Cuisine Fusion · Arcachon', $post_id );

    /* ── Entrées ── */
    update_field( 'menu_entrees', [
        [ 'nom' => 'La Burrata',                    'badge' => 'Nouveauté', 'description' => 'Tomates cerises, pêches, huile d\'olive, basilic',                        'prix' => '11' ],
        [ 'nom' => 'Ceviche de poisson aux agrumes','badge' => '',          'description' => 'Marinade 3 agrumes, brunoise pastèque kiwi, graines de courge, pickles maison', 'prix' => '13' ],
        [ 'nom' => 'Carpaccio de Boeuf',            'badge' => '',          'description' => 'Tomates cerises, mesclun, copeaux de parmesan',                            'prix' => '13' ],
        [ 'nom' => 'Feta grillée au miel',          'badge' => '',          'description' => 'Tartare de légumes de saison, salade',                                     'prix' => '13' ],
        [ 'nom' => 'Carpaccio de Melon',            'badge' => 'Nouveauté', 'description' => 'Jambon serrano, crumble d\'olives noires et parmesan, basilic',            'prix' => '13' ],
    ], $post_id );

    /* ── À Partager ── */
    update_field( 'menu_partager', [
        [ 'nom' => 'Planche de charcuterie',     'badge' => 'Nouveauté', 'description' => 'Terrine maison, charcuterie artisanale',  'prix' => '18' ],
        [ 'nom' => 'Planche de fromages',        'badge' => '',          'description' => 'Sélection de fromages affinés',            'prix' => '18' ],
        [ 'nom' => 'Planche mixte',              'badge' => '',          'description' => 'Entre copains',                             'prix' => '27' ],
        [ 'nom' => 'Cannelés salés',             'badge' => '',          'description' => 'Lard et piment d\'espelette',              'prix' => '13' ],
        [ 'nom' => 'Panier de légumes croquants','badge' => 'Nouveauté', 'description' => 'Sauce tzatziki et tapenade',              'prix' => '13' ],
    ], $post_id );

    /* ── Plats ── */
    update_field( 'menu_plats', [
        [ 'nom' => 'Poulpe grillé',                'badge' => 'Nouveauté', 'description' => 'Risotto façon paëlla, sauce gremolata',                                                  'prix' => '25' ],
        [ 'nom' => 'Toast avocado',                'badge' => '',          'description' => 'Pain de campagne toasté, guacamole, paprika fumé, oeuf parfait, chips de lard',          'prix' => '19' ],
        [ 'nom' => 'Ceviche de poisson',           'badge' => '',          'description' => 'Marinade 3 agrumes, avocat, menthe fraîche, quinoa aux légumes croquants',               'prix' => '22' ],
        [ 'nom' => 'Tataki de boeuf',              'badge' => '',          'description' => 'Marinade soja, citron vert, gingembre, herbes thaï, salade, frites maison',              'prix' => '19' ],
        [ 'nom' => 'Escalope milanaise',           'badge' => '',          'description' => 'Veau pané, polenta snackée, poêlée d\'aubergines, tomates cerises et basilic',           'prix' => '23' ],
        [ 'nom' => 'Poisson sauvage à la plancha', 'badge' => '',          'description' => 'Légumes de saison',                                                                       'prix' => '25' ],
        [ 'nom' => 'Pièce du boucher 250g',        'badge' => '',          'description' => 'Frites maison et salade, jus de viande corsé',                                           'prix' => '27' ],
        [ 'nom' => 'Aubergine farcie',             'badge' => '',          'description' => 'Tomates, mozzarella, parmesan gratiné, basilic frais et salade',                         'prix' => '18' ],
    ], $post_id );

    /* ── Desserts ── */
    update_field( 'menu_desserts_items', [
        [ 'nom' => 'Tarte aux Nectarines',               'badge' => '', 'description' => 'Sorbet citron vert yuzu',                                                           'prix' => '11' ],
        [ 'nom' => 'Brioche Perdue Glacée Vanille',      'badge' => '', 'description' => 'Caramel et fruits rouges',                                                          'prix' => '12' ],
        [ 'nom' => 'Tiramisu Maison',                    'badge' => '', 'description' => '',                                                                                  'prix' => '9'  ],
        [ 'nom' => 'Mousse au Chocolat',                 'badge' => '', 'description' => 'Croquant au praliné',                                                               'prix' => '10' ],
        [ 'nom' => "L'Instant Fraise du Petit Louvre",   'badge' => '', 'description' => 'Fraise, menthe, basilic, glace vanille, crème fouettée, écrasé de biscuits',       'prix' => '12' ],
    ], $post_id );

    update_field( 'menu_glaces_boules',  '1 boule, 2 boules, 3 boules', $post_id );
    update_field( 'menu_glaces_parfums',
        "Vanille, chocolat, café, caramel au beurre d'isigny, noix de coco, fraise\nTagada, rhum raisin, pistache\nMangue, framboise, pêche de vigne, passion, citron vert Yuzu, melon",
        $post_id
    );
    update_field( 'menu_cafe_prix', '10/12', $post_id );

    /* ── Note bas de page ── */
    update_field( 'menu_footnote',
        'Prix nets en € · Service compris · Chèque non accepté · CB minimum 5€',
        $post_id
    );

    /* Marquer comme créé */
    update_option( 'lpl_menu_v1_initialized', true );

}, 20 ); // priorité 20 = après l'init ACF


/* ══════════════════════════════════════════════════════════════
   6. AUTO-DÉSACTIVATION — Un seul menu "Actif" à la fois
   Quand un menu est sauvegardé comme "actif", tous les autres
   menus actifs passent automatiquement en "saisonnier"
   (mis de côté, réactivables à tout moment).
══════════════════════════════════════════════════════════════ */
add_action( 'acf/save_post', function ( $post_id ) {

    if ( wp_is_post_revision( $post_id ) )  return;
    if ( wp_is_post_autosave( $post_id ) )  return;
    if ( ! is_numeric( $post_id ) )         return;

    $post = get_post( (int) $post_id );
    if ( ! $post || $post->post_type !== 'lpl_menu' ) return;
    if ( ! function_exists( 'get_field' ) )           return;

    /* Lire le statut qui vient d'être sauvegardé */
    $statut = get_field( 'menu_statut', (int) $post_id );
    if ( $statut !== 'actif' ) return;

    /* Passer tous les AUTRES menus actifs en "saisonnier" */
    $autres = get_posts( [
        'post_type'      => 'lpl_menu',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post__not_in'   => [ (int) $post_id ],
        'fields'         => 'ids',
        'meta_query'     => [ [ 'key' => 'menu_statut', 'value' => 'actif', 'compare' => '=' ] ],
    ] );

    foreach ( $autres as $autre_id ) {
        update_field( 'menu_statut', 'saisonnier', $autre_id );
    }

}, 30 ); // priorité 30 = après la sauvegarde ACF (10) et la génération PDF (20)


/* ══════════════════════════════════════════════════════════════
   7. ALERTE JS BACK-OFFICE
   Confirmation avant de sauvegarder un menu comme "Actif"
   si un autre menu est déjà actif.
══════════════════════════════════════════════════════════════ */
add_action( 'admin_footer', function () {

    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'lpl_menu' || $screen->base !== 'post' ) return;

    /* Trouver l'ID du menu actif actuel (autre que celui en cours d'édition) */
    $current_id    = (int) ( $_GET['post'] ?? 0 );
    $active_menus  = get_posts( [
        'post_type'      => 'lpl_menu',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'post__not_in'   => $current_id ? [ $current_id ] : [],
        'fields'         => 'ids',
        'meta_query'     => [ [ 'key' => 'menu_statut', 'value' => 'actif', 'compare' => '=' ] ],
    ] );
    $has_other_active = ! empty( $active_menus );
    $active_title     = $has_other_active ? esc_js( html_entity_decode( get_the_title( $active_menus[0] ), ENT_QUOTES, 'UTF-8' ) ) : '';
    ?>
    <script>
    (function ($) {
        if (typeof acf === 'undefined') return;

        var hasOtherActive = <?php echo $has_other_active ? 'true' : 'false'; ?>;
        var activeTitle    = '<?php echo $active_title; ?>';

        var confirmed = false;

        /* Intercepter le submit du formulaire */
        $('form#post').on('submit', function (e) {
            if (!hasOtherActive) return true;
            var statut = $('select[name="acf[field_menu_statut]"]').val();
            if (statut !== 'actif') return true;
            if (confirmed) return true;  /* déjà confirmé via le bouton inline */

            /* Bloquer et afficher le bandeau de confirmation */
            e.preventDefault();
            showConfirmBanner();
        });

        /* Bandeau de confirmation inline — remplace window.confirm() */
        function showConfirmBanner() {
            if ($('#lpl-menu-confirm-banner').length) return;
            var banner = $(
                '<div id="lpl-menu-confirm-banner" style="'
                + 'background:#e6f4ea;border-left:4px solid #2d7a3a;padding:12px 16px;'
                + 'margin-top:8px;border-radius:3px;font-size:13px;color:#1a4a22;'
                + 'display:flex;align-items:center;gap:12px;flex-wrap:wrap;">'
                + '<span>✅ Le menu <strong>' + activeTitle + '</strong> passera en 🟡 Saisonnier '
                + '— il reste réactivable à tout moment. Ce menu prendra sa place.</span>'
                + '<span style="display:flex;gap:8px;margin-left:auto;">'
                + '<button type="button" id="lpl-confirm-yes" style="'
                + 'background:#2d7a3a;color:#fff;border:none;border-radius:3px;'
                + 'padding:5px 14px;cursor:pointer;font-size:12px;font-weight:600;">Confirmer</button>'
                + '<button type="button" id="lpl-confirm-no" style="'
                + 'background:#fff;color:#555;border:1px solid #ccc;border-radius:3px;'
                + 'padding:5px 14px;cursor:pointer;font-size:12px;">Annuler</button>'
                + '</span>'
                + '</div>'
            );

            /* Insérer sous le select ACF */
            var $field = $('select[name="acf[field_menu_statut]"]').closest('.acf-field');
            if ($field.length) { $field.after(banner); }
            else               { $('form#post').prepend(banner); }

            /* Confirmer → soumettre pour de vrai */
            $('#lpl-confirm-yes').on('click', function () {
                confirmed = true;
                banner.remove();
                $('form#post').trigger('submit');
            });

            /* Annuler → fermer le bandeau */
            $('#lpl-confirm-no').on('click', function () {
                banner.remove();
            });
        }

        /* Avertissement visuel si on change le select vers "actif" */
        $(document).on('change', 'select[name="acf[field_menu_statut]"]', function () {
            var $notice = $('#lpl-menu-actif-notice');
            $('#lpl-menu-confirm-banner').remove();
            confirmed = false;
            if ($(this).val() === 'actif' && hasOtherActive) {
                if (!$notice.length) {
                    $(this).closest('.acf-field').after(
                        '<div id="lpl-menu-actif-notice" style="'
                        + 'background:#fff3cd;border-left:4px solid #e36209;padding:10px 14px;'
                        + 'margin-top:8px;border-radius:3px;font-size:13px;color:#856404;">'
                        + '⚠️ Le menu <strong>' + activeTitle + '</strong> passera en 🟡 Saisonnier '
                        + 'à la sauvegarde — il reste réactivable à tout moment.'
                        + '</div>'
                    );
                }
            } else {
                $notice.remove();
            }
        });

    }(jQuery));
    </script>
    <?php
} );
