<?php declare(strict_types = 1);

use SpellChecker\DiacriticsHelper;

require_once('../src/SpellChecker/DiacriticsHelper.php');

$path = $argv[1];

// .dic -> .dia
$output = fopen(substr($path, 0, -1) . 'a', 'w');
$words = [];
foreach (explode("\n", file_get_contents($path)) as $word) {
    if ($word === '' || $word[0] === '#') {
        continue;
    }

    $stripped = DiacriticsHelper::removeDiacritics($word);
    if ($stripped !== $word) {
        $words[$stripped] = true;
    }
}

foreach ($words as $word => $foo) {
    fwrite($output, $word . "\n");
}
