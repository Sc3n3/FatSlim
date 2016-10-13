<?php


\Sc3n3\FatSlim\Bootstrap::getLoader();

is_file(path('app') .'/routes.php') ? require path('app') .'/routes.php' : '';
