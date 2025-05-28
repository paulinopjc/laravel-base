<?php

use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\settings\SiteLogoController;
use App\Models\Menu;

Route::get('/cms-pages/{slug}', [CmsPageController::class, 'showBySlug']);

