<?php

namespace Entity;

class Search {

    private $expression = "";

    function getExpression() {
        return $this->expression;
    }

    /**
     * 
     * @param string $expression
     */
    function setExpression($expression) {
        $this->expression = $expression;
    }

    function __toString() {
        return $this->expression;
    }

}