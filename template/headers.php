<?php include "backend/database.php"; ?>
<?php include "backend/table.php"; ?>
<?php
if ($_SESSION["Logged_IN"] == 2) {
} else {
    header('Location: ' . $URL_PATH . '/account/login.php');
}
?>

<?php $database = new \SRTS\Admin\database(); ?>
<?php $database->connect(); ?>
<!-- App favicon -->
<link rel="shortcut icon" href="<?php echo $URL_PATH; ?>/assets/images/favicon.ico">
<!-- App css -->
<link href="<?php echo $URL_PATH; ?>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $URL_PATH; ?>/assets/css/core.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $URL_PATH; ?>/assets/css/components.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $URL_PATH; ?>/assets/css/icons.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $URL_PATH; ?>/assets/css/pages.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $URL_PATH; ?>/assets/css/menu.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $URL_PATH; ?>/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
      integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

<script src="<?php echo $URL_PATH; ?>/assets/js/modernizr.min.js"></script>
