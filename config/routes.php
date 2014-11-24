<?php

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/default/index.thtml)...
 */
$Route->connect('/', array('controller' => 'default', 'action' => 'index'));
$Route->connect('/promo-cat-(.+)', array('controller' => 'promotion', 'action' => 'cat'));
$Route->connect('/promo-midcat-(.+)', array('controller' => 'promotion', 'action' => 'midcat'));
$Route->connect('/9.9', array('controller' => 'promotion', 'action' => 'cat9'));
$Route->connect('/9.9-(.+)', array('controller' => 'promotion', 'action' => 'cat9'));
$Route->connect('/item-([a-z0-9]+-[0-9]+)', array('controller' => 'promotion', 'action' => 'detail'));
?>