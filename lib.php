<?php
defined('MOODLE_INTERNAL') || die();

function local_gradepush_quiz_completed($event) {
    // Define the path to the log file
    $logfile = __DIR__ . '/gradepush.log';

    // Log the start of the function execution
    error_log("--- local_gradepush_quiz_completed function started ---\n", 3, $logfile);

    // Log the event details
    error_log("Event triggered: " . $event->eventname . "\n", 3, $logfile);
    error_log("Event data: " . print_r($event->get_data(), true) . "\n", 3, $logfile);

    // Rest of the function code...

    // Log the end of the function execution
    error_log("--- local_gradepush_quiz_completed function completed ---\n", 3, $logfile);
}

// function local_gradepush_quiz_completed($event) {
//     global $DB;

//     // Define the path to the error log file
//     $logfile = __DIR__ . '/error.log';

//     try {
//         // Start error logging
//         error_log("Event triggered: " . $event->eventname . "\n", 3, $logfile);

//         $quizid = get_config('local_gradepush', 'quizid');
//         if ($event->other['instanceid'] != $quizid) {
//             error_log("Quiz ID mismatch. Expected: {$quizid}, Actual: {$event->other['instanceid']}\n", 3, $logfile);
//             return;
//         }

//         $userid = $event->relateduserid;
//         $grade = quiz_get_best_grade($event->other['instanceid'], $userid);

//         error_log("User ID: {$userid}, Grade: {$grade}\n", 3, $logfile);

//         $endpoint = get_config('local_gradepush', 'endpoint');
//         $token = get_config('local_gradepush', 'token');

//         $params = [
//             'userid' => $userid,
//             'grade' => $grade,
//             'token' => $token,
//         ];

//         $curl = new curl();
//         $response = $curl->post($endpoint, $params);

//         if ($response) {
//             $record = new stdClass();
//             $record->userid = $userid;
//             $record->quizid = $quizid;
//             $record->grade = $grade;
//             $record->timesent = time();
//             $DB->insert_record('local_gradepush_sent', $record);
//             error_log("Grade sent successfully.\n", 3, $logfile);
//         } else {
//             if (get_config('local_gradepush', 'enablelogging')) {
//                 $record = new stdClass();
//                 $record->userid = $userid;
//                 $record->quizid = $quizid;
//                 $record->grade = $grade;
//                 $record->timesent = time();
//                 $record->response = $curl->error;
//                 $DB->insert_record('local_gradepush_log', $record);
//             }
//             error_log("Error sending grade: " . $curl->error . "\n", 3, $logfile);
//         }
//     } catch (Exception $e) {
//         // Log the exception to the error log file
//         error_log("Exception occurred: " . $e->getMessage() . "\n", 3, $logfile);
//         error_log("Stack trace: " . $e->getTraceAsString() . "\n", 3, $logfile);
//     }
// }