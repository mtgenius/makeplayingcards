<?php

$url = null;
if (array_key_exists('multiverseid', $_GET)) {
  $url = 'https://api.scryfall.com/cards/multiverse/' . $_GET['multiverseid'] . '?format=image&version=png';
}
else if (array_key_exists('url', $_GET)) {
  $url = 'https://img.scryfall.com/cards/png/en/' . $_GET['url'] . '.png';
}

if (!$url) {
  exit('Cannot find URL.');
}

header('Content-Type: image/png; charset=utf-8');
$old_image_string = file_get_contents($url);

$old_height = 1040;
$old_width = 745;
$old_image = imagecreatefromstring($old_image_string);

$border_color = imagecolorat($old_image, 0, 520);

$new_height = 1110;
$new_width = 816;
$new_image = imagecreatetruecolor($new_width, $new_height);
imagefill($new_image, 0, 0, $border_color);

imagecopyresampled(
  $new_image, $old_image,
  round(($new_width - $old_width) / 2), round(($new_height - $old_height) / 2),
  0, 0,
  $old_width, $old_height,
  $old_width, $old_height
);

$box =
  array_key_exists('box', $_GET) ?
    $_GET['box'] === 'creature' ?
      array(340, 84) :
      array(358, 105) :
    null;

if ($box !== null) {
  imagefilledrectangle(
    $new_image,
    $new_width - $box[0], $new_height - $box[1],
    $new_width, $new_height,
    $border_color
  );
}

imagepng($new_image);

?>
