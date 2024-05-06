<?php
defined('MOODLE_INTERNAL') || die();

function local_gradepush_quiz_completed($event) {
    global $DB;

    // Start error logging
    $logfile = __DIR__ . '/errors.log';
    file_put_contents($logfile, "Event triggered: " . $event->eventname . "\n", FILE_APPEND);

    $quizid = get_config('local_gradepush', 'quizid');
    if ($event->other['instanceid'] != $quizid) {
        file_put_contents($logfile, "Quiz ID mismatch. Expected: {$quizid}, Actual: {$event->other['instanceid']}\n", FILE_APPEND);
        return;
    }

    $userid = $event->relateduserid;
    $grade = quiz_get_best_grade($event->other['instanceid'], $userid);

    file_put_contents($logfile, "User ID: {$userid}, Grade: {$grade}\n", FILE_APPEND);

    $endpoint = get_config('local_gradepush', 'endpoint');
    $token = get_config('local_gradepush', 'token');

    $params = [
        'userid' => $userid,
        'grade' => $grade,
        'token' => $token,
    ];

    $curl = new curl();
    $response = $curl->post($endpoint, $params);

    if ($response) {
        $record = new stdClass();
        $record->userid = $userid;
        $record->quizid = $quizid;
        $record->grade = $grade;
        $record->timesent = time();
        $DB->insert_record('local_gradepush_sent', $record);
        file_put_contents($logfile, "Grade sent successfully.\n", FILE_APPEND);
    } else {
        if (get_config('local_gradepush', 'enablelogging')) {
            $record = new stdClass();
            $record->userid = $userid;
            $record->quizid = $quizid;
            $record->grade = $grade;
            $record->timesent = time();
            $record->response = $curl->error;
            $DB->insert_record('local_gradepush_log', $record);
        }
        file_put_contents($logfile, "Error sending grade: " . $curl->error . "\n", FILE_APPEND);
    }
}