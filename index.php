<?php
require_once __DIR__ . '/includes/bootstrap.php';

redirect(isset($_SESSION['user_id']) ? 'contacts.php' : 'login.php');
