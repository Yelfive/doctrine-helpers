<?php

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 * @date 2018-05-08
 */

namespace fk\Doctrine\Query\AST\MySQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * ```php
 * // SELECT IFNULL(a, '')
 * $builder->select('IFNULL(a, \'\')')
 * ```
 * **Note that** if there's quotes in this function, it must be single quotes instead of double
 * @package fk\Doctrine\Query\AST\MySQL
 */
class IfNullFunction extends FunctionNode
{

    /**
     * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $this->params;
        return sprintf(
            'IFNULL(%s, %s)',
            $sqlWalker->walkArithmeticPrimary($this->params[0]),
            $sqlWalker->walkArithmeticPrimary($this->params[1])
        );
    }

    protected $params = [];

    /**
     * @param \Doctrine\ORM\Query\Parser $parser
     *
     * @return void
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        // ->match will check the current token and move the pointer to the next token
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->params[] = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->params[] = $parser->SimpleArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}