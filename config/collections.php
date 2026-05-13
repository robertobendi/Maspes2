<?php

/**
 * Maspes Piante e Fiori — content shape.
 *
 * The brief removes `posts` (no editorial blog) and adds:
 *   - composizioni (the arrangement gallery — drives /composizioni and home three-up)
 *   - testimonianze (preserved customer reviews — three on home)
 *   - su_misura (bespoke inquiry form, posted to Formspree on the static export
 *     so we don't depend on Pebblestack's server in the GitHub Pages mirror)
 */

return [

    'pages' => [
        'label'          => 'Pages',
        'label_singular' => 'Page',
        'icon'           => 'file',
        'route'          => '/{slug}',
        'template'       => 'page.twig',
        'order_by'       => 'updated_at DESC',
        'fields' => [
            'title'            => ['type' => 'text',     'required' => true, 'label' => 'Title'],
            'slug'             => ['type' => 'slug',     'required' => true, 'label' => 'Slug', 'help' => 'URL path, lowercase letters, numbers, dashes.'],
            'body'             => ['type' => 'markdown', 'label' => 'Body', 'help' => 'Markdown supported.'],
            'meta_description' => ['type' => 'textarea', 'label' => 'Meta description', 'help' => 'Used in <meta name="description">. ~160 chars.'],
        ],
    ],

    'composizioni' => [
        'label'          => 'Composizioni',
        'label_singular' => 'Composizione',
        'icon'           => 'image',
        'route'          => '/composizioni/{slug}',
        'template'       => 'composizione.twig',
        'list_template'  => 'composizioni-list.twig',
        'order_by'       => 'created_at ASC',
        'list_limit'     => 100,
        'fields' => [
            'title'         => ['type' => 'text',     'required' => true, 'label' => 'Nome',          'help' => 'es. "100 Rose Rosse"'],
            'slug'          => ['type' => 'slug',     'required' => true, 'label' => 'Slug'],
            'categoria'     => ['type' => 'select',   'required' => true, 'label' => 'Categoria',     'options' => ['rose', 'piante', 'bouquet', 'orchidee', 'composizione']],
            'prezzo_da'     => ['type' => 'number',   'required' => true, 'label' => 'Prezzo (€)',    'help' => 'Numero intero in euro.'],
            'prezzo_fisso'  => ['type' => 'boolean',                       'label' => 'Prezzo fisso', 'help' => 'Se attivo, mostrato senza "da".'],
            'immagine_url'  => ['type' => 'url',      'required' => true, 'label' => 'Immagine (URL)'],
            'immagine_alt'  => ['type' => 'text',     'required' => true, 'label' => 'Testo alternativo'],
            'descrizione'   => ['type' => 'textarea', 'required' => true, 'label' => 'Descrizione'],
            'in_evidenza'   => ['type' => 'boolean',                       'label' => 'In evidenza',  'help' => 'Compare nella sezione "Le nostre composizioni" della home.'],
            'ordine'        => ['type' => 'number',                        'label' => 'Ordine'],
        ],
    ],

    'testimonianze' => [
        'label'          => 'Testimonianze',
        'label_singular' => 'Testimonianza',
        'icon'           => 'message-square',
        'fields' => [
            'nome'        => ['type' => 'text',     'required' => true, 'label' => 'Nome cliente'],
            'citazione'   => ['type' => 'textarea', 'required' => true, 'label' => 'Citazione'],
            'mostra_home' => ['type' => 'boolean',                       'label' => 'Mostra in home'],
            'ordine'      => ['type' => 'number',                        'label' => 'Ordine'],
        ],
    ],

    'su_misura' => [
        'label'          => 'Richieste su misura',
        'label_singular' => 'Richiesta',
        'is_form'        => true,
        'fields' => [
            'nome'       => ['type' => 'text',     'required' => true, 'label' => 'Nome'],
            'email'      => ['type' => 'text',     'required' => true, 'label' => 'Email'],
            'telefono'   => ['type' => 'text',                          'label' => 'Telefono'],
            'occasione'  => ['type' => 'select',   'required' => true, 'label' => 'Occasione', 'options' => ['Matrimonio', 'Evento', 'Hotel / Ristorante', 'Regalo importante', 'Altro']],
            'data'       => ['type' => 'datetime',                      'label' => 'Data'],
            'budget'     => ['type' => 'select',                        'label' => 'Budget',    'options' => ['Fino a €200', '€200–€500', '€500–€1.000', 'Oltre €1.000', 'Da definire']],
            'messaggio'  => ['type' => 'textarea', 'required' => true, 'label' => 'Messaggio'],
        ],
    ],

    'contact' => [
        'label'          => 'Contact',
        'label_singular' => 'Submission',
        'is_form'        => true,
        'fields' => [
            'name'    => ['type' => 'text',     'required' => true, 'label' => 'Name'],
            'email'   => ['type' => 'text',     'required' => true, 'label' => 'Email'],
            'message' => ['type' => 'textarea', 'required' => true, 'label' => 'Message'],
        ],
    ],

];
