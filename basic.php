<?php
include 'Cloudinary.php';
include 'Uploader.php';
\Cloudinary::config(array(
    "cloud_name" => "dalfincmm",
    "api_key" => "191674575895794",
    "api_secret" => "TDHhRFHT0INFHG8jOkpWfB0Osow"
));
\Cloudinary\Uploader::upload("http://res.cloudinary.com/demo/image/upload/couple.jpg");
