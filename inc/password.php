<?php
// Password Generator inspired by: http://xkcd.com/936/
$wordsFile =  dirname(__FILE__) . '/../data/words.txt';
if (!file_exists($wordsFile)) {
    $source = fopen('https://raw.githubusercontent.com/first20hours/google-10000-english/master/20k.txt', 'r');
    $file = fopen($wordsFile, 'w');
    $i = 0;
    while ($i < 2000 && !feof($source)) {
        $word = strtolower(fgets($source));
        if (preg_match('/^[a-z]{2,7}[a-ce-rt-z]$/i', $word)) {
            fputs($file, $word);
            ++$i;
        }
    }
    fclose($source);
    fclose($file);
}
$words = preg_split("/\n/", file_get_contents($wordsFile), -1, PREG_SPLIT_NO_EMPTY);

function generate_password($length = 3) {
    global $words;
    return implode('-', array_map(function ($index) {
        global $words;
        return $words[$index];
    }, array_rand($words, $length)));
}
