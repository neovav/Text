<?php
use \neovav\Text\Text;

require_once '..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

echo Text::genPass();
echo "\r\n", Text::genPass(6, 10, ['-', '+', '.', '@', '#']);