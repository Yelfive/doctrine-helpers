<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2018-05-16
 */

namespace fk\Doctrine\Query\AST\MySQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class GroupConcatFunction extends FunctionNode
{
    protected $is_distinct = false;
    protected $path_exp = null;
    protected $separator = null;
    protected $order_by = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(Lexer::T_DISTINCT)) {
            $parser->match(Lexer::T_DISTINCT);

            $this->is_distinct = true;
        }

        // first Path Expression is mandatory
        $this->path_exp = [];
        if ($lexer->isNextToken(Lexer::T_IDENTIFIER)) {
            $this->path_exp[] = $parser->StringExpression();
        } else {
            $this->path_exp[] = $parser->SingleValuedPathExpression();
        }

        while ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->path_exp[] = $parser->StringPrimary();
        }

        if ($lexer->isNextToken(Lexer::T_ORDER)) {
            $this->order_by = $parser->OrderByClause();
        }

        if ($lexer->isNextToken(Lexer::T_IDENTIFIER)) {
            if (strtolower($lexer->lookahead['value']) !== 'separator') {
                $parser->syntaxError('separator');
            }
            $parser->match(Lexer::T_IDENTIFIER);

            $this->separator = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $result = 'GROUP_CONCAT(' . ($this->is_distinct ? 'DISTINCT ' : '');

        $fields = [];
        foreach ($this->path_exp as $path) $fields[] = $path->dispatch($sqlWalker);

        $result .= sprintf('%s', implode(', ', $fields));

        if ($this->order_by) $result .= ' ' . $sqlWalker->walkOrderByClause($this->order_by);

        if ($this->separator) $result .= ' SEPARATOR ' . $sqlWalker->walkStringPrimary($this->separator);

        $result .= ')';

        return $result;
    }
}