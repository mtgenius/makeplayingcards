<?php

$url = null;
if (array_key_exists('multiverseid', $_GET)) {
  $url = 'https://api.scryfall.com/cards/multiverse/' . $_GET['multiverseid'] . '?format=image&version=png';
}
else if (array_key_exists('url', $_GET)) {
  if (preg_match('/^https?\:\/\//', $_GET['url'])) {
    $url = $_GET['url'];
  }
  else {
    $url = 'https://img.scryfall.com/cards/png/en/' . $_GET['url'] . '.png';
  }
}

if (!$url) {
  exit('Cannot find URL.');
}

$inner_height = 1040;
$inner_width = 745;
$outer_height = 1110;
$outer_width = 816;

$old_image_string = file_get_contents($url);

$old_image = imagecreatefromstring($old_image_string);
list($old_width, $old_height) = getimagesizefromstring($old_image_string);

$border_color = imagecolorat($old_image, 0, 520);

$new_image = imagecreatetruecolor($outer_width, $outer_height);
imagefill($new_image, 0, 0, $border_color);

imagecopyresampled(
  $new_image, $old_image,
  round(($outer_width - $inner_width) / 2),
  round(($outer_height - $inner_height) / 2),
  0, 0,
  $inner_width, $inner_height,
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
    $outer_width - $box[0], $outer_height - $box[1],
    $outer_width, $outer_height,
    $border_color
  );
}

header('Content-Type: image/png; charset=utf-8');
imagepng($new_image);

?>
