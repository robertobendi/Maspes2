<?php

/**
 * scripts/seed-content.php — seed the Maspes site with content.
 *
 * Called by export-static.sh after the install POST, so the static export
 * captures /su-misura, /visitaci, and the /composizioni list with content.
 *
 * Idempotent: uses INSERT OR REPLACE on entries.(collection, slug), so
 * re-running just refreshes data.
 */

declare(strict_types=1);

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

use Pebblestack\Core\Database;

$db = new Database($root . '/data/pebblestack.sqlite');
$now = time();

function upsert(Database $db, string $collection, string $slug, array $data, int $now, ?int $publishAt = null): void
{
    $payload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $db->run(
        'INSERT INTO entries (collection, slug, status, data, publish_at, created_at, updated_at)
         VALUES (:c, :s, :st, :d, :pa, :ca, :ua)
         ON CONFLICT(collection, slug) DO UPDATE SET
             status     = excluded.status,
             data       = excluded.data,
             publish_at = excluded.publish_at,
             updated_at = excluded.updated_at',
        [
            'c'  => $collection,
            's'  => $slug,
            'st' => 'published',
            'd'  => $payload,
            'pa' => $publishAt ?? $now,
            'ca' => $now,
            'ua' => $now,
        ]
    );
}

/* ----------------------------- PAGES ------------------------------ */

upsert($db, 'pages', 'su-misura', [
    'title' => 'Composizioni su misura',
    'slug'  => 'su-misura',
    'body'  => '',
    'meta_description' => "Matrimoni, eventi, hotel e regali importanti. Raccontaci l'occasione: ascoltiamo, disegniamo, consegniamo a Como e provincia.",
], $now);

upsert($db, 'pages', 'visitaci', [
    'title' => 'Visitaci',
    'slug'  => 'visitaci',
    'body'  => '',
    'meta_description' => 'Via Leone Leoni 2, 22100 Como. Orari, indicazioni, telefono e contatti di Maspes Piante e Fiori.',
], $now);

/* -------------------------- COMPOSIZIONI -------------------------- */

$composizioni = [
    [
        'slug'         => '100-rose-rosse',
        'title'        => '100 Rose Rosse',
        'categoria'    => 'rose',
        'prezzo_da'    => 700,
        'prezzo_fisso' => true,
        'immagine_url' => '/uploads/hero/wet_rose_bouquet__unsplash__jpg.jpg',
        'immagine_alt' => 'Bouquet imponente di rose rosse a stelo lungo su sfondo scuro, dettaglio macro.',
        'descrizione'  => 'La nostra composizione signature. Cento rose a stelo lungo, selezionate la mattina stessa, legate a mano in negozio. Su prenotazione, consegna garantita a Como.',
        'in_evidenza'  => true,
        'ordine'       => 1,
    ],
    [
        'slug'         => '24-rose-rosse',
        'title'        => '24 Rose Rosse',
        'categoria'    => 'rose',
        'prezzo_da'    => 168,
        'prezzo_fisso' => true,
        'immagine_url' => '/uploads/hero/crimson_bouquet__50703702043__jpg.jpg',
        'immagine_alt' => 'Rosa rossa aperta in primo piano, dettaglio macro su sfondo scuro.',
        'descrizione'  => 'Due dozzine di rose rosse: il gesto classico, ma fatto bene. Selezione di stelo, confezione bassa o alta a scelta, consegna in giornata.',
        'ordine'       => 2,
    ],
    [
        'slug'         => '12-rose-rosse',
        'title'        => '12 Rose Rosse',
        'categoria'    => 'rose',
        'prezzo_da'    => 84,
        'prezzo_fisso' => true,
        'immagine_url' => '/uploads/composizioni/close_up_of_red_rose_in_brooklyn_botanic_garden_jpg.jpg',
        'immagine_alt' => 'Roseto di rose rosse aperte, fotografate in luce naturale.',
        'descrizione'  => 'La dozzina che non sbaglia mai. Dodici rose rosse a stelo lungo, confezione in carta naturale o ribbon Maspes.',
        'ordine'       => 3,
    ],
    [
        'slug'         => '9-rose-rosse',
        'title'        => '9 Rose Rosse',
        'categoria'    => 'rose',
        'prezzo_da'    => 63,
        'prezzo_fisso' => true,
        'immagine_url' => '/uploads/composizioni/red_rose_flower_close_up_jpg.jpg',
        'immagine_alt' => 'Rosa scura aperta su sfondo nero, dettaglio macro con gocce di rugiada.',
        'descrizione'  => 'Nove rose. Una proporzione gentile, più discreta della dozzina, ugualmente carica. Da regalare senza occasione.',
        'ordine'       => 4,
    ],
    [
        'slug'         => '6-rose-rosse',
        'title'        => '6 Rose Rosse',
        'categoria'    => 'rose',
        'prezzo_da'    => 42,
        'prezzo_fisso' => true,
        'immagine_url' => '/uploads/composizioni/a_close_up_of_climbing_roses_jpg.jpg',
        'immagine_alt' => 'Rose rosse rampicanti in piena fioritura, primo piano.',
        'descrizione'  => 'Sei rose. Per un grazie, per un mi sei mancato, per arrivare a casa portando qualcosa.',
        'ordine'       => 5,
    ],
    [
        'slug'         => 'orchidea-phalaenopsis',
        'title'        => 'Orchidea Phalaenopsis',
        'categoria'    => 'orchidee',
        'prezzo_da'    => 38,
        'immagine_url' => '/uploads/composizioni/phalaenopsis_white_cultivar_2_jpg.jpg',
        'immagine_alt' => 'Orchidea bianca Phalaenopsis con felci sullo sfondo.',
        'descrizione'  => "Orchidea Phalaenopsis bianca o screziata, una o due aste. Pianta da interno di lunga durata, perfetta per un regalo di rappresentanza.",
        'ordine'       => 6,
    ],
    [
        'slug'         => 'pianta-da-interno',
        'title'        => 'Orchidea in coppo',
        'categoria'    => 'piante',
        'prezzo_da'    => 55,
        'immagine_url' => '/uploads/composizioni/orchid_2021_001_phalaenopsis_amabilis_blume_jpg.jpg',
        'immagine_alt' => "Orchidea bianca in vaso di ceramica brunita, illuminata da luce calda laterale.",
        'descrizione'  => "Una piccola orchidea presentata nel suo vaso di ceramica, scelta una a una. Per chi vuole un regalo che resti — non solo l'istante.",
        'in_evidenza'  => true,
        'ordine'       => 7,
    ],
    [
        'slug'         => 'bouquet-vivace',
        'title'        => 'Bouquet Vivace',
        'categoria'    => 'bouquet',
        'prezzo_da'    => 30,
        'immagine_url' => '/uploads/composizioni/mixed_flower_power_jpg.jpg',
        'immagine_alt' => "Bouquet vivace con rose arancio, gerbera e felci, in vaso di vetro.",
        'descrizione'  => "Bouquet di stagione, composto in negozio con i fiori del giorno. Colori e proporzioni li decidiamo insieme.",
        'in_evidenza'  => true,
        'ordine'       => 8,
    ],
    [
        'slug'         => 'bouquet-autore',
        'title'        => "Bouquet d'autore",
        'categoria'    => 'bouquet',
        'prezzo_da'    => 120,
        'immagine_url' => '/uploads/composizioni/anna_maria_janssens_or_jan_brueghel_the_younger__workshop___.jpg',
        'immagine_alt' => "Bouquet in stile fiammingo, peonie, lilium e tulipani, su fondo scuro.",
        'descrizione'  => "Bouquet di grande formato, costruito attorno a una varietà di stagione: peonie, lisianthus, lilium. Per cene, presentazioni, lobby di hotel.",
        'ordine'       => 9,
    ],
];

foreach ($composizioni as $c) {
    $slug = $c['slug'];
    unset($c['slug']);
    upsert($db, 'composizioni', $slug, $c, $now);
}

/* --------------------------- TESTIMONIANZE ------------------------ */

$testi = [
    [
        'slug' => 'jacopo-zuppati',
        'nome' => 'Jacopo Zuppati',
        'citazione' => 'Il fiorista top di Como. Se volete la qualità, sia per richieste particolari che per una semplice rosa andate da Corrado: non vi deluderà.',
        'mostra_home' => true,
        'ordine' => 1,
    ],
    [
        'slug' => 'barbara-bertolini',
        'nome' => 'Barbara Bertolini',
        'citazione' => 'Fiori di rara bellezza e qualità. Personale gentilissimo.',
        'mostra_home' => true,
        'ordine' => 2,
    ],
    [
        'slug' => 'chiara-vincenzi',
        'nome' => 'Chiara Vincenzi',
        'citazione' => 'Grande, grandissima professionalità e composizioni bellissime e raffinate.',
        'mostra_home' => true,
        'ordine' => 3,
    ],
];

foreach ($testi as $t) {
    $slug = $t['slug'];
    unset($t['slug']);
    upsert($db, 'testimonianze', $slug, $t, $now);
}

echo "seeded: " . count($composizioni) . " composizioni, " . count($testi) . " testimonianze, 2 pages\n";
