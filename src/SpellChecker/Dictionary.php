<?php declare(strict_types = 1);

namespace SpellChecker;

class Dictionary
{

    /** @var int[] */
    private $wordIndex;

    /** @var bool */
    private $checked;

    public function __construct(string $fileName, bool $checked = false)
    {
        if (!is_file($fileName) || !is_readable($fileName)) {
            throw new \SpellChecker\DictionaryFileNotReadableException($fileName);
        }

        foreach (explode("\n", file_get_contents($fileName)) as $word) {
            if ($word === '' || $word[0] === '#') {
                continue;
            }
            $this->wordIndex[$word] = 0;
        }

        $this->checked = $checked;
    }

    public function contains(string $word): bool
    {
        $found = isset($this->wordIndex[$word]);
        if ($found && $this->checked) {
            $this->wordIndex[$word]++;
        }

        return $found;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function getUnusedWords(): array
    {
        $words = [];
        foreach ($this->wordIndex as $word => $count) {
            if ($count < 1) {
                $words[] = $word;
            }
        }
        return $words;
    }

}
