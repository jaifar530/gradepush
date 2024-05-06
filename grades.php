<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_gradepush_gradestab');

$grades = $DB->get_records('local_gradepush_sent');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('gradestab', 'local_gradepush'));

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