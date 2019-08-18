<?php
use \neovav\Text\Text;

require_once '..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$str = 'Привет';
echo $str.' => '.Text::translit($str);