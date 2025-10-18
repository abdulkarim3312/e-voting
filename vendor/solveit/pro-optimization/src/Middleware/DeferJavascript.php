<?php

namespace Solveit\ProOptimization\Middleware;


class DeferJavascript extends ProOptimization
{
    public function apply($buffer)
    {
        $replace = [
            '/<script(?=[^>]+src[^>]+)((?![^>]+defer|data-ProOptimization-no-defer[^>]+)[^>]+)/i' => '<script $1',
        ];

        return $this->replace($replace, $buffer);
    }
}
