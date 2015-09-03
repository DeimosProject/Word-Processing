<?php

include "vendor/autoload.php";

$wp = new \Deimos\WordProcessingWithMorphy();

$content = "привет миру";

var_dump($wp->getArrayWeights($content));