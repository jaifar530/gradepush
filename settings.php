<?php
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new admin_settingpage('local_gradepush', get_string('pluginname', 'local_gradepush'));

    $settings->add(new admin_setting_configtext('local_gradepush/endpoint',
        get_string('endpoint', 'local_gradepush'), '', ''));

    $settings->add(new admin_setting_configtext('local_gradepush/token',
        get_string('token', 'local_gradepush'), '', ''));

    $settings->add(new admin_setting_configtext('local_gradepush/quizid',
        get_string('quizid', 'local_gradepush'), '', ''));

    $settings->add(new admin_setting_configcheckbox('local_gradepush/enablelogging',
        get_string('enablelogging', 'local_gradepush'), '', 0));

    $ADMIN->add('localplugins', $settings);
}