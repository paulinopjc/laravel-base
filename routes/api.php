<?php

use App\Http\Controllers\Admin\CmsPageController;

Route::get('/cms-pages/{slug}', [CmsPageController::class, 'showBySlug']);

