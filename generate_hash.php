<?php
// Tạo hash password cho admin@gmail.com và guide@gmail.com
echo "Hash cho admin@gmail.com: " . password_hash('admin@gmail.com', PASSWORD_DEFAULT) . "\n";
echo "Hash cho guide@gmail.com: " . password_hash('guide@gmail.com', PASSWORD_DEFAULT) . "\n";
?>