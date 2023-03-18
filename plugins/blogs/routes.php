<?php

use Chum\Core\Models\Route;

return array(
    new Route('get', 'blog-list', '/blogs/all', "\Plugins\blogs\controllers\BlogsController", 'index'),
    new Route('get', 'blog.admin.settings', '/admin/blogs/settings', "\Plugins\blogs\controllers\AdminController", 'index')
);