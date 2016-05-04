<?php    
    if(!empty($_branchConf['post-deploy']['url']) && ($curlUrl = $_branchConf['post-deploy']['url'])){
        if(!function_exists('curl_version')){
            Console::log("CURL not enabled.");
        } else {
            Console::log("Attempting Post Deploy URL({$curlUrl})...");
            if(!(Helper::hasOption($_GET, array("--dryrun", "-d")))){
                $payload = (!empty($_branchConf['post-deploy']['payload']))? $_branchConf['post-deploy']['payload'] : [];
                $curlResponse = Curl::post(
                    $curlUrl,
                    $payload
                );
                Console::log("Response: {$curlResponse}");
            } else {
                Console::log("Dry run will not generate a response!");
            }
        }
    }