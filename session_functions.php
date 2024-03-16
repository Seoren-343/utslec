<?php
session_start();
function unset_session() {
    $_SESSION = array();
}
function destroy_session() {
    session_unset();
    session_destroy();
}
function logout() {
    unset_session();
    destroy_session();
    header("Location: index.php");
    exit();
}
?>