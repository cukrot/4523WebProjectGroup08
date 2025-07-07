<?php
header('Content-Type: application/json');

$units = ['KG', 'PC', 'METER'];
echo json_encode($units);
?>