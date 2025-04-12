<?php
session_start();
session_unset();
session_destroy();

header('Location: Chapter4/main-page/mainPage.php');
exit;
