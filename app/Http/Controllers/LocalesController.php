<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use munkireport\lib\Modules;

/**
 * Class LocalesController
 *
 * Why does this exist when LocaleController already exists?
 * i18next expects a different format for translations, and this controller delivers them in exactly the format
 * required. `LocaleController` is still included to preserve backwards compatibility with modules that might load
 * their own locales.
 *
 * @package App\Http\Controllers
 */
class LocalesController extends Controller
{
    protected $fallbackLanguage = 'en';
    protected $modules;

    public function __construct(Modules $modules) {
        $this->modules = $modules;
    }

    /**
     * Get translations for the specified language/locale.
     *
     * Designed for compatibility with [i18next-fetch-backend](https://github.com/dotcore64/i18next-fetch-backend)
     *
     * @param string $language The language code to fetch, eg. `en`
     * @param string|null $namespace The translation namespace to load, the default is just `translation`. MunkiReport
     *                               does not use this feature (yet).
     */
    public function get(string $language, ?string $namespace = "translation") {
        if ($namespace === "translation") {
            $translations = public_path("assets/locales/{$language}.json");
            if (!file_exists($translations)) {
                $translations = public_path("assets/locales/{$this->fallbackLanguage}.json");
                if (!file_exists($translations)) {
                    return abort(
                        500,
                        "Could not find the specified locale or any viable fallback locale. Your installation of MunkiReport is broken"
                    );
                }
            }

            $translationsData = file_get_contents($translations);
        } else {
            // Non-core namespace, need to load module data
            $this->modules->loadInfo();
            $translationsData = $this->modules->getModuleLocales($language);
        }


        return response($translationsData, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Get a list of available locales
     *
     * @return array of locale names
     */
    public function index(): array {
        $locales = [];
        foreach (scandir(public_path("assets/locales")) as $item) {
            if (!strpos($item, 'json')) continue;
            $locales[] = strtok($item, '.');
        }

        return $locales;
    }
}
