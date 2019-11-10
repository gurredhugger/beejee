<?php
use app\core\app;
use app\core\base\Asset;

/** @var $content */

app::$assets
    ->add('css/bootstrap.min.css', ['priority' => 1])
    ->add('js/jquery.min.js', ['priority' => 1])
    ->add('js/bootstrap.min.js', ['priority' => 2]);
?>

<!doctype html>
<html lang="<?= app::$config['lang'] ?>">
<head>
    <meta charset="<?= app::$config['app_charset'] ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= app::$title ?></title>
    <?= app::$assets->get(Asset::HEADER)?>
</head>
<body>
    <?= $content ?>
    <?= app::$assets->get(Asset::FOOTER) ?>
</body>
</html>
