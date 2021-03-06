<?php

use Humbug\SelfUpdate\Updater;

$updater = new Updater(null, false);
$http = (phpversion() >= "5.6")? "https:" : "http:";

$updater->getStrategy()->setPharUrl("{$http}//raw.githubusercontent.com/sowhatdoido/pusheen/master/dist/pusheen.phar");
$updater->getStrategy()->setVersionUrl("{$http}//raw.githubusercontent.com/sowhatdoido/pusheen/master/version");
try {
    $result = $updater->update();
    if ($result) {
        $new = $updater->getNewVersion();
        $old = $updater->getOldVersion();
        exit("Updated from {$old} to {$new}" . PHP_EOL);
    } else {
        exit("No update needed!" . PHP_EOL);
    }
} catch (\Exception $e) {
    exit($e->getMessage() . PHP_EOL);
}