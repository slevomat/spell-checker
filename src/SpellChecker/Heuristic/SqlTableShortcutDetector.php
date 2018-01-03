<?php declare(strict_types = 1);

namespace SpellChecker\Heuristic;

use SpellChecker\Word;

class SqlTableShortcutDetector implements \SpellChecker\Heuristic\Heuristic
{

    /** @var string[] */
    private $prefixes = [
        // SQL
        'SELECT\\s',
        'FROM\\s',
        'JOIN\\s',
        'WHERE\\s',
        'GROUP BY\\s',
        'ORDER BY\\s',
        'AND\\s',
        'OR\\s',
        'COUNT[\\s(]',
        'SUM[\\s(]',
        'MIN[\\s(]',
        'MAX[\\s(]',
        'GROUP_CONCAT[\\s(]',
        'COALESCE[\\s(]',
        'IF[\\s(]',
        'IFNULL[\\s(]',
        'LEAST[\\s(]',
        // Doctrine etc.
        '->select\\(',
        '->addSelect\\(',
        '->distinct\\(',
        '->update\\(',
        '->delete\\(',
        '->indexBy\\(',
        '->set\\(',
        '->from\\(',
        '->join\\(',
        '->leftJoin\\(',
        '->innerJoin\\(',
        '->on\\(',
        '->where\\(',
        '->andWhere\\(',
        '->orWhere\\(',
        '->groupBy\\(',
        '->addGroupBy\\(',
        '->having\\(',
        '->andHaving\\(',
        '->orHaving\\(',
        '->orderBy\\(',
        '->addOrderBy\\(',
        '->addRootEntityFromClassMetadata\\(',
        '->applyMailStatisticsOrderSubqueryForFilter\\(',
    ];

    /** @var string */
    private $pattern;

    /**
     * Searches for signs, that the word is a table shortcut used in SQL FROM, JOIN
     * or previously used table shortcut used in WHERE, SELECT, HAVING, ON
     * @param \SpellChecker\Word $word
     * @param string $string
     * @param string[] $dictionaries
     * @return bool
     */
    public function check(Word $word, string &$string, array $dictionaries): bool
    {
        if ($this->pattern === null) {
            $this->pattern = sprintf('/(?:%s)(.*)$/', implode('|', $this->prefixes));
        }
        if ($word->block !== null) {
            return false;
        }
        if (!preg_match('/^[a-z][a-z0-9]{0,5}$/', $word->word)) {
            return false;
        }

        $row = substr($string, $word->rowStart, $word->rowEnd - $word->rowStart);
        if (preg_match($this->pattern, $row, $match)) {
            if (strpos($match[1], $word->word) !== false) {
                return true;
            }
        }

        return false;
    }

}
