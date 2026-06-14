<?php
session_start();

function est_connecte() {
    return isset($_SESSION['role']);
}

function est_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function est_client() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'client';
}

function verifier_admin() {
    if (!est_admin()) {
        header('Location: login.php');
        exit;
    }
}

function verifier_client() {
    if (!est_client()) {
        header('Location: login.php');
        exit;
    }
}
?>
