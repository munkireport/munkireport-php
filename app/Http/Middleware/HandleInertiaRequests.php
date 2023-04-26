<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use munkireport\lib\Dashboard;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $modules = app(\munkireport\lib\Modules::class)->loadInfo();
        $dashboard = app(Dashboard::class);
        $dashboard->loadAll();

        $admin_pages_legacy = [];
        foreach (scandir(conf('view_path') . 'admin') as $list_url) {
            if (strpos($list_url, 'php')) {
                $page_url = strtok($list_url, '.');

                $admin_pages_legacy[] = [
                    'href' => '/admin/show/' . $page_url,
                    'i18n' => "nav.admin." . strtok($list_url, '.'),
                ];
            }
        }

        $admin_pages_v5 = $modules->getDropdownData('admin_pages', 'module', '');

        $locales = [];
        foreach (scandir(PUBLIC_ROOT.'assets/locales') AS $list_url) {
            if (strpos($list_url, 'json')) {
                $locales[] = strtok($list_url, '.');
            }
        }

        return array_merge(parent::share($request), [
            //
            'appName' => config('app.name'),
            'dashboards' => $dashboard->getDropdownData('/show/dashboard', 'dashboard'),
            'reports' => $modules->getDropdownData('reports', '/show/report', ''),
            'listings' => $modules->getDropdownData('listings', '/show/listing', ''),
            'admin' => $admin_pages_legacy + $admin_pages_v5,
            'user' => $request->user(),
            'locales' => $locales,
        ]);
    }
}
