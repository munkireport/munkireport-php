<?php

/**
 * This alias shims the fully namespaced MR\Kiss\Model back to its original name, \Model so
 * that non-upgraded modules can still work the same way. You can remove this when all modules inherit the Laravel
 * HTTP Controller - mosen.
 */
class_alias('MR\Kiss\Model', '\Model');
