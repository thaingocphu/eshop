<?php

namespace App\Http\Middleware;

use App\Helper\Cart;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Illuminate\Foundation\Application;


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
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],

            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'warning' => fn() => $request->session()->get('error'),
                'infor' => fn() => $request->session()->get('infor'),

            ],

            'ziggy' => fn() =>[
                (new Ziggy)->toArray(),
                'location' => $request->url()
            ],

            'cart' => new CartResource(Cart::getProductsAndCartItems()),

            'canLogin' => app('router')->has('login'),

            'canRegister' => app('router')->has('register'),

            'laravelVersion' => Application::VERSION,

            'phpVersion' => PHP_VERSION,
            
            'csrf_token' => csrf_token()
        ];
    }
}
