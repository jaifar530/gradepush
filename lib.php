<?php
defined('MOODLE_INTERNAL') || die();

function local_gradepush_quiz_completed($event) {
    global $DB;

    try {
        // Start error logging
        mtrace("Event triggered: " . $event->eventname);

        $quizid = get_config('local_gradepush', 'quizid');
        if ($event->other['instanceid'] != $quizid) {
            mtrace("Quiz ID mismatch. Expected: {$quizid}, Actual: {$event->other['instanceid']}");
            return;
        }

        $userid = $event->relateduserid;
        $grade = quiz_get_best_grade($event->other['instanceid'], $userid);

        mtrace("User ID: {$userid}, Grade: {$grade}");

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
            mtrace("Grade sent successfully.");
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
            mtrace("Error sending grade: " . $curl->error);
        }
    } catch (Exception $e) {
        // Log the exception
        mtrace("Exception occurred: " . $e->getMessage());
        mtrace("Stack trace: " . $e->getTraceAsString());
    }
}