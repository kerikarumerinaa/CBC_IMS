<?php

// next_page.php
require_once 'access.php';

// Assume you want to restrict access to this page to only main admins
if (!hasAccess($_SESSION['userRole'], 'main_admin')) {
    header("Location: access_denied.php");
    exit;
}

// Rest of the page content

?>