<?php
/**
 * @author Mahabubul Hasan <codehasan@gmail.com>
 * Date: 10/24/2017
 * Time: 6:08 PM
 */

namespace Uzzal\Crud;


trait Enum
{
    protected static $_values;

    /**
     *
     * @return array
     */
    public static function asArray() {
        if(!self::$_values){
            $oClass = new \ReflectionClass(__CLASS__);
            self::$_values = $oClass->getConstants();
        }
        return self::$_values;
    }

    /**
     *
     * @return array
     */
    public static function keys(){
        return array_keys(self::asArray());
    }

    /**
     *
     * @return array
     */
    public static function values(){
        return array_values(self::asArray());
    }

    /**
     *
     * @param string $key
     * @return string
     */
    public static function value($key){
        return self::asArray()[trim($key)];
    }
}