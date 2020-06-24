<?php
header('Content-Type: application/x-yaml');
header('Content-Disposition: attachment; filename="'.$login.'.yml"');
echo "password_hash: $password_hash\n";