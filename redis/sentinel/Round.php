<?php

//轮训

class Round
{
    static $lastIndex = 0;

    public function select($list)
    {
        $currentIndex = self::$lastIndex;//当前的index

        $value = $list[$currentIndex];
        if ($currentIndex + 1 > count($list) - 1) {
            self::$lastIndex = 0;

        } else {
            self::$lastIndex++;
        }
        return $value;
    }

}