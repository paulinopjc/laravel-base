<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Inertia\Middleware;
use Illuminate\Http\Request;

class FrontendSharedData extends Middleware
{
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'frontendMenus' => fn () => Menu::with('children')->whereNull('parent_id')->orderBy('order')->get(),
        ]);
    }
}

