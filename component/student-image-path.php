<?php

$pth = '../students/' . $stid . '.jpg';
if (file_exists($pth)) {
    $pth = 'https://eimbox.com/students/' . $stid . '.jpg';
} else {
    $pth = 'https://eimbox.com/students/noimg.jpg';
}