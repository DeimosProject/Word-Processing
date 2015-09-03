<?php

include "vendor/autoload.php";

$wp = new \Deimos\WordProcessingWithMorphy();

$content = "deleted world adsfasdf111 сельскохозяйственно-машиностроительными -математическая-";

var_dump($wp->getArrayWeights($content));