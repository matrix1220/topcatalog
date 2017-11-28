<?php
include 'Cloudinary.php';
include 'Uploader.php';
if (file_exists('settings.php')) {
  include 'settings.php';
}
\Cloudinary\Uploader::upload("http://res.cloudinary.com/demo/image/upload/couple.jpg");
