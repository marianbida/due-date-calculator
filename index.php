<?php

require_once 'DueDateCalculator.php';

$calc = new DueDateCalculator();

$duedate = $calc->calculateDueDate('2015-12-15 14:12:00', 16);

echo '<p>2015-12-15 14:12:00 -> ' . $duedate . '</p>';
