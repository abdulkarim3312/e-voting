<?php

namespace Solveit\ProOptimization\Middleware;

class TrimUrls extends ProOptimization
{
    public function apply($buffer)
    {
        $replace = [
            '/https:/' => '',
            '/http:/' => ''
        ];

        return $this->replace($replace, $buffer);
    }
}
