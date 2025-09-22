<?php
require_once __DIR__ . '/../config/database.php'; // $pdo connection

$templates = [
    [
        'name' => 'Hero Classic',
        'type' => 'hero',
        'category' => 'basic',
        'preview_image' => null,
        'content' => json_encode([
            'type' => 'hero',
            'content' => [
                'heading' => 'Welcome to PikaAi',
                'subheading' => 'Your AI website builder'
            ]
        ]),
        'is_premium' => 0
    ],
    [
        'name' => 'Features Simple',
        'type' => 'features',
        'category' => 'basic',
        'preview_image' => null,
        'content' => json_encode([
            'type' => 'features',
            'content' => [
                ['title' => 'Fast', 'desc' => 'Build websites quickly with AI templates.'],
                ['title' => 'Customizable', 'desc' => 'Edit every section to fit your brand.'],
                ['title' => 'Responsive', 'desc' => 'Works on all devices seamlessly.']
            ]
        ]),
        'is_premium' => 0
    ],
    [
        'name' => 'CTA Button',
        'type' => 'cta',
        'category' => 'basic',
        'preview_image' => null,
        'content' => json_encode([
            'type' => 'cta',
            'content' => ['text' => 'Get Started', 'link' => '#']
        ]),
        'is_premium' => 0
    ],
    [
        'name' => 'Hero Minimalist',
        'type' => 'hero',
        'category' => 'basic',
        'preview_image' => null,
        'content' => json_encode([
            'type' => 'hero',
            'content' => [
                'heading' => 'Minimalist Design',
                'subheading' => 'Clean, modern, and professional.'
            ]
        ]),
        'is_premium' => 0
    ],
    [
        'name' => 'Features Modern',
        'type' => 'features',
        'category' => 'basic',
        'preview_image' => null,
        'content' => json_encode([
            'type' => 'features',
            'content' => [
                ['title' => 'AI-Driven', 'desc' => 'Generate content automatically with AI.'],
                ['title' => 'Drag & Drop', 'desc' => 'Intuitive block-based editor.'],
                ['title' => 'Customizable', 'desc' => 'Change text, images, and layout easily.']
            ]
        ]),
        'is_premium' => 0
    ]
];

// Insert into DB
foreach ($templates as $tpl) {
    $stmt = $pdo->prepare("
        INSERT INTO templates 
        (name, type, category, preview_image, content, is_premium, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute([
        $tpl['name'],
        $tpl['type'],
        $tpl['category'],
        $tpl['preview_image'],
        $tpl['content'],
        $tpl['is_premium']
    ]);
}

echo "Templates seeded successfully!";
