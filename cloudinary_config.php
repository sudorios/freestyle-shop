<?php
require_once __DIR__ . '/vendor/autoload.php';

\Cloudinary\Configuration\Configuration::instance([
  'cloud' => [
    'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
    'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
    'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
  ],
  'url' => [
    'secure' => true
  ]
]); 