<?php

/**
 * This alias shims the fully namespaced Compatibility\Kiss\ModuleController back to its original name, \Module_controller so
 * that non-upgraded modules can still work the same way. You can remove this when all modules inherit the Laravel
 * HTTP Controller - mosen.
 */
class_alias('Compatibility\Kiss\ModuleController', '\Module_controller');

