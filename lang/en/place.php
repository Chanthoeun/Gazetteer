<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Place Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during Place for various
    | messages that we need to display to the Place. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'single' => 'Place',
    'plural' => 'Places',

    'parent' => 'Parent',
    'khmer' => 'Name (Khmer)',
    'latin' => 'Name (English)',
    'code' => 'Code',
    'postal_code' => 'Postal Code',
    'reference' => 'Reference',
    'official_note' => 'Official Note',
    'note' => 'Note',
    'issued_date' => 'Issued Date',
    'geo_location' => 'Geo Location',
    'geo_boundary' => 'Geo Boundary',
    'province' => 'Province / Capital',
    'district' => 'District / Municipality / Khan',
    'commune' => 'Commune / Sangkat',
    'village' => 'Village',


    'action' => [
        'import' => 'Import',
        'importing' => 'Importing',
        'exporting' => 'Exporting',
        'export' => 'Export',

        'description' => [
            'import' => 'Would you like to process the import',
            'export' => 'Would you like to process the export'
        ],

        'notification' => [
            'label' => [
                'in_progress' => ':label in progress!',
                'imported' => ':label imported!',
                'failed' => ':label failed!',
            ],

            'msg' => [
                'in_progress' => 'We are :name :count records. we will notify you when it was done.',
                'importing' => 'We are importing the :name. we will notify you when it was done.',
                'imported' => ':name was imported successfully with :count records!',
                'failed' => ':name was :action failed, please try again!'
            ]
        ]
    ],
];
