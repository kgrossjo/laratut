<?php

namespace App;
use \Nadar\Stemming\Stemm;

class Helper {

    static function splitStringIntoWords($string)
    {
        return preg_split("/[ .,;:()!?-]+/", $string);
    }

    static function cleanWords($words)
    {
        return array_map(
            function ($w) {
                $w = preg_replace("/^['\"]+/", "", $w);
                $w = preg_replace("/['\"]+$/", "", $w);
                return $w;
            },
            $words
        );
    }

    static function removeEmptyWords($words)
    {
        return array_filter(
            $words,
            function ($w) {
                return ! empty($w);
            }
        );
    }

    static function lowercaseWords($words)
    {
        return array_map(
            function ($w) {
                return strtolower($w);
            },
            $words
        );
    }

    static function stemWords($words)
    {
        return array_map(
            function ($w) {
                return Stemm::stem($w, 'en');
            },
            $words
        );
    }

    static function splitStringIntoStems($s)
    {
        $words = Helper::splitStringIntoWords($s);
        // If the word begins or ends with a quote, remove the
        // quote.  (In this way, we will keep the apostrophe
        // in "don't", for example.)
        $clean_words = Helper::cleanWords($words);
        // Eliminate the empty string.
        $nonempty_words = Helper::removeEmptyWords($clean_words);
        // Lower-case
        $lower_words = Helper::lowercaseWords($nonempty_words);
        $stems = Helper::stemWords($lower_words);
        return $stems;
    }
}
