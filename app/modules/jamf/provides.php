<?php

return array(
    'client_tabs' => array(
        'jamf-tab' => array('view' => 'jamf_tab', 'i18n' => 'jamf.title'),
    ),
    'listings' => array(
        'jamf' => array('view' => 'jamf_listing', 'i18n' => 'jamf.title'),
    ),
    'widgets' => array(
        'jamf_enrolled_via_dep' => array('view' => 'jamf_enrolled_via_dep_widget'),
        'jamf_user_approved_enrollment' => array('view' => 'jamf_user_approved_enrollment_widget'),
        'jamf_user_approved_mdm' => array('view' => 'jamf_user_approved_mdm_widget'),
        'jamf_mdm_capable' => array('view' => 'jamf_mdm_capable_widget'),
        'jamf_purchased_leased' => array('view' => 'jamf_purchased_leased_widget'),
        'jamf_automatic_login_disabled' => array('view' => 'jamf_automatic_login_disabled_widget'),
        'jamf_xprotect_version' => array('view' => 'jamf_xprotect_version_widget'),
        'jamf_version' => array('view' => 'jamf_version_widget'),
        'jamf_pending_failed' => array('view' => 'jamf_pending_failed_widget'),
        'jamf_departments' => array('view' => 'jamf_departments_widget'),
        'jamf_buildings' => array('view' => 'jamf_buildings_widget'),
        'jamf_checkin' => array('view' => 'jamf_checkin_widget'),
    ),
    'admins' => array(
        'jamf' => array('view' => 'jamf_admin', 'i18n' => 'jamf.title'),
    ),
    'reports' => array(
        'jamf' => array('view' => 'jamf_report', 'i18n' => 'jamf.report')
    )
);
