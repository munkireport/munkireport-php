<?php
/// CLASS ALIASES FOR BACKWARDS COMPATIBILITY
/// ORDERING IS IMPORTANT !!!

/**
 * This alias shims the fully namespaced Compatibility\Kiss\View back to its original name, \View so
 * that non-upgraded modules can still work the same way. You can remove this when all modules inherit the Laravel
 * HTTP Controller - mosen.
 */
class_alias('Compatibility\Kiss\View', '\View');

/**
 * This alias shims the fully namespaced Compatibility\Kiss\Model back to its original name, \Model so
 * that non-upgraded modules can still work the same way. You can remove this when all modules inherit the Laravel
 * HTTP Controller - mosen.
 */
class_alias('Compatibility\Kiss\Model', '\Model');

/**
 * This alias shims the Eloquent base model from when we were using the Capsule class only, and not the full framework.
 */
class_alias('Compatibility\Capsule\MRModel', 'munkireport\models\MRModel');

/**
 * This class alias provided for backwards compatibility when 3rd party modules access machine via
 * \Machine_model without the namespace.
 */
//class_alias('munkireport\models\Machine_model', '\Machine_model');
//class_alias('munkireport\models\Machine_model', '\App\Machine');
// TODO: crashes the autoloader

/**
 * This alias shims the fully namespaced Compatibility\Kiss\ModuleController back to its original name, \Module_controller so
 * that non-upgraded modules can still work the same way. You can remove this when all modules inherit the Laravel
 * HTTP Controller - mosen.
 */
class_alias('Compatibility\Kiss\ModuleController', '\Module_controller');

/**
 * This class alias provided for backwards compatibility when 3rd party modules access reportdata via
 * \Reportdata_model without the namespace.
 */
class_alias('App\ReportData', '\Reportdata_model');


