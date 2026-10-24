<?php

return [
    'app_guide' => 'App Guide',
    'learn_how_to_use' => 'Learn how to use all the features of the Ganesha AR application',

    'progressive_learning' => [
        'id' => 'progressive_learning',
        'title' => 'E-Learning Progressive Path',
        'description' => 'Guide to completing the 4 sequential learning stages',
        'icon' => 'fa-graduation-cap',
        'steps' => [
            [
                'title' => 'Open Visit Heritage Menu',
                'description' => 'Select the Visit Heritage (E-Learning) menu on the home page, then choose the historical era or material you want to study.',
            ],
            [
                'title' => 'Stage 1: Complete the Pre-Test',
                'description' => 'Take the preliminary quiz to assess your initial knowledge. Completing the pre-test unlocks the subsequent learning modules.',
            ],
            [
                'title' => 'Stage 2: Study the Interactive E-Book',
                'description' => 'Read and study the digital e-book module completely to unlock access to the Virtual Living Museum.',
            ],
            [
                'title' => 'Stage 3: Explore the Virtual Living Museum',
                'description' => 'Immerse yourself in a 360° virtual reconstruction of the archaeological site and examine the historical artifacts within it.',
            ],
            [
                'title' => 'Stage 4: Complete the Post-Test',
                'description' => 'Finish the final evaluation quiz to measure your learning progress, record your score to the report card, and unlock the next era.',
            ],
        ],
    ],

    'virtual_living_museum' => [
        'id' => 'virtual_living_museum',
        'title' => 'Virtual Living Museum (WebXR 360°)',
        'description' => 'Guide to placing and exploring site environments in physical space',
        'icon' => 'fa-vr-cardboard',
        'steps' => [
            [
                'title' => 'Open AR on Mobile Device',
                'description' => 'Access the site on a WebXR-compatible Android or iOS device. If browsing from a desktop/laptop, scan the provided QR Token to continue on your mobile phone.',
            ],
            [
                'title' => 'Allow Camera & Scan Floor Surface',
                'description' => 'Grant camera permissions, then slowly pan your phone across a flat, well-lit floor surface.',
            ],
            [
                'title' => 'Lock Reticle & Place the Site',
                'description' => 'Target the white reticle circle onto the desired floor position, then tap the screen once to permanently anchor the 360° site.',
            ],
            [
                'title' => 'Walk Around & Inspect Artifacts',
                'description' => 'Physically walk around inside the virtual site. Tap the "Lihat Objek Peninggalan" button to view the catalog and details of artifacts embedded in the site.',
            ],
        ],
    ],

    'ar_marker' => [
        'id' => 'ar_marker',
        'title' => 'Scan QR Marker & 3D AR',
        'description' => 'Guide to scanning catalog QR codes and placing 3D artifacts in real space',
        'icon' => 'fa-qrcode',
        'steps' => [
            [
                'title' => 'Open AR Marker Menu',
                'description' => 'Select the AR Marker menu from the home page. You can download the PDF catalog if you do not have it yet.',
            ],
            [
                'title' => 'Scan QR Code Marker',
                'description' => 'Aim the scanner camera at the QR Code marker. QR codes can be scanned directly from computer screens, other phone screens, or printed copies.',
            ],
            [
                'title' => 'Explore 3D Model & Audio Narration',
                'description' => 'The interactive 3D artifact loads instantly. Rotate the model 360°, listen to audio narrations, read detailed descriptions, or switch artifacts via the thumbnail bar.',
            ],
            [
                'title' => 'Place in Real Space (AR)',
                'description' => 'Tap "Tampilkan di Ruang Nyata (AR)". The model launches via Google Scene Viewer (Android) or Apple Quick Look (iOS) to stably place it on your real-world desk or floor.',
            ],
        ],
    ],

    'maps_heritage' => [
        'id' => 'maps_heritage',
        'title' => 'Heritage Site Map (Maps)',
        'description' => 'Guide to exploring the interactive heritage map and navigation routes',
        'icon' => 'fa-map-marked-alt',
        'steps' => [
            [
                'title' => 'Open Maps Menu',
                'description' => 'Select the Maps menu from the home screen to view the geographic distribution of all cultural heritage sites.',
            ],
            [
                'title' => 'Search & Filter by Historical Era',
                'description' => 'Use the search box or historical era filters to easily locate specific archaeological sites.',
            ],
            [
                'title' => 'View Details & Directions',
                'description' => 'Tap any site marker pin on the map to inspect photos, read brief descriptions, or open driving/walking directions to the physical location.',
            ],
        ],
    ],

    'laporan_peninggalan' => [
        'id' => 'laporan_peninggalan',
        'title' => 'Cultural Heritage Report',
        'description' => 'Guide to reporting newly discovered historical objects or heritage sites',
        'icon' => 'fa-flag',
        'steps' => [
            [
                'title' => 'Open Report Menu',
                'description' => 'Select the Report Heritage menu from the home screen and click the "Tambah Laporan" button.',
            ],
            [
                'title' => 'Complete Discovery Details',
                'description' => 'Enter the name of the artifact/site, physical address, and detailed description of the condition found.',
            ],
            [
                'title' => 'Mark Coordinates on the Map',
                'description' => 'Use the interactive map to precisely pin the GPS coordinates of the discovery location.',
            ],
            [
                'title' => 'Upload Photos & Submit',
                'description' => 'Attach clear documentation photos and submit your report. Your report can be viewed, liked, and commented on by other users.',
            ],
        ],
    ],

    'heritage_video' => [
        'id' => 'heritage_video',
        'title' => 'Heritage Videos',
        'description' => 'Guide to watching audio-visual cultural heritage documentaries',
        'icon' => 'fa-video',
        'steps' => [
            [
                'title' => 'Open Heritage Video Menu',
                'description' => 'Select the Heritage Video menu from the home page.',
            ],
            [
                'title' => 'Select a Documentary',
                'description' => 'Choose a historical documentary video from the curated list.',
            ],
            [
                'title' => 'Watch & Learn',
                'description' => 'Watch engaging video content providing context and history to deepen your learning comprehension.',
            ],
        ],
    ],

    'statistik_rapor' => [
        'id' => 'statistik_rapor',
        'title' => 'Statistics & Leaderboard',
        'description' => 'Guide to monitoring evaluation scores and leaderboard standing',
        'icon' => 'fa-chart-line',
        'steps' => [
            [
                'title' => 'Open Statistics Menu',
                'description' => 'Select the Statistics menu from the home screen.',
            ],
            [
                'title' => 'Monitor Scores & Learning Progress',
                'description' => 'Review post-test score summaries across completed materials and track your current learning level progression.',
            ],
            [
                'title' => 'View Leaderboard Ranking',
                'description' => 'Compare your average evaluation score with other learners on the global/class leaderboard.',
            ],
        ],
    ],
];
