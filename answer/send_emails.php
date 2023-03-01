<?php
/** @noinspection PhpUnusedParameterInspection */
/** @noinspection SqlResolve */

require_once('common.php');
$dbConnection = require_once('db.php');

// Specific options
$notificationPeriodInDays = 3;
$notificationFrom = 'server@service.com';
$notificationSubjectTemplate = 'Subscription is expiring';
$notificationBodyTemplate = '{username}, your subscription is expiring soon';

/**
 * Send email
 * @param string $email
 * @param string $from
 * @param string $to
 * @param string $subj
 * @param string $body
 * @return bool
 * @todo
 */
function send_email(string $email, string $from, string $to, string $subj, string $body): bool {
    // Business logic here...
    return true;
}

// Total count of sent emails
$sentCount = 0;

$query = "
    SELECT
        u.username,
        u.email
    FROM users AS u
    INNER JOIN emails AS e ON e.email = u.email 
    WHERE
        DATE_PART('day', TO_TIMESTAMP(validts) - NOW()) BETWEEN 1 AND $1 AND
        e.valid = 1
";
$result = pg_query_params($dbConnection, $query, [NOTIFICATION_PERIOD_IN_DAYS]);
if (!$result) {
    logError('Database query error: ' . pg_last_error($dbConnection));
}
while ($row = pg_fetch_row($result)) {
    $username = $row[0];
    $email = $row[1];
    $subject = strtr($notificationSubjectTemplate, ['{username}' => $username]);
    $body = strtr($notificationBodyTemplate, ['{username}' => $username]);
    $sendResult = send_email($email, NOTIFICATION_FROM, $email, $subject, $body);
    $sentCount += $sendResult ? 1 : 0;
}

echo 'Sent emails: '  . $sentCount . PHP_EOL;
