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
  header('Content-Type: text/plain; charset=utf-8');
  exit('Cannot find URL.');
}

$stdout = fopen('php://stdout', 'w');
fwrite($stdout, 'URL: ' . $url . PHP_EOL);
fclose($stdout);

$inner_height = 1040;
$inner_width = 745;
$outer_height = 1110;
$outer_width = 816;

$padding_left = round(($outer_width - $inner_width) / 2);
$padding_top = round(($outer_height - $inner_height) / 2);

$old_image_string = file_get_contents($url);

$old_image = imagecreatefromstring($old_image_string);
list($old_width, $old_height) = getimagesizefromstring($old_image_string);

$new_image = imagecreatetruecolor($outer_width, $outer_height);

// Paste the new image so that we can grab color from it.
imagecopyresampled(
  $new_image, $old_image,
  $padding_left, $padding_top,
  0, 0,
  $inner_width, $inner_height,
  $old_width, $old_height
);

// Grab the border color from the bottom of the image.
$border_color = imagecolorat(
  $new_image,
  round($outer_width / 2),
  $outer_height - $padding_top - 1
);

// Fill the entire image with the border color.
imagefill($new_image, 0, 0, $border_color);

// Paste the new image on top of the border color.
imagecopyresampled(
  $new_image, $old_image,
  $padding_left, $padding_top,
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
    $outer_width - $padding_left - $box[0],       $outer_height - $box[1],
    $outer_width - $padding_left, $outer_height - $padding_top,
    $border_color
  );
}

// Top Left Corner
imagefilledrectangle(
  $new_image,
  $padding_left,      $padding_top,
  $padding_left + 25, $padding_top + 25,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $padding_left,      $padding_top,
  $padding_left + 35, $padding_top + 10,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $padding_left,      $padding_top,
  $padding_left + 10, $padding_top + 35,
  $border_color
);

// Top Right Corner
imagefilledrectangle(
  $new_image,
  $outer_width - $padding_left - 25, $padding_top,
  $outer_width - $padding_left,      $padding_top + 25,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $outer_width - $padding_left - 35, $padding_top,
  $outer_width - $padding_left,      $padding_top + 10,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $outer_width - $padding_left - 10, $padding_top,
  $outer_width - $padding_left,      $padding_top + 35,
  $border_color
);

// Bottom Left Corner
imagefilledrectangle(
  $new_image,
  $padding_left,      $outer_height - $padding_left - 25,
  $padding_left + 25, $outer_height - $padding_left,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $padding_left,      $outer_height - $padding_left - 35,
  $padding_left + 10, $outer_height - $padding_left,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $padding_left,      $outer_height - $padding_left - 10,
  $padding_left + 35, $outer_height - $padding_left,
  $border_color
);

// Bottom Right Corner
imagefilledrectangle(
  $new_image,
  $outer_width - $padding_left - 25, $outer_height - $padding_top - 25,
  $outer_width - $padding_left,      $outer_height - $padding_top,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $outer_width - $padding_left - 10, $outer_height - $padding_top - 35,
  $outer_width - $padding_left,      $outer_height - $padding_top,
  $border_color
);
imagefilledrectangle(
  $new_image,
  $outer_width - $padding_left - 35, $outer_height - $padding_top - 10,
  $outer_width - $padding_left,      $outer_height - $padding_top,
  $border_color
);

// Full art top
imagecopyresampled(
  $new_image, $new_image,
  $padding_left, 0,
  $padding_left, $padding_top,
  $inner_width, $padding_top,
  $inner_width, 1
);

// Full art left
imagecopyresampled(
  $new_image, $new_image,
  0, $padding_top,
  $padding_left, $padding_top,
  $padding_left, $inner_height,
  1, $inner_height
);

// Full art right
imagecopyresampled(
  $new_image, $new_image,
  $outer_width - $padding_left, $padding_top,
  $outer_width - $padding_left, $padding_top,
  $padding_left, $inner_height,
  1, $inner_height
);

header('Content-Type: image/png; charset=utf-8');
imagepng($new_image);

?>
