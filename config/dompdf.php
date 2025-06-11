<?php

return [
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf

    'public_path' => null,  // Override the public path if needed

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => [
        // Font directories configuration
        'font_dir' => storage_path('fonts'),
        'font_cache' => storage_path('fonts'),

        'temp_dir' => sys_get_temp_dir(),
        'chroot' => realpath(base_path()),

        // Make sure remote images are enabled
        'allowed_protocols' => [
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        'artifactPathValidation' => null,
        'log_output_file' => storage_path('logs/dompdf.log'),  // Enable logging for debugging

        'enable_font_subsetting' => false,
        'pdf_backend' => 'CPDF',
        'default_media_type' => 'screen',
        'default_paper_size' => 'a4',
        'default_paper_orientation' => 'portrait',
        'default_font' => 'serif',

        // Important for image resolution
        'dpi' => 150,  // Higher DPI for better image quality

        'enable_php' => false,
        'enable_javascript' => true,

        // Very important for remote resources
        'enable_remote' => true,

        // Allow localhost and the app's domain
        'allowed_remote_hosts' => ['localhost', 'itseeystore.my.id', '*'],

        'font_height_ratio' => 1.1,
        'enable_html5_parser' => true,
    ],
];
