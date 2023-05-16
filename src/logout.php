<?php
session_start();

session_unset();
session_regenerate_id(true);
session_destroy();

header("Location: /index.php");

