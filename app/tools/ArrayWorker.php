<?php


namespace tools;


class ArrayWorker
{
    public static function wrapMassViaNames($names,$values){
        if(!isset($names) || !isset($values)) return null;

        $new_mass = array();

        $tmp_counter1 = 0;
        $tmp_counter2 = 0;
        foreach ($names as $name){
            foreach ($values as $value){

                if ($tmp_counter1 == $tmp_counter2){
                    $new_mass[$name] = $value;
                }
                $tmp_counter2++;
            }
            $tmp_counter1++;
            $tmp_counter2 = 0;
        }

        return $new_mass;
    }
}
