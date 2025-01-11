<?php

class Protect
{
    public function secureStr($string)
    {
        return htmlspecialchars(trim($string), ENT_QUOTES);
    }
}