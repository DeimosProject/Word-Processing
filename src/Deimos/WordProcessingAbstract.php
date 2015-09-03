<?php

namespace Deimos;

abstract
class WordProcessingAbstract
{

    public $frequencyLetters = array(
        'О' => 9.28, 'А' => 8.66, 'Е' => 8.24,
        'И' => 7.45, 'Н' => 6.35, 'Т' => 6.30,
        'Р' => 5.53, 'С' => 5.45, 'Л' => 4.32,
        'В' => 4.19, 'К' => 3.47, 'П' => 3.35,
        'М' => 3.29, 'У' => 2.90, 'Д' => 2.56,
        'Я' => 2.22, 'Ы' => 2.11, 'Ь' => 1.90,
        'З' => 1.81, 'Б' => 1.51, 'Г' => 1.41,
        'Й' => 1.31, 'Ч' => 1.27, 'Ю' => 1.03,
        'Х' => 0.92, 'Ж' => 0.78, 'Ш' => 0.77,
        'Ц' => 0.52, 'Щ' => 0.49, 'Ф' => 0.40,
        'Э' => 0.17, 'Ъ' => 0.04,

        'A' => 8.1, 'K' => 0.4, 'V' => 0.9,
        'B' => 1.4, 'L' => 3.4, 'W' => 1.5,
        'C' => 2.7, 'M' => 2.5, 'X' => 0.2,
        'D' => 3.9, 'N' => 7.2, 'Y' => 1.9,
        'E' => 13.0, 'O' => 7.9, 'Z' => 0.1,
        'F' => 2.9, 'P' => 2.0, 'G' => 2.0,
        'R' => 6.9, 'H' => 5.2, 'S' => 6.1,
        'I' => 6.5, 'T' => 10.5, 'J' => 0.2,
        'U' => 2.4, 'Q' => 0.2,

        'other' => 0.001
    );

    public function __construct()
    {
        if (mb_internal_encoding() != 'UTF-8') {
            mb_internal_encoding('UTF-8');
        }
    }

    /**
     * @param $words array
     * @return array
     */
    protected abstract function processing($words);

    /**
     * @param $arrayWeights array
     * @return array
     */
    public function getWordsCRC32($arrayWeights)
    {
        $crc32 = [];
        foreach ($arrayWeights as $row) {
            $crc32[] = $row->crc32;
        }
        return $crc32;
    }

    /**
     * @param $content
     * @return array
     */
    public function getArrayWeights($content)
    {

        $words = $this->getWorlds($content);

        $words = $this->processing($words);

        $_row = [];
        foreach ($words as $word) {
            if (isset($_row[$word])) {
                $_row[$word]->weight *= ++$_row[$word]->repeats;
            }
            else {
                $_row[$word] = new \stdClass();
                $_row[$word]->crc32 = crc32($word);
                $_row[$word]->repeats = 1;
                $_row[$word]->weight = 0;
                $_word = str_split($word);
                foreach ($_word as $char) {
                    if (isset($this->frequencyLetters[$char])) {
                        $_row[$word]->weight += $this->frequencyLetters[$char];
                    }
                    else {
                        $_row[$word]->weight += $this->frequencyLetters['other'];
                    }
                }
            }
        }

        return $_row;

    }

    /**
     * @param $content
     * @return array
     */
    private function getWorlds($content)
    {

        $content = htmlspecialchars_decode($content, ENT_QUOTES);
        $content = preg_replace('/[<]+/', ' <', $content);
        $content = preg_replace('/[>]+/', '> ', $content);
        $content = preg_replace('/[\s\t\n\r]+/', ' ', $content);
        $content = preg_replace('/&[\w]+[;]{0,1}/', ' ', $content);
        $content = strip_tags($content);

        $content = mb_strtoupper($content);
        $content = preg_replace('/Ё/u', 'Е', $content);

        preg_match_all("/([\w\d]+-[\w\d]+|[\w\d]+_[\w\d]+|[\w\d]+)/u", $content, $out);

        if (count($out)) {

            return current($out);

        }

        return [];

    }

}