<?php

/**
 * Logging error and exiting
 * @param string $error
 * @return void
 */
function logError(string $error) {
    echo date('Y-m-d H:i:s') . ': ERROR:' . $error . PHP_EOL;
    exit(1);
}
