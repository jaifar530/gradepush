<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_gradepush_logtab');

$logs = $DB->get_records('local_gradepush_log');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('logtab', 'local_gradepush'));

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