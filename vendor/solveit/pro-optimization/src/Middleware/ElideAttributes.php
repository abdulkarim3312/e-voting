<?php

namespace Solveit\ProOptimization\Middleware;


class ElideAttributes extends ProOptimization
{
    public function apply($buffer)
    {
        $replace = [
            '/ method=("get"|get)/' => '',
            '/ disabled=[^ >]*(.*?)/' => ' disabled',
            '/ selected=[^ >]*(.*?)/' => ' selected',
        ];

        return $this->replace($replace, $buffer);
    }
}
