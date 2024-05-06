<?php
require_once(__DIR__ . '/../../config.php');
require_login();
require_capability('moodle/site:config', context_system::instance());

$url = new moodle_url('/local/gradepush/grades.php');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('gradestab', 'local_gradepush'));
$PAGE->set_heading(get_string('gradestab', 'local_gradepush'));

if (get_config('local_gradepush', 'enablegrades')) {
    $grades = $DB->get_records('local_gradepush_sent');
} else {
    $grades = [];
}

echo $OUTPUT->header();

if (!empty($grades)) {
    $table = new html_table();
    $table->head = ['User ID', 'Quiz ID', 'Grade', 'Time Sent'];

    foreach ($grades as $grade) {
        $table->data[] = [
            $grade->userid,
            $grade->quizid,
            $grade->grade,
            userdate($grade->timesent),
        ];
    }

    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('nogradesfound', 'local_gradepush'), 'warning');
}

echo $OUTPUT->footer();