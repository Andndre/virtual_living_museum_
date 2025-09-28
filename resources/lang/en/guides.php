<?php

return [
    'app_guide' => 'App Guide',
    'learn_how_to_use' => 'Learn how to use this application',

    'visit_heritage' => [
        'id' => 'visit_heritage',
        'title' => 'Visit Heritage',
        'description' => 'Guide to using the Visit Heritage feature',
        'icon' => 'fa-monument',
        'steps' => [
            [
                'title' => 'Open Visit Heritage Menu',
                'description' => 'Select Visit Heritage menu from the main application page'
            ],
            [
                'title' => 'Choose Material',
                'description' => 'Choose the heritage material you want to learn about'
            ],
            [
                'title' => 'Follow Each Step',
                'description' => 'Follow each step: take pretest, read e-book, visit virtual living museum, and take posttest'
            ],
        ]
    ],

    'virtual_living_museum' => [
        'id' => 'virtual_living_museum',
        'title' => 'Virtual Living Museum',
        'description' => 'Guide to using AR Virtual Living Museum feature in Visit Heritage menu',
        'icon' => 'fa-vr-cardboard',
        'steps' => [
            [
                'title' => 'Visit One of the Virtual Living Museums',
                'description' => 'Select Virtual Living Museum menu from Visit Heritage page'
            ],
            [
                'title' => 'Choose Available AR Spot',
                'description' => 'Choose one of the available AR spots on Virtual Living Museum page'
            ],
            [
                'title' => 'Allow Camera Access',
                'description' => 'Allow the application to access your device camera'
            ],
            [
                'title' => 'Point Camera',
                'description' => 'Point camera to open area until white circle appears'
            ],
            [
                'title' => 'View AR Content',
                'description' => 'Tap screen to place AR object. You can walk around to see AR object from different angles'
            ],
            [
                'title' => 'View Heritage Information',
                'description' => 'Use info button to see list of heritage information on AR object'
            ]
        ]
    ],

    'heritage_video' => [
        'id' => 'heritage_video',
        'title' => 'Heritage Video',
        'description' => 'Guide to watching cultural heritage videos',
        'icon' => 'fa-video',
        'steps' => [
            [
                'title' => 'Open Heritage Video Menu',
                'description' => 'Select Heritage Video menu from main page'
            ],
            [
                'title' => 'Choose Video',
                'description' => 'Select heritage video you want to watch from available list'
            ],
            [
                'title' => 'Watch Video',
                'description' => 'Video will play automatically. Use video controls to adjust volume and quality'
            ]
        ]
    ],

    'ebook' => [
        'id' => 'ebook',
        'title' => 'E-Book',
        'description' => 'Guide to reading cultural heritage e-books',
        'icon' => 'fa-book',
        'steps' => [
            [
                'title' => 'Access E-Book',
                'description' => 'E-book is available in learning process in Visit Heritage menu'
            ],
            [
                'title' => 'Read Material',
                'description' => 'Read e-book material carefully to understand cultural heritage'
            ],
            [
                'title' => 'Page Navigation',
                'description' => 'Use navigation buttons to move between e-book pages'
            ]
        ]
    ],

    'pretest_posttest' => [
        'id' => 'pretest_posttest',
        'title' => 'Pretest & Posttest',
        'description' => 'Guide to taking pretest and posttest',
        'icon' => 'fa-clipboard-check',
        'steps' => [
            [
                'title' => 'Take Pretest',
                'description' => 'Start by taking pretest to measure your initial knowledge'
            ],
            [
                'title' => 'Study Material',
                'description' => 'After pretest, study e-book material and visit virtual living museum'
            ],
            [
                'title' => 'Take Posttest',
                'description' => 'Complete learning process by taking posttest'
            ],
            [
                'title' => 'View Results',
                'description' => 'See your score improvement from pretest to posttest'
            ]
        ]
    ],

    'laporan_peninggalan' => [
        'id' => 'laporan_peninggalan',
        'title' => 'Heritage Report',
        'description' => 'Guide to reporting cultural heritage you discover',
        'icon' => 'fa-flag',
        'steps' => [
            [
                'title' => 'Open Heritage Report Menu',
                'description' => 'Select Heritage Report menu from the main application page'
            ],
            [
                'title' => 'Add New Report',
                'description' => 'Click "Add Report" button to create a new heritage report'
            ],
            [
                'title' => 'Fill Heritage Data',
                'description' => 'Enter heritage name, address, and description of the discovered heritage'
            ],
            [
                'title' => 'Set Location',
                'description' => 'Mark the heritage location on the map by clicking the correct position'
            ],
            [
                'title' => 'Upload Photos',
                'description' => 'Upload photos of the heritage to complete your report'
            ],
            [
                'title' => 'Submit Report',
                'description' => 'Click "Submit Report" to send your heritage report'
            ],
            [
                'title' => 'Interact with Reports',
                'description' => 'Like and comment on heritage reports from other users'
            ]
        ]
    ],

    'ar_marker' => [
        'id' => 'ar_marker',
        'title' => 'AR Marker',
        'description' => 'Guide to using AR Marker for augmented reality experience',
        'icon' => 'fa-qrcode',
        'steps' => [
            [
                'title' => 'Download AR Catalog',
                'description' => 'Download the catalog file containing AR markers from the app menu'
            ],
            [
                'title' => 'Print Marker',
                'description' => 'Print the AR marker from the downloaded catalog. Ensure good print quality and clarity'
            ],
            [
                'title' => 'Open AR Marker Menu',
                'description' => 'Select AR Marker menu from the main application page'
            ],
            [
                'title' => 'Allow Camera Access',
                'description' => 'Grant permission for the app to access your device camera'
            ],
            [
                'title' => 'Point Camera at Marker',
                'description' => 'Point your device camera at the printed marker. Make sure the marker is clearly visible and not cut off'
            ],
            [
                'title' => 'View AR Object',
                'description' => '3D object will appear on top of the marker. Move your device to view the object from different angles'
            ],
            [
                'title' => 'Interact with Object',
                'description' => 'Tap the AR object to see detailed information or perform other interactions'
            ]
        ]
    ]
];
