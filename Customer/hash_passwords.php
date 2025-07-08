<?php
   include 'config.php';

   $stmt = $pdo->query('SELECT cid, cpassword FROM customer');
   $customers = $stmt->fetchAll();

   foreach ($customers as $customer) {
       $hashed_password = password_hash($customer['cpassword'], PASSWORD_DEFAULT);
       $update_stmt = $pdo->prepare('UPDATE customer SET cpassword = ? WHERE cid = ?');
       $update_stmt->execute([$hashed_password, $customer['cid']]);
   }

   echo "Password has been successfully updated to hash value.";
   ?>