<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\Member;

echo "=== Registered RFID UIDs ===\n";

$members = Member::whereNotNull('uid')->get(['id', 'full_name', 'uid', 'membership_status']);

if ($members->count() > 0) {
    foreach ($members as $member) {
        echo "ID: {$member->id}, Name: {$member->full_name}, UID: {$member->uid}, Status: {$member->membership_status}\n";
    }
} else {
    echo "No members with RFID UIDs found.\n";
}

echo "\n=== Current Card UID ===\n";
echo "Detected UID: E69F8F40\n";

echo "\n=== Recommendation ===\n";
if ($members->where('uid', 'E69F8F40')->count() > 0) {
    echo "Card UID E69F8F40 is already registered.\n";
} else {
    echo "Card UID E69F8F40 is NOT registered.\n";
    echo "You need to register this card to a member.\n";
}
?>
