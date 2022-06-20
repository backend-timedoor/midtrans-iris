<?php

namespace Timedoor\TmdMidtransIris\Utils;

class Dumper
{
    /**
     * Dump the result and stop the execution
     *
     * @param   mixed $any
     * @return  void
     */
    public static function dd($any)
    {
        var_dump($any);
        exit();
    }
}