<?php


namespace Compatibility\Kiss;

/**
 * Module controller class
 *
 * @package munkireport
 * @author AvB
 * @note Renamed from Module_controller to ModuleController
 **/
class ModuleController extends Controller
{

    // Module, override in child object
    protected $module_path;

    public function get_script($filename = '')
    {
        $this->dumpModuleFile($filename, 'scripts', 'text/plain');
    }

    public function js($filename = '')
    {
        $this->dumpModuleFile($filename . '.js', 'js', 'application/javascript');
    }

    private function dumpModuleFile($filename, $directory, $content_type)
    {
        // Check if dir exists
        $dir_path = $this->module_path . '/' . $directory . '/';
        if (is_readable($dir_path)) {
            // Get filenames in module dir (just to be safe)
            $files = array_diff(scandir($dir_path), ['..', '.']);
        } else {
            $files = [];
        }

        $file_path = $dir_path . basename($filename);

        if (! in_array($filename, $files) or ! is_readable($file_path)) {
            // Signal to curl that the load failed
            header("HTTP/1.0 404 Not Found");
            printf('File %s is not available', $filename);
            return;
        }

        // Dump the file
        header("Content-Type: $content_type");
        echo file_get_contents($file_path);
    }
}
