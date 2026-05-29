<?php
/**
 * Le Petit Louvre — Champs ACF de la page d'accueil
 * Enregistrés en PHP : apparaissent automatiquement dans WP Admin > ACF.
 * Modifier les valeurs depuis Admin > Pages > Accueil (onglet en bas).
 *
 * Structure :
 *   1. Hero           — label, titre H1, tagline, dispo, photos slideshow
 *   2. Présentation   — label, titre H2, texte, photo
 *   3. Citation       — texte de la blockquote
 *   4. Speakeasy      — label, titre H2, texte, photos slideshow
 *   5. Privatisation  — label, titre H2, texte, photo
 *   6. Nos Plats      — label, titre H2, repeater images
 *   7. Le Petit Louvre — titre H2, texte, photo
 *   8. Avis clients   — titre H2, repeater avis
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

/* ─────────────────────────────────────────────────────────────
   Conditions de localisation réutilisables
───────────────────────────────────────────────────────────── */
$options_location = [
    [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'theme-options' ] ],
];
$carte_page_location = [
    [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-carte.php' ] ],
];
$resa_page_location = [
    [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-reservation.php' ] ],
];
$contact_page_location = [
    [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-contact.php' ] ],
];

/* ─────────────────────────────────────────────────────────────
   Condition commune : page d'accueil uniquement
───────────────────────────────────────────────────────────── */
$front_page_location = [
    [
        [
            'param'    => 'page_type',
            'operator' => '==',
            'value'    => 'front_page',
        ],
    ],
];

/* ══════════════════════════════════════════════════════════════
   1. HERO
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'         => 'group_lpl_hero',
    'title'       => '① Hero — Bannière principale',
    'menu_order'  => 10,
    'location'    => $front_page_location,
    'fields'      => [

        [
            'key'           => 'field_hero_label',
            'label'         => 'Label (sous-titre capslock)',
            'name'          => 'hero_label',
            'type'          => 'text',
            'default_value' => 'Cuisine Fusion · Arcachon',
            'instructions'  => 'Texte en petites capitales au-dessus du H1.',
        ],
        [
            'key'           => 'field_hero_title',
            'label'         => 'Titre H1',
            'name'          => 'hero_title',
            'type'          => 'text',
            'default_value' => 'Le Petit Louvre',
        ],
        [
            'key'           => 'field_hero_tagline',
            'label'         => 'Tagline (phrase italic)',
            'name'          => 'hero_tagline',
            'type'          => 'text',
            'default_value' => 'Gastronomie, terrasse & bar à vins au cœur du Bassin',
        ],
        [
            'key'           => 'field_hero_availability',
            'label'         => 'Texte disponibilité (bas de hero)',
            'name'          => 'hero_availability',
            'type'          => 'text',
            'default_value' => 'Tables disponibles ce soir · Réservation conseillée',
        ],
        [
            'key'          => 'field_hero_images',
            'label'        => 'Photos du slideshow hero',
            'name'         => 'hero_images',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez 2 à 5 photos. La première sera chargée en priorité (LCP).',
            'min'          => 1,
            'max'          => 6,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_hero_images_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_hero_images_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'instructions' => 'Ex : Terrasse ensoleillée Le Petit Louvre Arcachon',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   2. PRÉSENTATION — Notre Restaurant
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_presentation',
    'title'      => '② Présentation — Notre Restaurant',
    'menu_order' => 20,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_pres_label',
            'label'         => 'Label (capslock)',
            'name'          => 'pres_label',
            'type'          => 'text',
            'default_value' => 'Notre Restaurant',
        ],
        [
            'key'           => 'field_pres_title',
            'label'         => 'Titre H2',
            'name'          => 'pres_title',
            'type'          => 'textarea',
            'rows'          => 2,
            'default_value' => 'Une invitation à la cuisine fusion, le temps d\'un verre ou d\'un repas',
        ],
        [
            'key'           => 'field_pres_body',
            'label'         => 'Texte de présentation',
            'name'          => 'pres_body',
            'type'          => 'wysiwyg',
            'toolbar'       => 'basic',
            'media_upload'  => 0,
            'default_value' => '<p>Au cœur d\'Arcachon, Le Petit Louvre incarne l\'esprit d\'un bistrot contemporain, où les saveurs se croisent et les moments se partagent.</p><p>Du déjeuner au dîner, le restaurant propose une cuisine généreuse et créative, mêlant tradition et touches actuelles, dans une ambiance chaleureuse et décontractée.</p><p>En salle ou sur la terrasse, installez-vous pour profiter d\'un cadre convivial, idéal pour un repas entre amis, en famille ou une pause gourmande.</p>',
        ],
        [
            'key'           => 'field_pres_photo',
            'label'         => 'Photo de présentation',
            'name'          => 'pres_photo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'instructions'  => 'Photo affichée à gauche du texte (desktop). Masquée sur mobile.',
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   3. CITATION
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_citation',
    'title'      => '③ Citation',
    'menu_order' => 30,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_quote_text',
            'label'         => 'Texte de la citation',
            'name'          => 'quote_text',
            'type'          => 'textarea',
            'rows'          => 3,
            'default_value' => 'Une terrasse ouverte de 70 couverts, un espace lumineux avec une équipe attentive, heureuse de vous accueillir avec le sourire.',
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   4. SPEAKEASY — Bar & Vin
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_speakeasy',
    'title'      => '④ Speakeasy — Bar & Vin',
    'menu_order' => 40,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_spk_label',
            'label'         => 'Label (capslock)',
            'name'          => 'spk_label',
            'type'          => 'text',
            'default_value' => 'BAR & VIN',
        ],
        [
            'key'           => 'field_spk_title',
            'label'         => 'Titre H2',
            'name'          => 'spk_title',
            'type'          => 'text',
            'default_value' => 'Notre bar à cocktails & vins',
        ],
        [
            'key'           => 'field_spk_body',
            'label'         => 'Texte',
            'name'          => 'spk_body',
            'type'          => 'wysiwyg',
            'toolbar'       => 'basic',
            'media_upload'  => 0,
            'default_value' => '<p>Au Petit Louvre à Arcachon, le vin est bien plus qu\'un accompagnement : c\'est une véritable expérience, servie avec le sourire.</p><p>Notre carte des vins met à l\'honneur une large sélection de vins du terroir français, soigneusement choisis à travers les grandes régions viticoles de France.</p><p>Rouges de caractère, blancs élégants ou vins plus surprenants, notre sélection s\'accorde parfaitement avec les plats de notre restaurant à Arcachon.</p>',
        ],
        [
            'key'          => 'field_spk_images',
            'label'        => 'Photos du slideshow (bar/vins)',
            'name'         => 'spk_images',
            'type'         => 'repeater',
            'min'          => 1,
            'max'          => 4,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_spk_images_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'medium',
                ],
                [
                    'key'   => 'field_spk_images_alt',
                    'label' => 'Description (SEO)',
                    'name'  => 'alt',
                    'type'  => 'text',
                ],
            ],
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   5. PRIVATISATION
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_privatisation',
    'title'      => '⑤ Privatisation — Événements',
    'menu_order' => 50,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_priv_label',
            'label'         => 'Label (capslock)',
            'name'          => 'priv_label',
            'type'          => 'text',
            'default_value' => 'Événements',
        ],
        [
            'key'           => 'field_priv_title',
            'label'         => 'Titre H2',
            'name'          => 'priv_title',
            'type'          => 'text',
            'default_value' => 'Privatisation',
        ],
        [
            'key'           => 'field_priv_body',
            'label'         => 'Texte',
            'name'          => 'priv_body',
            'type'          => 'wysiwyg',
            'toolbar'       => 'basic',
            'media_upload'  => 0,
            'default_value' => '<p>Le Petit Louvre à Arcachon vous propose la privatisation de ses espaces pour vos événements privés et professionnels.</p><p>Déjeuner intimiste, dîner d\'entreprise, anniversaire ou cocktail : nous adaptons le restaurant selon vos besoins, avec un service attentif et une cuisine soignée.</p><p>Offrez à vos invités un événement sur-mesure au cœur du bassin d\'Arcachon, dans un cadre élégant et convivial.</p>',
        ],
        [
            'key'           => 'field_priv_photo',
            'label'         => 'Photo',
            'name'          => 'priv_photo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   6. NOS PLATS — Slider
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_plats',
    'title'      => '⑥ Nos Plats — Slider',
    'menu_order' => 60,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_plats_label',
            'label'         => 'Label (capslock)',
            'name'          => 'plats_label',
            'type'          => 'text',
            'default_value' => 'Notre carte',
        ],
        [
            'key'           => 'field_plats_title',
            'label'         => 'Titre H2',
            'name'          => 'plats_title',
            'type'          => 'text',
            'default_value' => 'Nos plats',
        ],
        [
            'key'          => 'field_plats_slider',
            'label'        => 'Photos des plats',
            'name'         => 'plats_slider',
            'type'         => 'repeater',
            'instructions' => 'Glissez-déposez pour réorganiser. Format carré recommandé.',
            'layout'       => 'table',
            'button_label' => 'Ajouter un plat',
            'sub_fields'   => [
                [
                    'key'           => 'field_plats_slider_img',
                    'label'         => 'Photo du plat',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_plats_slider_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'instructions' => 'Ex : Burrata, tomates et basilic — entrée signature',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   7. LE PETIT LOUVRE — Brand Story
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_brandstory',
    'title'      => '⑦ Le Petit Louvre — Notre histoire',
    'menu_order' => 70,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_lpl_title',
            'label'         => 'Titre H2',
            'name'          => 'lpl_title',
            'type'          => 'text',
            'default_value' => 'Le Petit Louvre',
        ],
        [
            'key'           => 'field_lpl_body',
            'label'         => 'Texte',
            'name'          => 'lpl_body',
            'type'          => 'wysiwyg',
            'toolbar'       => 'basic',
            'media_upload'  => 0,
            'default_value' => '<p>Le Petit Louvre vous invite à vivre une expérience culinaire raffinée, dans une atmosphère unique où l\'élégance rencontre l\'émotion.</p><p>Idéalement situé au cœur d\'Arcachon, Le Petit Louvre incarne l\'esprit de la gastronomie parisienne mélangé à la tradition : une cuisine authentique, exigeante et profondément ancrée dans le goût.</p><p>Derrière son nom discret se révèle un lieu au design contemporain et soigné, où chaque détail a été pensé pour créer une ambiance chaleureuse, zen et résolument haut de gamme.</p><p>Aux fourneaux, le chef imagine des plats d\'exception, alliant tradition et création, pour offrir une cuisine précise, inspirée et généreuse, aussi belle à regarder qu\'à savourer.</p>',
        ],
        [
            'key'           => 'field_lpl_photo',
            'label'         => 'Photo',
            'name'          => 'lpl_photo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   9. NOTRE UNIVERS — Galerie
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_galerie',
    'title'      => '⑨ Notre univers — Galerie',
    'menu_order' => 90,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'          => 'field_galerie_images',
            'label'        => 'Photos de la galerie',
            'name'         => 'galerie_images',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez les photos du slider "Notre univers". Format paysage recommandé.',
            'min'          => 1,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_galerie_images_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_galerie_images_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'instructions' => 'Ex : Salle du restaurant Le Petit Louvre Arcachon',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   8. AVIS CLIENTS
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_lpl_avis',
    'title'      => '⑧ Avis clients',
    'menu_order' => 80,
    'location'   => $front_page_location,
    'fields'     => [

        [
            'key'           => 'field_avis_section_title',
            'label'         => 'Titre de section H2',
            'name'          => 'avis_section_title',
            'type'          => 'text',
            'default_value' => 'Ce que disent nos clients',
        ],
        [
            'key'          => 'field_avis_list',
            'label'        => 'Avis',
            'name'         => 'avis_list',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez ou modifiez les avis clients. Minimum 3 recommandé.',
            'min'          => 1,
            'layout'       => 'row',
            'button_label' => 'Ajouter un avis',
            'sub_fields'   => [
                [
                    'key'           => 'field_avis_quote',
                    'label'         => 'Texte de l\'avis',
                    'name'          => 'quote',
                    'type'          => 'textarea',
                    'rows'          => 3,
                    'column_width'  => 50,
                ],
                [
                    'key'          => 'field_avis_name',
                    'label'        => 'Nom du client',
                    'name'         => 'name',
                    'type'         => 'text',
                    'column_width' => 20,
                ],
                [
                    'key'     => 'field_avis_via',
                    'label'   => 'Source',
                    'name'    => 'via',
                    'type'    => 'select',
                    'choices' => [
                        'Via Google'      => 'Via Google',
                        'Via TripAdvisor' => 'Via TripAdvisor',
                        'Via TheFork'     => 'Via TheFork',
                    ],
                    'default_value' => 'Via Google',
                    'column_width'  => 20,
                ],
                [
                    'key'          => 'field_avis_stars',
                    'label'        => 'Note',
                    'name'         => 'stars',
                    'type'         => 'select',
                    'choices'      => [
                        '5' => '★★★★★',
                        '4' => '★★★★☆',
                        '3' => '★★★☆☆',
                    ],
                    'default_value' => '5',
                    'column_width'  => 10,
                ],
                [
                    'key'           => 'field_avis_avatar',
                    'label'         => 'Photo (avatar)',
                    'name'          => 'avatar',
                    'type'          => 'image',
                    'instructions'  => 'Optionnel — laissez vide pour utiliser un avatar automatique.',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 15,
                ],
            ],
        ],

    ],
] );


/* ══════════════════════════════════════════════════════════════
   PAGE LA CARTE
   Les plats (Entrées, À Partager, Plats, Desserts) sont gérés
   dans WP Admin > Les Menus (CPT lpl_menu).
   Ces champs gèrent uniquement la mise en page de la page.
══════════════════════════════════════════════════════════════ */

/* ──────────────────────────────────────────────────────────────
   C1. HERO — Bannière
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_carte_hero',
    'title'      => '① La Carte — Hero',
    'menu_order' => 10,
    'location'   => $carte_page_location,
    'fields'     => [

        [
            'key'           => 'field_carte_hero_label',
            'label'         => 'Label (capslock)',
            'name'          => 'carte_hero_label',
            'type'          => 'text',
            'default_value' => 'Cuisine Fusion · Arcachon',
            'instructions'  => 'Texte en petites capitales au-dessus du H1.',
        ],
        [
            'key'           => 'field_carte_hero_tagline',
            'label'         => 'Tagline',
            'name'          => 'carte_hero_tagline',
            'type'          => 'text',
            'default_value' => 'Par le chef Marco · Une invitation à partager saveurs et émotions',
        ],
        [
            'key'          => 'field_carte_hero_images',
            'label'        => 'Photos du slideshow hero',
            'name'         => 'carte_hero_images',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez 2 à 4 photos de plats. La première est prioritaire (LCP).',
            'min'          => 1,
            'max'          => 6,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_carte_hero_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_carte_hero_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'instructions' => 'Ex : Poulpe grillé, risotto façon paëlla — Le Petit Louvre',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   C2. TITRES DE SECTIONS
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_carte_sections',
    'title'      => '② La Carte — Titres des sections',
    'menu_order' => 20,
    'location'   => $carte_page_location,
    'fields'     => [

        [
            'key'           => 'field_carte_main_title',
            'label'         => 'Titre principal (centré)',
            'name'          => 'carte_main_title',
            'type'          => 'text',
            'default_value' => 'LA CARTE',
            'instructions'  => 'Grand titre décoratif avec lignes de part et d\'autre.',
        ],
        [
            'key'           => 'field_carte_chef_nom',
            'label'         => 'Pastille chef — texte',
            'name'          => 'carte_chef_nom',
            'type'          => 'text',
            'default_value' => 'Par le chef Marco',
            'instructions'  => 'Texte affiché dans la pastille à droite du titre "LA CARTE". Laisser vide pour masquer la pastille.',
        ],
        [
            'key'           => 'field_carte_chef_photo',
            'label'         => 'Pastille chef — photo',
            'name'          => 'carte_chef_photo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'thumbnail',
            'instructions'  => 'Photo ronde du chef dans la pastille. Format carré recommandé (ex : 200×200 px).',
        ],
        [
            'key'           => 'field_carte_titre_entrees',
            'label'         => 'Titre section Entrées',
            'name'          => 'carte_titre_entrees',
            'type'          => 'text',
            'default_value' => 'Entrées',
        ],
        [
            'key'           => 'field_carte_titre_partager',
            'label'         => 'Titre section À Partager',
            'name'          => 'carte_titre_partager',
            'type'          => 'text',
            'default_value' => 'À Partager',
        ],
        [
            'key'           => 'field_carte_titre_plats',
            'label'         => 'Titre section Plats',
            'name'          => 'carte_titre_plats',
            'type'          => 'text',
            'default_value' => 'Plats',
        ],
        [
            'key'           => 'field_carte_titre_desserts',
            'label'         => 'Titre section Desserts',
            'name'          => 'carte_titre_desserts',
            'type'          => 'text',
            'default_value' => 'Desserts',
        ],

    ],
] );


/* ══════════════════════════════════════════════════════════════
   PAGE RÉSERVATION
══════════════════════════════════════════════════════════════ */

/* ──────────────────────────────────────────────────────────────
   R1. HERO
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_resa_hero',
    'title'      => '① Réservation — Hero',
    'menu_order' => 10,
    'location'   => $resa_page_location,
    'fields'     => [

        [
            'key'           => 'field_resa_hero_label',
            'label'         => 'Label (capslock)',
            'name'          => 'resa_hero_label',
            'type'          => 'text',
            'default_value' => 'Restaurant · Arcachon',
        ],
        [
            'key'           => 'field_resa_hero_title',
            'label'         => 'Titre H1',
            'name'          => 'resa_hero_title',
            'type'          => 'text',
            'default_value' => 'Réservez votre table',
        ],
        [
            'key'           => 'field_resa_hero_tagline_1',
            'label'         => 'Tagline — ligne 1',
            'name'          => 'resa_hero_tagline_1',
            'type'          => 'text',
            'default_value' => 'Votre table garantie',
            'instructions'  => 'Laisser vide pour masquer cette ligne.',
        ],
        [
            'key'           => 'field_resa_hero_tagline_2',
            'label'         => 'Tagline — ligne 2',
            'name'          => 'resa_hero_tagline_2',
            'type'          => 'text',
            'default_value' => 'Confirmation par email sous 2h',
            'instructions'  => 'Laisser vide pour masquer cette ligne.',
        ],
        [
            'key'          => 'field_resa_hero_images',
            'label'        => 'Photos du hero (crossfade)',
            'name'         => 'resa_hero_images',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez 2 à 4 photos. La première est prioritaire (LCP).',
            'min'          => 1,
            'max'          => 4,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_resa_hero_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_resa_hero_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   R2. INFOS PRATIQUES (trust strip + horaires + adresse)
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_resa_infos',
    'title'      => '② Réservation — Infos pratiques',
    'menu_order' => 20,
    'location'   => $resa_page_location,
    'fields'     => [

        [
            'key'           => 'field_resa_trust_1',
            'label'         => 'Bandeau — Intérieur',
            'name'          => 'resa_trust_1',
            'type'          => 'text',
            'default_value' => '50 couverts en intérieur',
            'instructions'  => 'Texte sous l\'icône table (bandeau sous le hero)',
        ],
        [
            'key'           => 'field_resa_trust_2',
            'label'         => 'Bandeau — Extérieur',
            'name'          => 'resa_trust_2',
            'type'          => 'text',
            'default_value' => '70 couverts en extérieur',
            'instructions'  => 'Texte sous l\'icône terrasse',
        ],
        [
            'key'           => 'field_resa_trust_3',
            'label'         => 'Bandeau — Confirmation',
            'name'          => 'resa_trust_3',
            'type'          => 'text',
            'default_value' => 'Table confirmée sous 2h',
            'instructions'  => 'Texte sous l\'icône validation',
        ],
        [
            'key'           => 'field_resa_horaires_1',
            'label'         => 'Horaires — Ligne 1',
            'name'          => 'resa_horaires_1',
            'type'          => 'text',
            'default_value' => 'Lun – Dim  ·  9h → 23h',
        ],
        [
            'key'           => 'field_resa_horaires_2',
            'label'         => 'Horaires — Ligne 2 (optionnel)',
            'name'          => 'resa_horaires_2',
            'type'          => 'text',
            'default_value' => '',
        ],
        [
            'key'           => 'field_resa_horaires_closed',
            'label'         => 'Horaires — Jour fermé (optionnel)',
            'name'          => 'resa_horaires_closed',
            'type'          => 'text',
            'default_value' => '',
        ],
        [
            'key'           => 'field_resa_adresse',
            'label'         => 'Adresse',
            'name'          => 'resa_adresse',
            'type'          => 'text',
            'default_value' => '14 Pl. Lucien de Gracia, 33120 Arcachon',
        ],
        [
            'key'           => 'field_resa_maps_url',
            'label'         => 'Lien Google Maps',
            'name'          => 'resa_maps_url',
            'type'          => 'url',
            'default_value' => 'https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon',
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   R3. 3 BONNES RAISONS
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_resa_raisons',
    'title'      => '③ Réservation — 3 Bonnes Raisons',
    'menu_order' => 30,
    'location'   => $resa_page_location,
    'fields'     => [

        [
            'key'          => 'field_resa_raisons_list',
            'label'        => 'Panneaux (exactement 3)',
            'name'         => 'resa_raisons',
            'type'         => 'repeater',
            'instructions' => 'Le 2e panneau est automatiquement mis en avant (featured). Renseignez le "Tag" uniquement pour ce panneau.',
            'min'          => 3,
            'max'          => 3,
            'layout'       => 'row',
            'button_label' => 'Ajouter un panneau',
            'sub_fields'   => [
                [
                    'key'          => 'field_resa_raison_titre',
                    'label'        => 'Titre',
                    'name'         => 'titre',
                    'type'         => 'text',
                ],
                [
                    'key'          => 'field_resa_raison_desc',
                    'label'        => 'Description',
                    'name'         => 'description',
                    'type'         => 'textarea',
                    'rows'         => 3,
                ],
                [
                    'key'           => 'field_resa_raison_image',
                    'label'         => 'Photo de fond',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                ],
                [
                    'key'          => 'field_resa_raison_tag',
                    'label'        => 'Tag (panneau central uniquement)',
                    'name'         => 'tag',
                    'type'         => 'text',
                    'instructions' => 'Ex : Simple & sans stress',
                ],
            ],
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   R4. LA CUISINE EN IMAGES (Marquee vertical)
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_resa_marquee',
    'title'      => '④ Réservation — La Cuisine en Images',
    'menu_order' => 40,
    'location'   => $resa_page_location,
    'fields'     => [

        [
            'key'          => 'field_resa_marquee_images',
            'label'        => 'Photos des plats (galerie défilante)',
            'name'         => 'resa_marquee_images',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez 9 à 12 photos de plats. Format 4/3 recommandé. Les photos sont réparties automatiquement en 3 colonnes.',
            'min'          => 9,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_resa_marquee_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_resa_marquee_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   R5. CTA FINAL
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_resa_cta',
    'title'      => '⑤ Réservation — CTA Final',
    'menu_order' => 50,
    'location'   => $resa_page_location,
    'fields'     => [

        [
            'key'           => 'field_resa_cta_label',
            'label'         => 'Label (capslock)',
            'name'          => 'resa_cta_label',
            'type'          => 'text',
            'default_value' => 'Ne tardez pas',
        ],
        [
            'key'           => 'field_resa_cta_title',
            'label'         => 'Titre H2',
            'name'          => 'resa_cta_title',
            'type'          => 'textarea',
            'rows'          => 2,
            'default_value' => "Les meilleures tables\npartent en premier",
            'instructions'  => 'Saut de ligne = <br> dans le rendu',
        ],
        [
            'key'           => 'field_resa_cta_subtitle',
            'label'         => 'Sous-titre',
            'name'          => 'resa_cta_subtitle',
            'type'          => 'textarea',
            'rows'          => 2,
            'default_value' => "Le week-end est souvent complet dès le mercredi.\nAssurez votre place maintenant.",
        ],
        [
            'key'           => 'field_resa_cta_fine',
            'label'         => 'Note de bas de page',
            'name'          => 'resa_cta_fine',
            'type'          => 'text',
            'default_value' => 'Annulation gratuite jusqu\'à 24h avant · Confirmation par email sous 2h',
        ],
        [
            'key'           => 'field_resa_cta_image',
            'label'         => 'Image de fond',
            'name'          => 'resa_cta_image',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'instructions'  => 'Image en arrière-plan (opacité réduite automatiquement)',
        ],

    ],
] );


/* ══════════════════════════════════════════════════════════════
   PAGE CONTACT
   CO1 : Hero
   CO2 : Formulaire & Photo
   CO3 : Infos pratiques
   CO4 : Bannière Recrutement
══════════════════════════════════════════════════════════════ */

/* ──────────────────────────────────────────────────────────────
   CO1. HERO
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_contact_hero',
    'title'      => '① Contact — Hero',
    'menu_order' => 10,
    'location'   => $contact_page_location,
    'fields'     => [

        [
            'key'           => 'field_contact_hero_label',
            'label'         => 'Label (capslock)',
            'name'          => 'contact_hero_label',
            'type'          => 'text',
            'default_value' => 'Restaurant · Arcachon',
        ],
        [
            'key'           => 'field_contact_hero_title',
            'label'         => 'Titre H1',
            'name'          => 'contact_hero_title',
            'type'          => 'text',
            'default_value' => 'Infos & Contact',
        ],
        [
            'key'           => 'field_contact_hero_tagline_1',
            'label'         => 'Tagline — ligne 1',
            'name'          => 'contact_hero_tagline_1',
            'type'          => 'text',
            'default_value' => 'Nous sommes à votre écoute',
            'instructions'  => 'Laisser vide pour masquer cette ligne.',
        ],
        [
            'key'           => 'field_contact_hero_tagline_2',
            'label'         => 'Tagline — ligne 2',
            'name'          => 'contact_hero_tagline_2',
            'type'          => 'text',
            'default_value' => 'Répondons à toutes vos questions',
            'instructions'  => 'Laisser vide pour masquer cette ligne.',
        ],
        [
            'key'          => 'field_contact_hero_images',
            'label'        => 'Photos du hero (crossfade)',
            'name'         => 'contact_hero_images',
            'type'         => 'repeater',
            'instructions' => 'Ajoutez 2 à 4 photos. La première est prioritaire (LCP).',
            'min'          => 1,
            'max'          => 4,
            'layout'       => 'table',
            'button_label' => 'Ajouter une photo',
            'sub_fields'   => [
                [
                    'key'           => 'field_contact_hero_img',
                    'label'         => 'Photo',
                    'name'          => 'image',
                    'type'          => 'image',
                    'return_format' => 'array',
                    'preview_size'  => 'thumbnail',
                    'column_width'  => 60,
                ],
                [
                    'key'          => 'field_contact_hero_alt',
                    'label'        => 'Description (SEO)',
                    'name'         => 'alt',
                    'type'         => 'text',
                    'column_width' => 40,
                ],
            ],
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   CO2. FORMULAIRE & PHOTO
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_contact_contenu',
    'title'      => '② Contact — Formulaire & Photo',
    'menu_order' => 20,
    'location'   => $contact_page_location,
    'fields'     => [

        [
            'key'           => 'field_contact_form_label',
            'label'         => 'Label section (capslock)',
            'name'          => 'contact_form_label',
            'type'          => 'text',
            'default_value' => 'Nous écrire',
        ],
        [
            'key'           => 'field_contact_form_title',
            'label'         => 'Titre H2',
            'name'          => 'contact_form_title',
            'type'          => 'text',
            'default_value' => 'Vous avez des questions ?',
        ],
        [
            'key'           => 'field_contact_telephone',
            'label'         => 'Numéro de téléphone',
            'name'          => 'contact_telephone',
            'type'          => 'text',
            'default_value' => '05 57 15 73 59',
            'instructions'  => 'Format national : 05 57 15 73 59. Le lien tel: est généré automatiquement.',
        ],
        [
            'key'           => 'field_contact_photo',
            'label'         => 'Photo (colonne droite, au-dessus de la carte)',
            'name'          => 'contact_photo',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'instructions'  => 'Format 4/3 recommandé.',
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   CO3. INFOS PRATIQUES
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_contact_infos',
    'title'      => '③ Contact — Infos pratiques',
    'menu_order' => 30,
    'location'   => $contact_page_location,
    'fields'     => [

        [
            'key'           => 'field_contact_adresse',
            'label'         => 'Adresse',
            'name'          => 'contact_adresse',
            'type'          => 'textarea',
            'rows'          => 3,
            'default_value' => "14 Pl. Lucien de Gracia,\n33120 Arcachon",
            'instructions'  => 'Entrée = saut de ligne dans le rendu.',
        ],
        [
            'key'           => 'field_contact_email',
            'label'         => 'Email',
            'name'          => 'contact_email',
            'type'          => 'email',
            'default_value' => 'contact@lepetitlouvre.fr',
        ],
        [
            'key'           => 'field_contact_horaires_1',
            'label'         => 'Horaires — Ligne 1',
            'name'          => 'contact_horaires_1',
            'type'          => 'text',
            'default_value' => 'Lun – Dim  ·  9h → 23h',
        ],
        [
            'key'           => 'field_contact_horaires_2',
            'label'         => 'Horaires — Ligne 2 (optionnel)',
            'name'          => 'contact_horaires_2',
            'type'          => 'text',
            'default_value' => '',
        ],
        [
            'key'           => 'field_contact_horaires_closed',
            'label'         => 'Horaires — Jour fermé (optionnel)',
            'name'          => 'contact_horaires_closed',
            'type'          => 'text',
            'default_value' => '',
        ],
        [
            'key'           => 'field_contact_maps_embed',
            'label'         => 'URL iframe Google Maps (src)',
            'name'          => 'contact_maps_embed',
            'type'          => 'textarea',
            'rows'          => 3,
            'default_value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2826.5477020065387!2d-1.1697042235937494!3d44.661378989785936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd54942783b3f3fd%3A0x8d48b1df21b277!2s14%20Pl.%20Lucien%20de%20Gracia%2C%2033120%20Arcachon!5e0!3m2!1sfr!2sfr!4v1716139200000!5m2!1sfr!2sfr',
            'instructions'  => 'Collez uniquement la valeur src= de l\'iframe (pas le code HTML complet).',
        ],
        [
            'key'           => 'field_contact_maps_link',
            'label'         => 'Lien itinéraire Google Maps',
            'name'          => 'contact_maps_link',
            'type'          => 'url',
            'default_value' => 'https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon',
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   CO4. BANNIÈRE RECRUTEMENT
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_contact_recrutement',
    'title'      => '④ Contact — Bannière Recrutement',
    'menu_order' => 40,
    'location'   => $contact_page_location,
    'fields'     => [

        [
            'key'           => 'field_contact_recrutement_actif',
            'label'         => 'Afficher la bannière recrutement',
            'name'          => 'contact_recrutement_actif',
            'type'          => 'true_false',
            'default_value' => 1,
            'ui'            => 1,
            'instructions'  => 'Décochez pour masquer complètement cette section.',
        ],
        [
            'key'           => 'field_contact_recrutement_label',
            'label'         => 'Label (capslock)',
            'name'          => 'contact_recrutement_label',
            'type'          => 'text',
            'default_value' => 'Rejoindre l\'équipe',
        ],
        [
            'key'           => 'field_contact_recrutement_titre',
            'label'         => 'Titre H2',
            'name'          => 'contact_recrutement_titre',
            'type'          => 'text',
            'default_value' => 'Nous recrutons pour la saison',
        ],
        [
            'key'           => 'field_contact_recrutement_texte',
            'label'         => 'Texte descriptif',
            'name'          => 'contact_recrutement_texte',
            'type'          => 'textarea',
            'rows'          => 3,
            'default_value' => "Serveur·se, commis de salle, barman·aid — vous aimez l'accueil et le sud-ouest ?\nRejoignez une équipe passionnée dans un restaurant emblématique d'Arcachon.",
            'instructions'  => 'Saut de ligne = <br> dans le rendu.',
        ],
        [
            'key'           => 'field_contact_recrutement_image',
            'label'         => 'Image de fond',
            'name'          => 'contact_recrutement_image',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
        ],
        [
            'key'          => 'field_contact_recrutement_badges',
            'label'        => 'Badges (max 4)',
            'name'         => 'contact_recrutement_badges',
            'type'         => 'repeater',
            'max'          => 4,
            'layout'       => 'table',
            'button_label' => 'Ajouter un badge',
            'sub_fields'   => [
                [
                    'key'   => 'field_contact_badge_texte',
                    'label' => 'Texte du badge',
                    'name'  => 'texte',
                    'type'  => 'text',
                ],
            ],
        ],
        [
            'key'           => 'field_contact_recrutement_email_sujet',
            'label'         => 'Sujet de l\'email de candidature',
            'name'          => 'contact_recrutement_email_sujet',
            'type'          => 'text',
            'default_value' => 'Candidature saisonnière – Le Petit Louvre',
        ],

    ],
] );

/* ══════════════════════════════════════════════════════════════
   CONTACT — ⑤ Moyens de paiement & Services
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_contact_paiements',
    'title'      => '⑤ Contact — Moyens de paiement & Services',
    'menu_order' => 50,
    'location'   => $contact_page_location,
    'fields'     => [

        [
            'key'           => 'field_contact_moyens_paiement',
            'label'         => 'Moyens de paiement acceptés',
            'name'          => 'contact_moyens_paiement',
            'type'          => 'checkbox',
            'instructions'  => 'Cochez les modes de paiement acceptés. Ils s\'affichent sous forme de pastilles sur la page Contact.',
            'choices'       => [
                'amex'         => 'American Express',
                'mastercard'   => 'Mastercard',
                'visa'         => 'Visa',
                'carte_debit'  => 'Carte de débit',
                'ticket_resto' => 'Ticket Restaurant',
                'cash'         => 'Cash',
            ],
            'default_value' => [ 'amex', 'mastercard', 'visa', 'carte_debit', 'ticket_resto', 'cash' ],
            'layout'        => 'horizontal',
            'return_format' => 'value',
        ],

        [
            'key'           => 'field_contact_services',
            'label'         => 'Équipements & services',
            'name'          => 'contact_services',
            'type'          => 'checkbox',
            'instructions'  => 'Cochez les services disponibles dans l\'établissement.',
            'choices'       => [
                'pmr'       => 'Accès PMR',
                'terrasse'  => 'Terrasse',
                'clim'      => 'Climatisation',
                'wifi'      => 'Wifi',
            ],
            'default_value' => [ 'pmr', 'terrasse', 'clim' ],
            'layout'        => 'horizontal',
            'return_format' => 'value',
        ],

    ],
] );


/* ══════════════════════════════════════════════════════════════
   OPTIONS DU THÈME — Header & Footer
   Accessible via WP Admin > Options du thème
   Lecture : get_field( 'opt_xxx', 'option' )
══════════════════════════════════════════════════════════════ */

/* ──────────────────────────────────────────────────────────────
   OPT1. COORDONNÉES GLOBALES
   (header téléphone, footer contact, Schema.org…)
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_opt_coordonnees',
    'title'      => '① Coordonnées — Téléphone · Email · Adresse',
    'menu_order' => 10,
    'location'   => $options_location,
    'fields'     => [

        [
            'key'           => 'field_opt_telephone',
            'label'         => 'Téléphone',
            'name'          => 'opt_telephone',
            'type'          => 'text',
            'default_value' => '05 57 15 73 59',
            'instructions'  => 'Format national : 05 57 15 73 59. Le lien tel: est généré automatiquement.',
        ],
        [
            'key'           => 'field_opt_email',
            'label'         => 'Email',
            'name'          => 'opt_email',
            'type'          => 'email',
            'default_value' => 'contact@lepetitlouvre.fr',
        ],
        [
            'key'           => 'field_opt_adresse',
            'label'         => 'Adresse complète',
            'name'          => 'opt_adresse',
            'type'          => 'textarea',
            'rows'          => 2,
            'default_value' => "14 Pl. Lucien de Gracia,\n33120 Arcachon",
            'instructions'  => 'Entrée = saut de ligne dans le rendu.',
        ],
        [
            'key'           => 'field_opt_maps_link',
            'label'         => 'Lien itinéraire Google Maps',
            'name'          => 'opt_maps_link',
            'type'          => 'url',
            'default_value' => 'https://www.google.com/maps/dir//14+Pl.+Lucien+de+Gracia,+33120+Arcachon',
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   OPT2. RÉSEAUX SOCIAUX
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_opt_reseaux',
    'title'      => '② Réseaux Sociaux',
    'menu_order' => 20,
    'location'   => $options_location,
    'fields'     => [

        [
            'key'          => 'field_opt_facebook',
            'label'        => 'Facebook',
            'name'         => 'opt_social_facebook',
            'type'         => 'url',
            'instructions' => 'URL de la page Facebook. Laisser vide pour masquer l\'icône.',
        ],
        [
            'key'          => 'field_opt_instagram',
            'label'        => 'Instagram',
            'name'         => 'opt_social_instagram',
            'type'         => 'url',
            'instructions' => 'URL du compte Instagram.',
        ],
        [
            'key'          => 'field_opt_tiktok',
            'label'        => 'TikTok',
            'name'         => 'opt_social_tiktok',
            'type'         => 'url',
            'instructions' => 'URL du compte TikTok.',
        ],
        [
            'key'          => 'field_opt_tripadvisor',
            'label'        => 'TripAdvisor',
            'name'         => 'opt_social_tripadvisor',
            'type'         => 'url',
            'instructions' => 'URL de la fiche TripAdvisor.',
        ],

    ],
] );

/* ──────────────────────────────────────────────────────────────
   OPT3. FOOTER — Textes & Bouton flottant
──────────────────────────────────────────────────────────────── */
acf_add_local_field_group( [
    'key'        => 'group_opt_footer',
    'title'      => '③ Footer — Textes & Bouton flottant',
    'menu_order' => 30,
    'location'   => $options_location,
    'fields'     => [

        [
            'key'           => 'field_opt_footer_desc',
            'label'         => 'Description sous le logo',
            'name'          => 'opt_footer_desc',
            'type'          => 'textarea',
            'rows'          => 3,
            'default_value' => 'Institution emblématique d\'Arcachon, Le Petit Louvre se réinvente avec une cuisine française fusion moderne.',
        ],
        [
            'key'           => 'field_opt_footer_horaires',
            'label'         => 'Horaires (bloc texte)',
            'name'          => 'opt_footer_horaires',
            'type'          => 'textarea',
            'rows'          => 5,
            'default_value' => "Le Petit Louvre vous accueille\n\nDu lundi au dimanche\nde 9h à 23h",
            'instructions'  => 'Saut de ligne simple = <br>, ligne vide = <br><br>.',
        ],
        [
            'key'           => 'field_opt_footer_copyright',
            'label'         => 'Texte copyright',
            'name'          => 'opt_footer_copyright',
            'type'          => 'text',
            'default_value' => 'Le Petit Louvre restaurant',
            'instructions'  => 'L\'année et "— Tous droits réservés" sont ajoutés automatiquement.',
        ],
        [
            'key'           => 'field_opt_float_label',
            'label'         => 'Bouton flottant — Texte',
            'name'          => 'opt_float_label',
            'type'          => 'text',
            'default_value' => 'Réserver une table',
            'instructions'  => 'Texte du bouton vert flottant (bas de page).',
        ],

    ],
] );


/* ══════════════════════════════════════════════════════════════
   HELPER — génère les sous-champs d'un repeater "menu item"
   Paramètres :
     $prefix  : préfixe unique pour les clés ACF (ex: "bois_aper")
     $double  : true = prix_2 (2ème colonne), false = prix seul
     $triple  : true = prix_2 + prix_3 (3 colonnes vins)
══════════════════════════════════════════════════════════════ */
function lpl_item_subfields( $prefix, $double = false, $triple = false ) {
    $fields = [
        [ 'key' => "field_{$prefix}_nom",  'label' => 'Nom',         'name' => 'nom',         'type' => 'text',     'required' => 1 ],
        [ 'key' => "field_{$prefix}_desc", 'label' => 'Description', 'name' => 'description', 'type' => 'text',     'instructions' => 'Laisser vide si aucune description.' ],
        [ 'key' => "field_{$prefix}_prix", 'label' => $triple ? 'Prix 14cl' : ( $double ? 'Prix 25cl' : 'Prix' ), 'name' => 'prix', 'type' => 'text', 'required' => 1, 'instructions' => 'Ex : 12&thinsp;€ ou 12 €' ],
    ];
    if ( $double || $triple ) {
        $fields[] = [ 'key' => "field_{$prefix}_prix2", 'label' => $triple ? 'Prix 28cl' : 'Prix 50cl', 'name' => 'prix_2', 'type' => 'text', 'instructions' => 'Laisser vide si non applicable.' ];
    }
    if ( $triple ) {
        $fields[] = [ 'key' => "field_{$prefix}_prix3", 'label' => 'Prix 75cl', 'name' => 'prix_3', 'type' => 'text', 'instructions' => 'Laisser vide si non applicable.' ];
    }
    $fields[] = [ 'key' => "field_{$prefix}_badge", 'label' => 'Badge (optionnel)', 'name' => 'badge', 'type' => 'text', 'instructions' => 'Ex : Bio, Coup de cœur, Signature, Sans alcool' ];
    return $fields;
}

/* ──────────────────────────────────────────────────────────────
   Localisations
──────────────────────────────────────────────────────────────── */
$boissons_location  = [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-carte-des-boissons.php'  ] ] ];
$cocktails_location = [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-carte-des-cocktails.php' ] ] ];
$vins_location      = [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-carte-des-vins.php'      ] ] ];
$alcools_location   = [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-carte-des-alcools.php'   ] ] ];


/* ══════════════════════════════════════════════════════════════
   CARTE DES BOISSONS — ① Hero
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_boissons_hero',
    'title'      => '① Hero — Carte des Boissons',
    'menu_order' => 10,
    'location'   => $boissons_location,
    'fields'     => [
        [ 'key' => 'field_boissons_hero_label',   'label' => 'Label',   'name' => 'boissons_hero_label',   'type' => 'text', 'default_value' => 'Bar · Arcachon' ],
        [ 'key' => 'field_boissons_hero_title',   'label' => 'Titre H1','name' => 'boissons_hero_title',   'type' => 'text', 'default_value' => 'Carte des Boissons' ],
        [ 'key' => 'field_boissons_hero_tagline', 'label' => 'Tagline', 'name' => 'boissons_hero_tagline', 'type' => 'text', 'default_value' => 'Apéritifs, bières, smoothies & cafeterie' ],
        [
            'key'          => 'field_boissons_hero_images',
            'label'        => 'Photos Hero (slideshow)',
            'name'         => 'boissons_hero_images',
            'type'         => 'repeater',
            'min'          => 1,
            'layout'       => 'table',
            'button_label' => '+ Ajouter une photo',
            'sub_fields'   => [
                [ 'key' => 'field_boissons_hero_img',     'label' => 'Photo',  'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'required' => 1 ],
                [ 'key' => 'field_boissons_hero_img_alt', 'label' => 'Texte alt', 'name' => 'alt','type' => 'text', 'instructions' => 'Description de la photo pour l\'accessibilité.' ],
            ],
        ],
    ],
] );

/* ══════════════════════════════════════════════════════════════
   CARTE DES BOISSONS — ② Menu
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_boissons_menu',
    'title'      => '② Menu — Boissons',
    'menu_order' => 20,
    'location'   => $boissons_location,
    'fields'     => [

        /* Apéritifs */
        [ 'key' => 'field_boissons_titre_aper',  'label' => 'Titre — Apéritifs',       'name' => 'boissons_titre_aperitifs',       'type' => 'text', 'default_value' => 'Apéritifs' ],
        [ 'key' => 'field_boissons_aper_rep', 'label' => 'Items — Apéritifs (6cl)', 'name' => 'aperitifs',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Apéritif',
          'sub_fields' => lpl_item_subfields('bois_aper') ],

        /* Bières pression */
        [ 'key' => 'field_boissons_titre_bpres', 'label' => 'Titre — Bières Pression',   'name' => 'boissons_titre_bieres_pression',  'type' => 'text', 'default_value' => 'Bières Pression' ],
        [ 'key' => 'field_boissons_bpres_rep', 'label' => 'Items — Bières Pression (25cl / 50cl)', 'name' => 'bieres_pression',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Bière pression',
          'sub_fields' => lpl_item_subfields('bois_bpres', true) ],

        /* Bières bouteille */
        [ 'key' => 'field_boissons_titre_bbout', 'label' => 'Titre — Bières Bouteille',  'name' => 'boissons_titre_bieres_bouteille', 'type' => 'text', 'default_value' => 'Bières Bouteille' ],
        [ 'key' => 'field_boissons_bbout_rep', 'label' => 'Items — Bières Bouteille (33cl)', 'name' => 'bieres_bouteille',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Bière bouteille',
          'sub_fields' => lpl_item_subfields('bois_bbout') ],

        /* Jus de fruit */
        [ 'key' => 'field_boissons_titre_jus',   'label' => 'Titre — Jus de Fruit',      'name' => 'boissons_titre_jus_fruit',        'type' => 'text', 'default_value' => 'Jus de Fruit' ],
        [ 'key' => 'field_boissons_jus_rep', 'label' => 'Items — Jus de Fruit (25cl)', 'name' => 'jus_fruit',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Jus de fruit',
          'sub_fields' => lpl_item_subfields('bois_jus') ],

        /* Smoothies */
        [ 'key' => 'field_boissons_titre_smoo',  'label' => 'Titre — Smoothies',         'name' => 'boissons_titre_smoothies',        'type' => 'text', 'default_value' => 'Smoothies' ],
        [ 'key' => 'field_boissons_smoo_rep', 'label' => 'Items — Smoothies (40cl)', 'name' => 'smoothies',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Smoothie',
          'sub_fields' => lpl_item_subfields('bois_smoo') ],

        /* Sodas */
        [ 'key' => 'field_boissons_titre_soda',  'label' => 'Titre — Sodas',             'name' => 'boissons_titre_sodas',            'type' => 'text', 'default_value' => 'Sodas' ],
        [ 'key' => 'field_boissons_soda_rep', 'label' => 'Items — Sodas (33cl)', 'name' => 'sodas',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Soda',
          'sub_fields' => lpl_item_subfields('bois_soda') ],

        /* Pressé */
        [ 'key' => 'field_boissons_titre_presse', 'label' => 'Titre — Pressé',           'name' => 'boissons_titre_presse',           'type' => 'text', 'default_value' => 'Pressé' ],
        [ 'key' => 'field_boissons_presse_rep', 'label' => 'Items — Pressé (20cl)', 'name' => 'presse',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Pressé',
          'sub_fields' => lpl_item_subfields('bois_presse') ],

        /* Eaux */
        [ 'key' => 'field_boissons_titre_eaux',  'label' => 'Titre — Eaux',              'name' => 'boissons_titre_eaux',             'type' => 'text', 'default_value' => 'Eaux' ],
        [ 'key' => 'field_boissons_eaux_rep', 'label' => 'Items — Eaux', 'name' => 'eaux',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Eau',
          'sub_fields' => lpl_item_subfields('bois_eaux') ],

        /* Cafeterie */
        [ 'key' => 'field_boissons_titre_cafe',  'label' => 'Titre — Cafeterie',         'name' => 'boissons_titre_cafeterie',        'type' => 'text', 'default_value' => 'Cafeterie' ],
        [ 'key' => 'field_boissons_cafe_rep', 'label' => 'Items — Cafeterie', 'name' => 'cafeterie',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Boisson chaude',
          'sub_fields' => lpl_item_subfields('bois_cafe') ],

        /* Note bas de page */
        [ 'key' => 'field_boissons_footnote', 'label' => 'Note bas de page', 'name' => 'boissons_footnote',
          'type' => 'text', 'default_value' => 'Prix net en € – service compris – chèque non accepté – CB minimum 5€' ],
    ],
] );


/* ══════════════════════════════════════════════════════════════
   CARTE DES COCKTAILS — ① Hero
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_cocktails_hero',
    'title'      => '① Hero — Carte des Cocktails',
    'menu_order' => 10,
    'location'   => $cocktails_location,
    'fields'     => [
        [ 'key' => 'field_cocktails_hero_label',   'label' => 'Label',   'name' => 'cocktails_hero_label',   'type' => 'text', 'default_value' => 'Bar · Arcachon' ],
        [ 'key' => 'field_cocktails_hero_title',   'label' => 'Titre H1','name' => 'cocktails_hero_title',   'type' => 'text', 'default_value' => 'Carte des Cocktails' ],
        [ 'key' => 'field_cocktails_hero_tagline', 'label' => 'Tagline', 'name' => 'cocktails_hero_tagline', 'type' => 'text', 'default_value' => 'Des créations uniques à partager' ],
        [
            'key'          => 'field_cocktails_hero_images',
            'label'        => 'Photos Hero (slideshow)',
            'name'         => 'cocktails_hero_images',
            'type'         => 'repeater',
            'min'          => 1,
            'layout'       => 'table',
            'button_label' => '+ Ajouter une photo',
            'sub_fields'   => [
                [ 'key' => 'field_cocktails_hero_img',     'label' => 'Photo',      'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'required' => 1 ],
                [ 'key' => 'field_cocktails_hero_img_alt', 'label' => 'Texte alt',  'name' => 'alt',   'type' => 'text' ],
            ],
        ],
    ],
] );

/* ══════════════════════════════════════════════════════════════
   CARTE DES COCKTAILS — ② Menu
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_cocktails_menu',
    'title'      => '② Menu — Cocktails',
    'menu_order' => 20,
    'location'   => $cocktails_location,
    'fields'     => [

        /* Martini Cocktails */
        [ 'key' => 'field_ckt_titre_martini',    'label' => 'Titre — Martinis',            'name' => 'cocktails_titre_martinis',   'type' => 'text', 'default_value' => 'Martini Cocktails' ],
        [ 'key' => 'field_ckt_martini_rep', 'label' => 'Items — Martini Cocktails (20cl)', 'name' => 'martini_cocktails',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Martini',
          'sub_fields' => lpl_item_subfields('ckt_martini') ],

        /* Cocktails Signature */
        [ 'key' => 'field_ckt_titre_signature',  'label' => 'Titre — Signature',           'name' => 'cocktails_titre_signature',  'type' => 'text', 'default_value' => 'Cocktails Signature' ],
        [ 'key' => 'field_ckt_signature_rep', 'label' => 'Items — Cocktails Signature (30cl)', 'name' => 'cocktails_signature',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Signature',
          'sub_fields' => lpl_item_subfields('ckt_sign') ],

        /* Classiques */
        [ 'key' => 'field_ckt_titre_classiques', 'label' => 'Titre — Classiques',          'name' => 'cocktails_titre_classiques', 'type' => 'text', 'default_value' => 'Classiques Cocktails' ],
        [ 'key' => 'field_ckt_classiques_rep', 'label' => 'Items — Classiques Cocktails (30cl)', 'name' => 'cocktails_classiques',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Classique',
          'sub_fields' => lpl_item_subfields('ckt_clas') ],

        /* Spritz */
        [ 'key' => 'field_ckt_titre_spritz',     'label' => 'Titre — Spritz',              'name' => 'cocktails_titre_spritz',     'type' => 'text', 'default_value' => 'Spritz' ],
        [ 'key' => 'field_ckt_spritz_rep', 'label' => 'Items — Spritz (40cl)', 'name' => 'spritz',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Spritz',
          'sub_fields' => lpl_item_subfields('ckt_spritz') ],

        /* Sans Alcool */
        [ 'key' => 'field_ckt_titre_sansalcool', 'label' => 'Titre — Sans Alcool',         'name' => 'cocktails_titre_sans_alcool','type' => 'text', 'default_value' => 'Sans Alcool' ],
        [ 'key' => 'field_ckt_sansalcool_rep', 'label' => 'Items — Sans Alcool (30cl)', 'name' => 'sans_alcool',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Sans alcool',
          'sub_fields' => lpl_item_subfields('ckt_na') ],

        /* Note bas de page */
        [ 'key' => 'field_cocktails_footnote', 'label' => 'Note bas de page', 'name' => 'cocktails_footnote',
          'type' => 'text', 'default_value' => 'Prix net en € – service compris – chèque non accepté – CB minimum 5€' ],
    ],
] );


/* ══════════════════════════════════════════════════════════════
   CARTE DES VINS — ① Hero
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_vins_hero',
    'title'      => '① Hero — Carte des Vins',
    'menu_order' => 10,
    'location'   => $vins_location,
    'fields'     => [
        [ 'key' => 'field_vins_hero_label',   'label' => 'Label',   'name' => 'vins_hero_label',   'type' => 'text', 'default_value' => 'Sélection · Arcachon' ],
        [ 'key' => 'field_vins_hero_title',   'label' => 'Titre H1','name' => 'vins_hero_title',   'type' => 'text', 'default_value' => 'Carte des Vins' ],
        [ 'key' => 'field_vins_hero_tagline', 'label' => 'Tagline', 'name' => 'vins_hero_tagline', 'type' => 'text', 'default_value' => 'Une cave soigneusement sélectionnée · Des accords pensés pour chaque plat' ],
        [
            'key'          => 'field_vins_hero_images',
            'label'        => 'Photos Hero (slideshow)',
            'name'         => 'vins_hero_images',
            'type'         => 'repeater',
            'min'          => 1,
            'layout'       => 'table',
            'button_label' => '+ Ajouter une photo',
            'sub_fields'   => [
                [ 'key' => 'field_vins_hero_img',     'label' => 'Photo',     'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'required' => 1 ],
                [ 'key' => 'field_vins_hero_img_alt', 'label' => 'Texte alt', 'name' => 'alt',   'type' => 'text' ],
            ],
        ],
    ],
] );

/* ══════════════════════════════════════════════════════════════
   CARTE DES VINS — ② Menu
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_vins_menu',
    'title'      => '② Menu — Vins',
    'menu_order' => 20,
    'location'   => $vins_location,
    'fields'     => [

        /* Blancs */
        [ 'key' => 'field_vins_titre_blancs', 'label' => 'Titre — Vins Blancs',          'name' => 'vins_titre_blancs',      'type' => 'text', 'default_value' => 'Blanc' ],
        [ 'key' => 'field_vins_blancs_rep', 'label' => 'Items — Vins Blancs (14cl / 28cl / 75cl)', 'name' => 'vins_blancs',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Vin blanc',
          'sub_fields' => lpl_item_subfields('vins_bla', false, true) ],

        /* Rouges */
        [ 'key' => 'field_vins_titre_rouges', 'label' => 'Titre — Vins Rouges',          'name' => 'vins_titre_rouges',      'type' => 'text', 'default_value' => 'Rouge' ],
        [ 'key' => 'field_vins_rouges_rep', 'label' => 'Items — Vins Rouges (14cl / 28cl / 75cl)', 'name' => 'vins_rouges',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Vin rouge',
          'sub_fields' => lpl_item_subfields('vins_rou', false, true) ],

        /* Rosés */
        [ 'key' => 'field_vins_titre_roses', 'label' => 'Titre — Vins Rosés',            'name' => 'vins_titre_roses',       'type' => 'text', 'default_value' => 'Rosé' ],
        [ 'key' => 'field_vins_roses_rep', 'label' => 'Items — Vins Rosés (14cl / 28cl / 75cl)', 'name' => 'vins_roses',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Vin rosé',
          'sub_fields' => lpl_item_subfields('vins_ros', false, true) ],

        /* Champagnes */
        [ 'key' => 'field_vins_titre_champ', 'label' => 'Titre — Champagnes',            'name' => 'vins_titre_champagnes',  'type' => 'text', 'default_value' => 'Champagne' ],
        [ 'key' => 'field_vins_champ_rep', 'label' => 'Items — Champagnes & Pétillants (14cl / 28cl / 75cl)', 'name' => 'vins_champagnes',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Champagne',
          'sub_fields' => lpl_item_subfields('vins_cha', false, true) ],

        /* Cocktails & Apéritifs vins */
        [ 'key' => 'field_vins_ckt_rep', 'label' => 'Cocktails & Apéritifs (prix unique)', 'name' => 'vins_cocktails',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Cocktail / Apéritif',
          'sub_fields' => lpl_item_subfields('vins_ckt') ],

        /* Note bas de page */
        [ 'key' => 'field_vins_footnote', 'label' => 'Note bas de page', 'name' => 'vins_footnote',
          'type' => 'text', 'default_value' => 'Prix net en € – service compris – chèque non accepté – CB minimum 5€' ],
    ],
] );


/* ══════════════════════════════════════════════════════════════
   CARTE DES ALCOOLS — ① Hero
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_alcools_hero',
    'title'      => '① Hero — Carte des Alcools',
    'menu_order' => 10,
    'location'   => $alcools_location,
    'fields'     => [
        [ 'key' => 'field_alcools_hero_label',   'label' => 'Label',   'name' => 'alcools_hero_label',   'type' => 'text', 'default_value' => 'Bar · Arcachon' ],
        [ 'key' => 'field_alcools_hero_title',   'label' => 'Titre H1','name' => 'alcools_hero_title',   'type' => 'text', 'default_value' => 'Carte des Alcools' ],
        [ 'key' => 'field_alcools_hero_tagline', 'label' => 'Tagline', 'name' => 'alcools_hero_tagline', 'type' => 'text', 'default_value' => 'Vodkas, whiskies, gins, rhums & spiritueux d\'exception' ],
        [
            'key'          => 'field_alcools_hero_images',
            'label'        => 'Photos Hero (slideshow)',
            'name'         => 'alcools_hero_images',
            'type'         => 'repeater',
            'min'          => 1,
            'layout'       => 'table',
            'button_label' => '+ Ajouter une photo',
            'sub_fields'   => [
                [ 'key' => 'field_alcools_hero_img',     'label' => 'Photo',     'name' => 'image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail', 'required' => 1 ],
                [ 'key' => 'field_alcools_hero_img_alt', 'label' => 'Texte alt', 'name' => 'alt',   'type' => 'text',  'instructions' => 'Description de la photo pour l\'accessibilité.' ],
            ],
        ],
    ],
] );

/* ══════════════════════════════════════════════════════════════
   CARTE DES ALCOOLS — ② Menu
══════════════════════════════════════════════════════════════ */
acf_add_local_field_group( [
    'key'        => 'group_alcools_menu',
    'title'      => '② Menu — Alcools & Spiritueux',
    'menu_order' => 20,
    'location'   => $alcools_location,
    'fields'     => [

        /* Vodka */
        [ 'key' => 'field_alcools_titre_vodka',    'label' => 'Titre — Vodka',            'name' => 'alcools_titre_vodka',    'type' => 'text', 'default_value' => 'Vodka' ],
        [ 'key' => 'field_alcools_vodka_rep', 'label' => 'Items — Vodka (4cl)', 'name' => 'alcools_vodka',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Vodka',
          'sub_fields' => lpl_item_subfields('alc_vod') ],

        /* Rhum */
        [ 'key' => 'field_alcools_titre_rhum',     'label' => 'Titre — Rhum',             'name' => 'alcools_titre_rhum',     'type' => 'text', 'default_value' => 'Rhum' ],
        [ 'key' => 'field_alcools_rhum_rep', 'label' => 'Items — Rhum (4cl)', 'name' => 'alcools_rhum',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Rhum',
          'sub_fields' => lpl_item_subfields('alc_rhum') ],

        /* Whisky */
        [ 'key' => 'field_alcools_titre_whisky',   'label' => 'Titre — Whisky',           'name' => 'alcools_titre_whisky',   'type' => 'text', 'default_value' => 'Whisky' ],
        [ 'key' => 'field_alcools_whisky_rep', 'label' => 'Items — Whisky (4cl)', 'name' => 'alcools_whisky',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Whisky',
          'sub_fields' => lpl_item_subfields('alc_whi') ],

        /* Gin */
        [ 'key' => 'field_alcools_titre_gin',      'label' => 'Titre — Gin',              'name' => 'alcools_titre_gin',      'type' => 'text', 'default_value' => 'Gin' ],
        [ 'key' => 'field_alcools_gin_rep', 'label' => 'Items — Gin (4cl)', 'name' => 'alcools_gin',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Gin',
          'sub_fields' => lpl_item_subfields('alc_gin') ],

        /* Digestifs */
        [ 'key' => 'field_alcools_titre_digestifs', 'label' => 'Titre — Digestifs',       'name' => 'alcools_titre_digestifs', 'type' => 'text', 'default_value' => 'Digestifs' ],
        [ 'key' => 'field_alcools_digestifs_rep', 'label' => 'Items — Digestifs (4cl)', 'name' => 'alcools_digestifs',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Digestif',
          'sub_fields' => lpl_item_subfields('alc_dig') ],

        /* Tequila */
        [ 'key' => 'field_alcools_titre_tequila',  'label' => 'Titre — Tequila',          'name' => 'alcools_titre_tequila',  'type' => 'text', 'default_value' => 'Tequila' ],
        [ 'key' => 'field_alcools_tequila_rep', 'label' => 'Items — Tequila (4cl)', 'name' => 'alcools_tequila',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Tequila',
          'sub_fields' => lpl_item_subfields('alc_teq') ],

        /* Cognac & Armagnac */
        [ 'key' => 'field_alcools_titre_cognac',   'label' => 'Titre — Cognac & Armagnac','name' => 'alcools_titre_cognac',   'type' => 'text', 'default_value' => 'Cognac & Armagnac' ],
        [ 'key' => 'field_alcools_cognac_rep', 'label' => 'Items — Cognac & Armagnac (4cl)', 'name' => 'alcools_cognac',
          'type' => 'repeater', 'layout' => 'table', 'button_label' => '+ Cognac / Armagnac',
          'sub_fields' => lpl_item_subfields('alc_cog') ],

    ],
] );


/* ══════════════════════════════════════════════════════════════
   MIGRATION — Pré-remplissage des cartes bar (one-shot)
   Se déclenche une seule fois à la première connexion admin.
══════════════════════════════════════════════════════════════ */
add_action( 'admin_init', function () {

    if ( ! function_exists( 'update_field' ) )          return;
    if ( get_option( 'lpl_bar_menus_initialized' ) )    return;
    if ( ! current_user_can( 'manage_options' ) )       return;

    /* ── Helper : trouve l'ID d'une page par son template ── */
    $find_page = function ( $template ) {
        $pages = get_pages( [ 'meta_key' => '_wp_page_template', 'meta_value' => $template, 'number' => 1 ] );
        return ! empty( $pages ) ? $pages[0]->ID : 0;
    };

    $pid_bois  = $find_page( 'page-carte-des-boissons.php' );
    $pid_ckt   = $find_page( 'page-carte-des-cocktails.php' );
    $pid_vins  = $find_page( 'page-carte-des-vins.php' );
    $pid_alc   = $find_page( 'page-carte-des-alcools.php' );

    /* ════════════════════════════════════
       BOISSONS
    ════════════════════════════════════ */
    if ( $pid_bois ) {

        update_field( 'aperitifs', [
            [ 'nom' => 'Pastis 51',           'description' => '2 cl',                        'prix' => '4',    'badge' => '' ],
            [ 'nom' => 'Lillet blanc / rosé', 'description' => '',                             'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Campari',             'description' => '',                             'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Martini blanc / rouge','description' => '',                            'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Suze',                'description' => '',                             'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Kir vin blanc',       'description' => 'Cassis, mûre, framboise, fraise', 'prix' => '6','badge' => '' ],
            [ 'nom' => 'Kir royal',           'description' => '',                             'prix' => '11',   'badge' => '' ],
        ], $pid_bois );

        update_field( 'bieres_pression', [
            [ 'nom' => 'Meteor',     'description' => 'Pils',            'prix' => '4,5', 'prix_2' => '8', 'badge' => '' ],
            [ 'nom' => 'Wendelinus', 'description' => 'Bière d\'abbaye', 'prix' => '5',   'prix_2' => '9', 'badge' => '' ],
            [ 'nom' => 'Meteor',     'description' => 'Bière blanche',   'prix' => '5',   'prix_2' => '9', 'badge' => '' ],
            [ 'nom' => 'Meteor',     'description' => 'IPA',             'prix' => '5',   'prix_2' => '9', 'badge' => '' ],
        ], $pid_bois );

        update_field( 'bieres_bouteille', [
            [ 'nom' => 'Corona',          'description' => '', 'prix' => '7', 'badge' => '' ],
            [ 'nom' => 'San Miguel Blonde','description' => '', 'prix' => '7', 'badge' => '' ],
            [ 'nom' => 'Pelforth Brune',  'description' => '', 'prix' => '7', 'badge' => '' ],
            [ 'nom' => '1664 sans alcool','description' => '', 'prix' => '6', 'badge' => '' ],
        ], $pid_bois );

        update_field( 'jus_fruit', [
            [ 'nom' => 'Jus d\'orange', 'description' => '', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Jus de tomate', 'description' => '', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Jus de pomme',  'description' => '', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Jus d\'ananas', 'description' => '', 'prix' => '4,5', 'badge' => '' ],
        ], $pid_bois );

        update_field( 'smoothies', [
            [ 'nom' => 'Vitamina', 'description' => 'Kiwi, framboise, mangue',  'prix' => '8', 'badge' => '' ],
            [ 'nom' => 'Énergie',  'description' => 'Banane, kiwi, ananas',     'prix' => '8', 'badge' => '' ],
            [ 'nom' => 'Exotique', 'description' => 'Mangue, orange, banane',   'prix' => '8', 'badge' => '' ],
        ], $pid_bois );

        update_field( 'sodas', [
            [ 'nom' => 'Coca-Cola, Coca-Cola zéro', 'description' => '',      'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Orangina',                  'description' => '25 cl', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Schweppes tonic',            'description' => '25 cl', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Sprite',                    'description' => '25 cl', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Ginger beer',               'description' => '25 cl', 'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Thé glacé maison',           'description' => '',      'prix' => '5,5', 'badge' => '' ],
            [ 'nom' => 'Citronnade maison',          'description' => '',      'prix' => '5,5', 'badge' => '' ],
        ], $pid_bois );

        update_field( 'presse', [
            [ 'nom' => 'Citron', 'description' => '', 'prix' => '5,5', 'badge' => '' ],
            [ 'nom' => 'Orange', 'description' => '', 'prix' => '5,5', 'badge' => '' ],
        ], $pid_bois );

        update_field( 'eaux', [
            [ 'nom' => 'Vittel',        'description' => '100 cl', 'prix' => '7',   'badge' => '' ],
            [ 'nom' => 'San Pellegrino','description' => '100 cl', 'prix' => '7',   'badge' => '' ],
            [ 'nom' => 'Perrier',       'description' => '33 cl',  'prix' => '4,5', 'badge' => '' ],
            [ 'nom' => 'Vittel',        'description' => '25 cl',  'prix' => '4',   'badge' => '' ],
        ], $pid_bois );

        update_field( 'cafeterie', [
            [ 'nom' => 'Expresso & Déca',         'description' => '',               'prix' => '2',    'badge' => '' ],
            [ 'nom' => 'Double expresso',          'description' => '',               'prix' => '4',    'badge' => '' ],
            [ 'nom' => 'Café crème',               'description' => '',               'prix' => '4,5',  'badge' => '' ],
            [ 'nom' => 'Capuccino',                'description' => '',               'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Café viennois',            'description' => '',               'prix' => '5,5',  'badge' => '' ],
            [ 'nom' => 'Chocolat chaud',           'description' => '',               'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Chocolat viennois',        'description' => '',               'prix' => '5,5',  'badge' => '' ],
            [ 'nom' => 'Hot Chocolate Marshmallow','description' => '',               'prix' => '6',    'badge' => '' ],
            [ 'nom' => 'Thé & Infusion',           'description' => 'Mariage Frères', 'prix' => '5',    'badge' => '' ],
            [ 'nom' => 'Irish Coffee',             'description' => '',               'prix' => '10',   'badge' => '' ],
        ], $pid_bois );
    }

    /* ════════════════════════════════════
       COCKTAILS
    ════════════════════════════════════ */
    if ( $pid_ckt ) {

        update_field( 'martini_cocktails', [
            [ 'nom' => 'Cucumber Martini',       'description' => 'Vodka, concombre',                      'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Pine and Berries Martini','description' => 'Vodka, framboise, ananas, Chambord',   'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Pornstar Martini',        'description' => 'Vodka, passion, vanille, champagne',   'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Tira Martini',            'description' => 'Amaretto, expresso, Kalhua',           'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Kiwi Martini',            'description' => 'Gin, kiwi',                            'prix' => '12', 'badge' => '' ],
        ], $pid_ckt );

        update_field( 'cocktails_signature', [
            [ 'nom' => 'Red Lover',   'description' => 'Gin, ananas, framboise, citron',                    'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Apple Pie',   'description' => 'Rhum, pomme, cannelle, citron',                     'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Petit Louis', 'description' => 'Whisky, pain d\'épices, vanille, Kalhua',           'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Amber Drop',  'description' => 'Vodka, orange, passion, cannelle, citron',          'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Exotic Tiki', 'description' => 'Rhum, mangue, passion, coco',                       'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Black Jack',  'description' => 'Whisky J.Daniel, framboise, Chambord, cranberry',   'prix' => '13', 'badge' => '' ],
        ], $pid_ckt );

        update_field( 'cocktails_classiques', [
            [ 'nom' => 'Gin Basil Smash',   'description' => 'Gin, feuilles de basilic, citron',  'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Moscow Mule',       'description' => 'Vodka, ginger, citron vert',         'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Daiquiri',          'description' => 'Rhum, citron vert',                  'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Mojito',            'description' => 'Rhum, menthe, citron vert, soda',    'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Margarita',         'description' => 'Tequila, triple sec, citron',        'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Pina Colada',       'description' => 'Rhum, ananas, coco',                 'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Bloody Mary',       'description' => 'Vodka, tomates, épices',             'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Caïpirinha',        'description' => 'Cachaça, citron vert',               'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Kiwi Collins',      'description' => 'Vodka, kiwi, soda, citron',         'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Pineapple Collins', 'description' => 'Vodka, ananas, soda, citron',        'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Raspberry Collins', 'description' => 'Vodka, framboise, soda, citron',     'prix' => '12', 'badge' => '' ],
        ], $pid_ckt );

        update_field( 'spritz', [
            [ 'nom' => 'Apérol',   'description' => 'Liqueur Apérol, prosecco, soda',              'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Campari',  'description' => 'Liqueur Campari, prosecco, soda',              'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Italicus', 'description' => 'Liqueur bergamote, prosecco, soda',            'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Hugo',     'description' => 'Liqueur St Germain, menthe, prosecco, soda',  'prix' => '13', 'badge' => '' ],
        ], $pid_ckt );

        update_field( 'sans_alcool', [
            [ 'nom' => 'Petit Louvre',  'description' => 'Ananas, pomme, orgeat, citron',    'prix' => '8', 'badge' => '' ],
            [ 'nom' => 'Saint Anne',    'description' => 'Orange, pomme, cranberry, pêche',  'prix' => '8', 'badge' => '' ],
            [ 'nom' => 'Virgin Mojito', 'description' => 'Menthe, citron, soda',             'prix' => '8', 'badge' => '' ],
            [ 'nom' => 'Virgin Colada', 'description' => 'Ananas, coco',                     'prix' => '8', 'badge' => '' ],
        ], $pid_ckt );
    }

    /* ════════════════════════════════════
       VINS
    ════════════════════════════════════ */
    if ( $pid_vins ) {

        update_field( 'vins_blancs', [
            [ 'nom' => 'Provence Rollier',  'description' => 'Château de la Martinette « bio » 2023',                     'prix' => '5',  'prix_2' => '10', 'prix_3' => '25', 'badge' => '' ],
            [ 'nom' => 'Côtes de Gascogne', 'description' => 'Domaine de Magnaut « moelleux » 2024',                      'prix' => '6',  'prix_2' => '12', 'prix_3' => '27', 'badge' => '' ],
            [ 'nom' => 'Bordeaux Graves',   'description' => 'Château tour de Castres 2023',                              'prix' => '7',  'prix_2' => '14', 'prix_3' => '34', 'badge' => '' ],
            [ 'nom' => 'Val de Loire',      'description' => 'Pouilly-fumé. La villaudière de Reverdy 2024',              'prix' => '9',  'prix_2' => '18', 'prix_3' => '43', 'badge' => '' ],
            [ 'nom' => 'Provence',          'description' => 'Clos blanc. Château de la Martinette « bio » 2023',         'prix' => '',   'prix_2' => '',   'prix_3' => '43', 'badge' => '' ],
            [ 'nom' => 'Rhône',             'description' => 'Château de Valcombe 2024',                                   'prix' => '',   'prix_2' => '',   'prix_3' => '31', 'badge' => '' ],
            [ 'nom' => 'Bordeaux',          'description' => 'Blaye. Château Bertinerie 2024',                             'prix' => '',   'prix_2' => '',   'prix_3' => '31', 'badge' => '' ],
            [ 'nom' => 'Bourgogne',         'description' => 'Chablis. Dampt frères tradition 2022',                       'prix' => '',   'prix_2' => '',   'prix_3' => '43', 'badge' => '' ],
            [ 'nom' => 'Bourgogne',         'description' => 'Santenay. Justin Girardin, Les Terrasses de Bievaux 2023',  'prix' => '',   'prix_2' => '',   'prix_3' => '55', 'badge' => '' ],
        ], $pid_vins );

        update_field( 'vins_rouges', [
            [ 'nom' => 'Rhône',        'description' => 'Côtes du Rhône. Domaine de l\'Obrieu les frangines « bio » 2022', 'prix' => '6',  'prix_2' => '12', 'prix_3' => '27', 'badge' => '' ],
            [ 'nom' => 'Val de Loire', 'description' => 'Bourgueil. Clos de l\'Abbaye 2021',                               'prix' => '7',  'prix_2' => '14', 'prix_3' => '29', 'badge' => '' ],
            [ 'nom' => 'Vin du Monde', 'description' => 'Argentine. Festivo Malbec 2023',                                  'prix' => '8',  'prix_2' => '16', 'prix_3' => '32', 'badge' => '' ],
            [ 'nom' => 'Bordeaux',     'description' => 'Pessac Leognan. Domaine de la Roche 2019',                        'prix' => '9',  'prix_2' => '18', 'prix_3' => '39', 'badge' => '' ],
            [ 'nom' => 'Bourgogne',    'description' => 'Côte de nuits. Dupasquier les Vignottes 2022',                    'prix' => '',   'prix_2' => '',   'prix_3' => '49', 'badge' => '' ],
            [ 'nom' => 'Bourgogne',    'description' => 'Chassagne-Montrachet. Louis Latour 2021',                         'prix' => '',   'prix_2' => '',   'prix_3' => '72', 'badge' => '' ],
            [ 'nom' => 'Languedoc',    'description' => 'Pic saint loup. Mas de l\'oncle 2023',                            'prix' => '',   'prix_2' => '',   'prix_3' => '39', 'badge' => '' ],
            [ 'nom' => 'Rhône',        'description' => 'Vacqueyras. Domaine de l\'Obrieu 2020',                           'prix' => '',   'prix_2' => '',   'prix_3' => '39', 'badge' => '' ],
            [ 'nom' => 'Rhône',        'description' => 'Châteauneuf-du-Pape. Château La Nerthe 2020',                     'prix' => '',   'prix_2' => '',   'prix_3' => '75', 'badge' => '' ],
            [ 'nom' => 'Bordeaux',     'description' => 'Saint-Émilion. Château Pipeau grand cru 2021',                    'prix' => '',   'prix_2' => '',   'prix_3' => '55', 'badge' => '' ],
            [ 'nom' => 'Bordeaux',     'description' => 'Margaux. Blason d\'Issan 2020',                                   'prix' => '',   'prix_2' => '',   'prix_3' => '59', 'badge' => '' ],
            [ 'nom' => 'Bordeaux',     'description' => 'Pauillac. Fleur de Pédesclaux 2016',                              'prix' => '',   'prix_2' => '',   'prix_3' => '62', 'badge' => '' ],
        ], $pid_vins );

        update_field( 'vins_roses', [
            [ 'nom' => 'Provence Rollier', 'description' => 'Château de la Martinette « bio » 2024', 'prix' => '6', 'prix_2' => '12', 'prix_3' => '25', 'badge' => '' ],
            [ 'nom' => 'Provence',         'description' => 'Minuty Prestige 2024',                  'prix' => '8', 'prix_2' => '16', 'prix_3' => '39', 'badge' => '' ],
        ], $pid_vins );

        update_field( 'vins_champagnes', [
            [ 'nom' => 'Colin Alliance Brut',          'description' => '', 'prix' => '10', 'prix_2' => '20', 'prix_3' => '65', 'badge' => '' ],
            [ 'nom' => 'Colin Castille Blanc de Blanc', 'description' => '', 'prix' => '12', 'prix_2' => '24', 'prix_3' => '80', 'badge' => '' ],
            [ 'nom' => 'Deutz Brut',                   'description' => '', 'prix' => '',   'prix_2' => '',   'prix_3' => '95', 'badge' => '' ],
        ], $pid_vins );

        update_field( 'vins_footnote',
            '* Les millésimes sont susceptibles de changer en fonction des arrivages — Prix net en € – service compris – chèque non accepté – CB minimum 5€',
            $pid_vins
        );
    }

    /* ════════════════════════════════════
       ALCOOLS
    ════════════════════════════════════ */
    if ( $pid_alc ) {

        update_field( 'alcools_vodka', [
            [ 'nom' => 'Smirnoff',       'description' => '', 'prix' => '7',  'badge' => '' ],
            [ 'nom' => 'Stolichnaya',    'description' => '', 'prix' => '9',  'badge' => '' ],
            [ 'nom' => 'Ketel One',      'description' => '', 'prix' => '10', 'badge' => '' ],
            [ 'nom' => 'Pyla française', 'description' => '', 'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Grey Goose',     'description' => '', 'prix' => '13', 'badge' => '' ],
        ], $pid_alc );

        update_field( 'alcools_rhum', [
            [ 'nom' => 'Pampero Ambré',  'description' => '', 'prix' => '7',  'badge' => '' ],
            [ 'nom' => 'Trois Rivières', 'description' => '', 'prix' => '9',  'badge' => '' ],
            [ 'nom' => 'Botran',         'description' => '', 'prix' => '11', 'badge' => '' ],
            [ 'nom' => 'Diplomatico',    'description' => '', 'prix' => '12', 'badge' => '' ],
            [ 'nom' => 'Zacapa 23 ans',  'description' => '', 'prix' => '18', 'badge' => '' ],
        ], $pid_alc );

        update_field( 'alcools_whisky', [
            [ 'nom' => 'Johnny Walker Red', 'description' => '', 'prix' => '7',  'badge' => '' ],
            [ 'nom' => "Jack Daniel's",     'description' => '', 'prix' => '10', 'badge' => '' ],
            [ 'nom' => 'Bulleit Bourbon',   'description' => '', 'prix' => '11', 'badge' => '' ],
            [ 'nom' => 'Talisker 10 ans',   'description' => '', 'prix' => '14', 'badge' => '' ],
            [ 'nom' => 'Chivas 18 ans',     'description' => '', 'prix' => '16', 'badge' => '' ],
        ], $pid_alc );

        update_field( 'alcools_gin', [
            [ 'nom' => 'Bombay Original', 'description' => '', 'prix' => '7',  'badge' => '' ],
            [ 'nom' => 'Tanqueray',       'description' => '', 'prix' => '8',  'badge' => '' ],
            [ 'nom' => "Hendrick's",      'description' => '', 'prix' => '10', 'badge' => '' ],
            [ 'nom' => 'Gin Mare',        'description' => '', 'prix' => '12', 'badge' => '' ],
        ], $pid_alc );

        update_field( 'alcools_digestifs', [
            [ 'nom' => 'Limoncello',     'description' => '', 'prix' => '6', 'badge' => '' ],
            [ 'nom' => 'Get 27',         'description' => '', 'prix' => '7', 'badge' => '' ],
            [ 'nom' => 'Baileys',        'description' => '', 'prix' => '7', 'badge' => '' ],
            [ 'nom' => 'Amaretto',       'description' => '', 'prix' => '7', 'badge' => '' ],
            [ 'nom' => 'Poire Williams', 'description' => '', 'prix' => '8', 'badge' => '' ],
        ], $pid_alc );

        update_field( 'alcools_tequila', [
            [ 'nom' => 'Patron Silver',   'description' => '', 'prix' => '11', 'badge' => '' ],
            [ 'nom' => 'Patron Reposado', 'description' => '', 'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Patron Anejo',    'description' => '', 'prix' => '16', 'badge' => '' ],
        ], $pid_alc );

        update_field( 'alcools_cognac', [
            [ 'nom' => 'Armagnac',        'description' => '', 'prix' => '10', 'badge' => '' ],
            [ 'nom' => 'Cognac ABK6 VS',  'description' => '', 'prix' => '13', 'badge' => '' ],
            [ 'nom' => 'Cognac ABK6 XO',  'description' => '', 'prix' => '18', 'badge' => '' ],
        ], $pid_alc );
    }

    update_option( 'lpl_bar_menus_initialized', true );

}, 20 );
