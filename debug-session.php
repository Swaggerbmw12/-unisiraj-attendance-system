<?php
/**
 * Session Debug Script
 * Shows all session variables
 */

session_start();

echo "==================================\n";
echo "Current Session Variables\n";
echo "==================================\n\n";

if (empty($_SESSION)) {
    echo "No session variables set.\n";
} else {
    foreach ($_SESSION as $key => $value) {
        if (is_array($value)) {
            echo "$key: " . print_r($value, true) . "\n";
        } else {
            echo "$key: $value\n";
        }
    }
}

echo "\n==================================\n";
