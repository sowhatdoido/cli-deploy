<?php

use Humbug\SelfUpdate\Updater;

$updater = new Updater(null, false);
$updater->getStrategy()->setPharUrl('https://rawgit.com/sowhatdoido/pusheen/master/dist/pusheen.phar');
$updater->getStrategy()->setVersionUrl('https://rawgit.com/sowhatdoido/pusheen/master/version');
try {
    $result = $updater->update();
    if ($result) {
        $new = $updater->getNewVersion();
        $old = $updater->getOldVersion();
        exit("Updated from {$old} to {$new}");
    } else {
        exit("No update needed!");
    }
} catch (\Exception $e) {
    exit($e->getMessage());
}