<?php
require_once(__DIR__ . '/../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());

$url = new moodle_url('/local/gradepush/logs.php');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('logtab', 'local_gradepush'));
$PAGE->set_heading(get_string('logtab', 'local_gradepush'));

if (get_config('local_gradepush', 'enablelogging')) {
    $logs = $DB->get_records('local_gradepush_log');
} else {
    $logs = [];
}

echo $OUTPUT->header();

if (!empty($logs)) {
    $table = new html_table();
    $table->head = ['User ID', 'Quiz ID', 'Grade', 'Time Sent', 'Response'];

    foreach ($logs as $log) {
        $table->data[] = [
            $log->userid,
            $log->quizid,
            $log->grade,
            userdate($log->timesent),
            $log->response,
        ];
    }

    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('nologsfound', 'local_gradepush'), 'warning');
}

echo $OUTPUT->footer();