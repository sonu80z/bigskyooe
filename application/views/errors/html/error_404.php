<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Digital Surety Error</title>
    <link rel="shortcut icon" href="<?= base_url();?>public/dist/img/hico.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url() ?>public/custom/css/error.css">
</head>
<body>
<div class="er_head">
    <img src="<?= base_url()?>public/dist/img/full_logo.png" />
</div>
<div class="er_body">
    <div class="er_btlt">
        <?= $heading; ?>
    </div>
    <div class="er_bcnt">
        <?php
        if ( isset($_SESSION["is_admin_login"]) && $_SESSION["is_admin_login"]) {
            echo $message;
        } else {
            echo 'We are really sorry. There are a bit problems for security. <br>
                        Please contact with support team. <br>
                        Thank for your understand ';
        }
        ?>
    </div>
</div>
<div class="er_footer">
    (C) 2018 RIETSCHEL TECHNOLOGY
</div>
</body>
</html>