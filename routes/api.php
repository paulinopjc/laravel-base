<?php

use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\MenuController;
use App\Models\Menu;

Route::get('/cms-pages/{slug}', [CmsPageController::class, 'showBySlug']);

Route::get('/menus', [MenuController::class, 'show']);

