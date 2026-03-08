<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">
    <?php if(isset($_SESSION["is_admin_login"])) { ?>
        <h4>An uncaught Exception was encountered</h4>

        <p>Type: <?php echo get_class($exception); ?></p>
        <p>Message: <?php echo $message; ?></p>
        <p>Filename: <?php echo $exception->getFile(); ?></p>
        <p>Line Number: <?php echo $exception->getLine(); ?></p>

        <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

            <p>Backtrace:</p>
            <?php foreach ($exception->getTrace() as $error): ?>

                <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

                    <p style="margin-left:10px">
                        File: <?php echo $error['file']; ?><br />
                        Line: <?php echo $error['line']; ?><br />
                        Function: <?php echo $error['function']; ?>
                    </p>
                <?php endif ?>

            <?php endforeach ?>

        <?php endif ?>
    <?php } else { ?>
        <div style="background: red; padding: 30px;">
            <img src="https://digitalsurety.ch/dev/public/dist/img/full_logo.png" style="height: 60px;" />
        </div>
        <div style="min-height: 600px;">
            <div style="width: 80%; margin: 30px auto 5px; border-bottom: 1px solid silver; padding: 5px; font-size: 24px; font-weight: bold;">
                An uncaught Exception was encountered
            </div>
            <div style="width: 80%; margin: 10px auto; border: 1px solid silver; border-radius: 10px; padding: 20px;">
                We are really sorry. There are a bit problems for security. <br>
                Please contact with support team. <br>
                Thank for your understand
            </div>
        </div>
        <div style="text-align: center; padding: 30px; font-size: 26px; color: white; background: gray;">
            (C) 2018 RIETSCHEL TECHNOLOGY
        </div>
    <?php } ?>
</div>