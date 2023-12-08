<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $menus = [];
        if (isset($user)) {
          $menus = Menu::with('children')
            ->whereHas('roles', function ($q) use ($user) {
              $q->where('role_id', '=', $user->role_id);
            })
            ->whereNull('parent_id')
            ->orderBy('sequence', 'asc')
            ->get();
        }
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
            ],
            'menus' => $menus,
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
