<?php

namespace Compatibility\Kiss\View\Engines;

use Illuminate\Contracts\View\Engine;
use Illuminate\Support\Str;
use Compatibility\Kiss\View;
use Throwable;

class KissEngine implements Engine
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array  $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param  string  $__path
     * @param  array  $__data
     * @return string
     */
    protected function evaluatePath($__path, $__data)
    {
        $obLevel = ob_get_level();

        ob_start();

        // KISSMVC View does the extraction step
        // extract($__data, EXTR_SKIP);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        try {
            $obj = new View();
            $viewPath = config('_munkireport.view_path');
            if (Str::startsWith($__path, $viewPath)) {
                $__path = substr($__path, strlen($viewPath), strlen($__path));
            }
            $info = pathinfo($__path);
            $viewBase = $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'];
            $obj->view($viewBase, $__data, $viewPath);
        } catch (Throwable $e) {
            $this->handleViewException($e, $obLevel);
        }

        return ltrim(ob_get_clean());
    }

    /**
     * Handle a view exception.
     *
     * @param  \Throwable  $e
     * @param  int  $obLevel
     * @return void
     *
     * @throws \Throwable
     */
    protected function handleViewException(Throwable $e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            ob_end_clean();
        }

        throw $e;
    }
}
