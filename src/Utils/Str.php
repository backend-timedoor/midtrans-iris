<?php

namespace Timedoor\TmdMidtransIris\Utils;

class Str
{
    /**
     * Generate a random hex string of a given length
     *
     * @param   integer $length
     * @return  string
     */
    public static function random($length = 16)
    {
        $bytes = random_bytes($length);
        return substr(bin2hex($bytes), 0, $length);
    }
}