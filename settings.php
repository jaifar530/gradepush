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

    $settings->add(new admin_setting_configcheckbox('local_gradepush/enablegrades',
        get_string('enablegrades', 'local_gradepush'), '', 1));

    $ADMIN->add('localplugins', $settings);
}

$settings = null;

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_externalpage('local_gradepush_manage',
        get_string('pluginname', 'local_gradepush'),
        new moodle_url('/admin/settings.php', ['section' => 'local_gradepush'])));
    
    $ADMIN->add('localplugins', new admin_externalpage('local_gradepush_logs',
        get_string('logtab', 'local_gradepush'),
        new moodle_url('/local/gradepush/logs.php')));

    $ADMIN->add('localplugins', new admin_externalpage('local_gradepush_grades',
        get_string('gradestab', 'local_gradepush'),
        new moodle_url('/local/gradepush/grades.php')));
}