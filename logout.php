<?php
session_start();
session_destroy();
header('Location: loginandregister/login.php');
exit;