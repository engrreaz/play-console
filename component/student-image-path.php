<?php

$pth = '../students/' . $stid . '.jpg';
$pth = file_exists($pth) ? "https://eimbox.com/students/{$stid}.jpg" : 'https://eimbox.com/students/noimg.jpg';