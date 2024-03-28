<?php
echo "404 PAGE";
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
print($uri);
