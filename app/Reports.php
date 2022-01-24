<?php
namespace App;

use Illuminate\Support\Facades\Log;
use munkireport\lib\Modules;
use App\Hash as MunkiReportHash;

class Reports
{
    protected $processors = [
        'reportdata' => \munkireport\processors\ReportDataProcessor::class,
        'machine' => \munkireport\processors\MachineProcessor::class,
    ];

    protected $modules;

    public function __construct(Modules $modules) {
        $this->modules = $modules;
    }

    /**
     * Process all items submitted, for all modules.
     *
     * @param string $serial The serial number of the machine sending the reports.
     * @param array $items The array of items, from the key `items` in the request.
     *
     * @returns array An array of messages to append to the response which may be displayed on the client.
     */
    public function processAll(string $serial, array $items): array
    {
        $messages = [];
        foreach ($items as $name => $item) {
            Log::info("starting: {$name}");
            $messages[] = "Server info: starting: {$name}";

            // All models are lowercase
            $name = strtolower($name);

            if (preg_match('/[^\da-z_]/', $name)) {
                Log::info("Model has an illegal name: {$name}");
                $messages[] = "Server info: Model has an illegal name: {$name}";
                continue;
            }

            // All models should be part of a module
            if (substr($name, -6) == '_model') {
                $module = substr($name, 0, -6);
            } else // Legacy clients
            {
                $module = $name;
            }

            $messages = array_merge($messages, $this->process($serial, $module, $item));
        }

        return $messages;
    }

    /**
     * Process data submitted by one machine for a single module.
     *
     * @param string $serial The serial number of the machine submitting the report data.
     * @param string $module The module that the data relates to.
     * @param array $item The module report item, which contains keys for `data` and `hash`
     *
     * @returns array An array of messages to append to the response which may be displayed on the client.
     */
    public function process(string $serial, string $module, array $item): array
    {
        $messages = [];

        if (isset($this->processors[$module])) {
            $processorClass = $this->processors[$module];
            $processor = new $processorClass($module, $serial);
            $processor->run($item['data']);
        } else {
            if ($this->runV1ModuleProcessor($module, $serial, $item['data'])) {
                $this->updateHash($module, $serial, $item['hash']);
            } elseif ($this->runV1ModuleModelProcessor($module, $serial, $item['data'])) {
                $this->updateHash($module, $serial, $item['hash']);
            } else {
                Log::info("No processor found for: {$module}");
                $messages[] = "Server info: No processor found for: {$module}";
            }
        }

        return $messages;
    }

    /**
     * Add a processor class for a specific module name.
     *
     * Older modules that contain <module>_processor.php or <module>_model.php are
     * still processed in the normal way and don't require this method.
     *
     * @param string $module
     * @param string $className
     */
    public function addProcessor(string $module, string $className): void
    {
        $this->processors[$module] = $className;
    }

    /**
     * Process report data using the current (v1 module) convention:
     *
     * - Look for a <modulename>_processor.php class and use that, *OR*
     * - Try to use the <modulename>_model.php classes process() function
     * - Otherwise generate an info event "Processor not found"
     *
     * @param string $module The module name to process data for.
     * @param string $serial The serial number of the machine submitting data.
     * @param string $data The data submitted, which may be encoded or serialized.
     * @returns bool True on success, otherwise False
     */
    protected function runV1ModuleProcessor(string $module, string $serial, string $data): bool
    {
        $processorPath = null;
        if ($this->modules->getModuleProcessorPath($module, $processorPath)) {
            require_once($processorPath);
            $className = ucfirst("\\{$module}_processor");
            if (!class_exists($className, false)) {
                Log::warning("Class not found: {$className}");
                return False;
            }

            try {
                $processor = new $className($module, $serial);
                if (!method_exists($processor, 'run')) {
                    Log::warning("No run method in: {$className}");
                    return False;
                }

                $processor->run($data);
                return True;
            } catch (\Exception $e) {
                Log::warning("An error occurred while processing: {$className}");
                Log::warning("Error: " . $e->getMessage());
                return False;
            }
        }

        return False;
    }

    /**
     * Process report data using the current (v1 module) convention:
     *
     * @param string $module
     * @param string $serial
     * @param string $data
     * @return bool
     */
    protected function runV1ModuleModelProcessor(string $module, string $serial, string $data): bool
    {
        $modelPath = null;
        if ($this->modules->getModuleModelPath($module, $modelPath)) {
            require_once($modelPath);
            $className = ucfirst("\\{$module}_model");
            if (!class_exists($className, false)) {
                // $this->_warning("Class not found: $classname");
                Log::warning("Class not found: {$className}");
                return False;
            }

            try {
                $processor = new $className($serial);
                if (!method_exists($processor, 'process')) {
                    Log::warning("No process method in: {$className}");
                    return False;
                }

                $processor->process($data);
                return True;
            } catch (\Exception $e) {
                Log::warning("An error occurred while processing: {$className}");
                Log::warning("Error: " . $e->getMessage());
                return False;
            }
        }

        return False;
    }


    protected function updateHash(string $module, string $serial, string $hash)
    {
        MunkiReportHash::updateOrCreate(
            [
                'serial_number' => $serial,
                'name' => $module,
            ],
            [
                'name' => $module,
                'hash' => $hash,
                'timestamp' => time(),
            ]
        );
    }
}
