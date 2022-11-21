<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use League\Flysystem\StorageAttributes;
use munkireport\lib\Modules;


class InstallController extends Controller
{
    private $moduleManager;

    public function __construct()
    {
        $this->moduleManager = app(Modules::class);
    }

    /**
     * Install all modules
     *
     * @author Bochoven, A.E. van
     **/
    public function index()
    {
        $this->modules();
    }

    /**
     * Show all available modules
     *
     * @param ?string $format string format the output, one of 'config', 'env', 'autopkg', or 'json'. Defaults to newline text
     * @returns Response|JsonResponse|View
     **/
    public function dump_modules(?string $format = '')
    {
        $modules = array_keys($this->moduleManager->getModuleList());

        switch ($format) {
            case 'config':
            case 'env':
                $str = implode(', ', $modules);
                return response("MODULES=\"$str\"", 200, ['Content-Type' => 'text/plain']);
            case 'autopkg':
                return view('install.modules_autopkg', ['modules' => $modules]);
            case 'json':
                return response()->json($modules);
            default:
                return response(implode("\n", $modules), 200, ['Content-Type' => 'text/plain']);
        }
    }

    /**
     * Install only selected modules
     *
     * @author Bochoven, A.E. van
     **/
    public function modules()
    {

        $data['install_scripts'] = array();
        $data['uninstall_scripts'] = array();

        // Get required modules from config
        $use_modules = config('_munkireport.modules', array());

        // Override with requested modules
        if (func_get_args()) {
            $use_modules = func_get_args();
        }

        // Collect install scripts from modules
        foreach ($this->moduleManager->getModuleList() as $module => $modulePath) {

            // Check if we need to uninstall this module
            if (! in_array($module, $use_modules)) {
                // Check if there is an uninstall script
                if (is_file($modulePath.'/scripts/uninstall.sh')) {
                    $data['uninstall_scripts'][$module] = $modulePath.'/scripts/uninstall.sh';
                }

                continue;
            }

            // Check if there is a install script
            if (is_file($modulePath.'/scripts/install.sh')) {
                $data['install_scripts'][$module] = $modulePath.'/scripts/install.sh';
            }
        }

        // Buffer script
        ob_start();
        mr_view('install/install_script', $data);
        $content = ob_get_clean();

        // Get etag header
        $etagHeader = ( isset($_SERVER["HTTP_IF_NONE_MATCH"]) ? trim($_SERVER["HTTP_IF_NONE_MATCH"]) : false );

        // generate the etag from content
        $etag = md5($content);

        //set etag-header
        header("Etag: ".$etag);

        // if last modified date is same as "HTTP_IF_MODIFIED_SINCE", send 304 then exit
        if ($etag === $etagHeader) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }

        // new content modified, so output content
        echo $content;
        exit;

//        $contents = view('install.install')->with($data);
//        return response($contents)->header('Content-Type', 'text/plain;charset=UTF-8');
    }

    public function plist()
    {
        $contents = view('install.plist')->with(['version' => '6.0']);
        return response($contents, 200, ['Content-Type' => 'text/xml;charset=UTF-8']);
    }

    public function get_paths()
    {

        $adapter = new \League\Flysystem\Local\LocalFilesystemAdapter(PUBLIC_ROOT.'assets/client_installer/payload/');
        $filesystem = new \League\Flysystem\Filesystem($adapter);
        $listing = $filesystem->listContents('', true)
            ->filter(function (StorageAttributes $attributes) {
                return $attributes->isFile()
                    && basename($attributes->path())[0] !== '.'; /*
                    && strpos($attributes->path(), '@') !== false // Don't accept @ in path - Synology I'm looking at you
                    && strpos($attributes->path(), '.AppleDouble') !== false; // What year is it?*/
            });

        foreach($listing as $item) {
            echo "/" . $item->path() . ";/" . $this->get_target_path($item) . "\n";
        }
    }

    private function get_target_path(&$fileObj)
    {
        if(basename($fileObj->path()) == 'postflight'){
            return dirname($fileObj->path()) . '/' . config('_munkireport.postflight_script');
        }
        if(basename($fileObj->path()) == 'report_broken_client'){
            return dirname($fileObj->path()) . '/' . config('_munkireport.report_broken_client_script');
        }
        return $fileObj->path();
    }

    private function is_regular_file($fileObj)
    {
        if($fileObj['type'] != 'file'){
            return false;
        }
        if($fileObj['basename'][0] == '.'){
            return false;
        }
        // Don't accept @ in path - Synology I'm looking at you
        if(strpos($fileObj['path'], '@') !== false){
            return false;
        }
        // Skip .AppleDouble directories
        if(strpos($fileObj['path'], '.AppleDouble') !== false){
            return false;
        }

        return true;
    }
}
