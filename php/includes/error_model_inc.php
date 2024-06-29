<?php
function redirectWithError(string $error_message): void
{
    header("Location: ../pages/error_page.php?error=" . urlencode($error_message));
    exit;
}
