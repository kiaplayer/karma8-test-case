<?php
/** @noinspection PhpUnusedParameterInspection */
/** @noinspection SqlResolve */

require_once('common.php');
$dbConnection = require_once('db.php');

/**
 * Email check
 * @param string $email
 * @return int (0 - invalid, 1 - valid)
 * @todo
 */
function check_email(string $email): int {
    // Business logic here...
    return 1;
}

// Total count of checked emails
$checkedCount = 0;

// All confirmed emails are valid by default, we should not check them additionally
$query = '
    UPDATE emails AS e
    SET 
        e.valid = 1,
        e.checked = 1
    FROM users AS u
    WHERE 
        u.email = e.email AND 
        u.confirmed = 1 AND 
        e.checked = 0
';
$result = pg_query($dbConnection, $query);
if (!$result) {
    logError('Database query error: ' . pg_last_error($dbConnection));
}

$checkedCount += pg_affected_rows($result);

// Check remaining emails
$query = '
    SELECT
        email
    FROM emails
    WHERE 
        checked = 0
';
$result = pg_query($dbConnection, $query);
if (!$result) {
    logError('Database query error: ' . pg_last_error($dbConnection));
}
$preparedStatement = pg_prepare($dbConnection, 'updateEmail', 'UPDATE emails SET valid = $1, checked = 1 WHERE email = $2');
if (!$preparedStatement) {
    logError('Database prepare query error: ' . pg_last_error($dbConnection));
}
while ($row = pg_fetch_row($result)) {
    $email = $row[0];
    $isValid = check_email($email);
    $result = pg_execute($dbConnection, 'updateEmail', [$isValid, $email]);
    if (!$result) {
        logError('Database query error: ' . pg_last_error($dbConnection));
    }
    $checkedCount += 1;
}

echo 'Checked emails: '  . $checkedCount . PHP_EOL;
