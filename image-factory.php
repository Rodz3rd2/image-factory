<?php

// default gray
$bg_colors = [
    'red' => isset($_GET['bg_r']) ? $_GET['bg_r'] : 128,
    'green' => isset($_GET['bg_g']) ? $_GET['bg_g'] : 128,
    'blue' => isset($_GET['bg_b']) ? $_GET['bg_b'] : 128
];

// default white
$text_colors = [
    'red' => isset($_GET['text_r']) ? $_GET['text_r'] : 255,
    'green' => isset($_GET['text_g']) ? $_GET['text_g'] : 255,
    'blue' => isset($_GET['text_b']) ? $_GET['text_b'] : 255
];

// text properties
$text_prop = [
    'size' => isset($_GET['text_size']) ? $_GET['text_size'] : 10,
    'angle' => isset($_GET['text_angle']) ? $_GET['text_angle'] : 0,
    'font' => isset($_GET['text_font']) ? $_GET['text_font'] : "./UbuntuMono-B.ttf",
    'text' => isset($_GET['text']) ? $_GET['text'] : "Aa",

    'x' => isset($_GET['text_x']) ? $_GET['text_x'] : 0,
    'y' => isset($_GET['text_y']) ? $_GET['text_y'] : 0,
    'w' => isset($_GET['text_w']) ? $_GET['text_w'] : 0,
    'h' => isset($_GET['text_h']) ? $_GET['text_h'] : 0
];

$image_dimension = [
    'w' => isset($_GET['w']) ? $_GET['w'] : 200,
    'h' => isset($_GET['w']) ? $_GET['w'] : 200
];

$image = imagecreate($image_dimension['w'], $image_dimension['h']) or die("Cannot initialize new GD image stream");

$bbox = imagettfbbox($text_prop['size'], $text_prop['angle'], $text_prop['font'], $text_prop['text']);

// lower left
$ll_coor_plane = [
    'x' => array_shift($bbox),
    'y' => array_shift($bbox)
];

// lower right
$lr_coor_plane = [
    'x' => array_shift($bbox),
    'y' => array_shift($bbox)
];

// upper right
$ur_coor_plane = [
    'x' => array_shift($bbox),
    'y' => array_shift($bbox)
];

// upper left
$ul_coor_plane = [
    'x' => array_shift($bbox),
    'y' => array_shift($bbox)
];

$text_prop['w'] = $ur_coor_plane['x'] - $ul_coor_plane['x'];
$text_prop['h'] = $ll_coor_plane['y'] - $ul_coor_plane['y'];

$diffx = $image_dimension['w'] - $text_prop['w'];
$diffy = $image_dimension['h'] - $text_prop['h'];

// center the text if x and y are not define
if ($text_prop['x'] === 0 && $text_prop['y'] === 0) {
    $text_prop['x'] = $diffx / 2;
    $text_prop['y'] = ($diffy / 2) + $text_prop['h'];
} else {
    $text_prop['y'] += $text_prop['h'];
}

$bg_color = imagecolorallocate($image, $bg_colors['red'], $bg_colors['green'], $bg_colors['blue']);
$text_color = imagecolorallocate($image, $text_colors['red'], $text_colors['green'], $text_colors['blue']);

imagettftext($image, $text_prop['size'], $text_prop['angle'], $text_prop['x'], $text_prop['y'], $text_color, $text_prop['font'], $text_prop['text']);

header("Content-Type: image/png");
imagepng($image);
imagedestroy($image);