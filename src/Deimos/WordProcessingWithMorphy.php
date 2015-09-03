<?php

namespace Deimos;

class WordProcessingWithMorphy extends WordProcessingAbstract
{
    protected function processing($words)
    {
        $morphy = new phpMorphy();
        foreach ($words as &$word) {
            if (preg_match('/[а-я]+/u', $word)) {
                $lang = 'ru';
            }
            else {
                $lang = 'en';
            }
            $_word = $morphy->get($lang)->getBaseForm($word);
            if ($_word) {
                $word = $_word;
            }
        }
        return $words;
    }
}