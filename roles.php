// roles.php

<?php
$roles = [
    'membership_admin' => ['membership', 'admin'],
    'assimilation' => ['assimilation'],
    'finance' => ['finance'],
    'main_admin' => ['membership', 'assimilation', 'finance', 'events']
];

// Function to check if a user has access to a certain role
function hasAccess($userRole, $requiredRole) {
    return in_array($requiredRole, $GLOBALS['roles'][$userRole]);
}
?>