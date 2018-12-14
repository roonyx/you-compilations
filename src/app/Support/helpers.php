<?php
/**
 * File: helpers.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-12
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

if (!\function_exists('parseUrlInText')) {
    function parseUrlInText($str)
    {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        $urls = [];
        $urlsToReplace = [];
        
        if (\preg_match_all($reg_exUrl, $str, $urls)) {
            $numOfMatches = \count($urls[0]);
            for ($i = 0; $i < $numOfMatches; $i++) {
                $alreadyAdded = false;
                $numOfUrlsToReplace = \count($urlsToReplace);
                for ($j = 0; $j < $numOfUrlsToReplace; $j++) {
                    if ($urlsToReplace[$j] == $urls[0][$i]) {
                        $alreadyAdded = true;
                    }
                }
                if (!$alreadyAdded) {
                    \array_push($urlsToReplace, $urls[0][$i]);
                }
            }
            $numOfUrlsToReplace = \count($urlsToReplace);
            for ($i = 0; $i < $numOfUrlsToReplace; $i++) {
                $str = \str_replace($urlsToReplace[$i], '<a href="' . $urlsToReplace[$i] . '" target="_blank">' . $urlsToReplace[$i] . '</a> ', $str);
            }
            return $str;
        }

        return $str;
    }
}

if (!\function_exists('parseException')) {
    /**
     * @param \Exception $exception
     * @return string
     */
    function parseException($exception)
    {
        return 'File: ' . $exception->getFile() . '; Line: ' . $exception->getLine() . '; Message: ' . $exception->getMessage();
    }
}
