<?php
require_once __DIR__ . '/../src/bootstrap.php';
requireUser(); // This triggers our check!
echo "If you can see this, you are logged in!";