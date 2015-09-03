<?php

namespace Deimos;

class WordProcessingWithMorphy extends WordProcessingAbstract
{
    protected function processing($words)
    {
        $morphy = new phpMorphy();
        foreach ($words as &$word) {
            if (preg_match('/[а-я]+/ui', $word)) {
                $lang = 'ru';
            }
            else {
                $lang = 'en';
            }
            if (preg_match('/[-]/u', $word)) {
                $explode = explode("-", $word);
                $new_word = array_pop($explode);
                $_word = $morphy->get($lang)->getBaseForm($new_word);
            }
            else {
                $_word = $morphy->get($lang)->getBaseForm($word);
            }
            if ($_word) {
                if (is_array($_word)) {
                    $word = current($_word);
                }
                else {
                    $word = $_word;
                }
                if (isset($explode)) {
                    $explode = implode("-", $explode);
                    $word = $explode . "-" . $word;
                }
                unset($explode);
            }
        }
        return $words;
    }
}