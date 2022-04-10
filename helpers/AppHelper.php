<?php
// Некоторые вспомогательные функции

namespace app\helpers;

use app\data\Constants;

class AppHelper
{    
    // Вычисление возраста из даты
    public static function calculateAge($birthday = '01.01.1970', $showText = false)
    {
        $isMonth = false;
        $birthdayTimestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthdayTimestamp);
        
        if (date('md', $birthdayTimestamp) > date('md')) {
            $age--;            
        }
        
        if ($age < 1) {
            $age = date('m') - date('m', $birthdayTimestamp);
            $isMonth = true;
            
            if ($age < 1) {
                return 'Новорожденный';
            }
        } 
                
        if ($showText) {      
            $match = ($isMonth) ? ['месяц', 'месяца', 'месяцев'] : ['год', 'года', 'лет'];
            $age = self::declension($age, $match[0], $match[1], $match[2]);
        }
        
        return $age;
    }
    
    // Вычисление возраста из даты (в формате месяцев - до двух лет)
    public static function calculateMedicalAge($birthday = '01.01.1970')
    {
        $birthdayTimestamp = strtotime($birthday);
        $year = date('Y') - date('Y', $birthdayTimestamp);
        if (date('md', $birthdayTimestamp) > date('md')) {
            $year--;
        }
        $age = ($year * 12) + date('m') - date('m', $birthdayTimestamp);
        
        if ($age <= 24) {
            return ($age < 1) ? 'Новорожденный' : self::declension($age, 'месяц', 'месяца', 'месяцев');
        } else {            
            return self::declension($year, 'год', 'года', 'лет');
        }
    }
    
    // Склонение слов в зависимости от числа
    public static function declension($n, $form1, $form2, $form3, $with_number = true)
    {
        $num = abs($n) % 100;
        $n1 = $num % 10;
        $str = '';
        
        if ($with_number) {
            $str = $n . ' ';
        }
        if ($num > 10 && $num < 20) return $str . $form3;
        if ($n1 > 1 && $n1 < 5) return $str . $form2;
        if ($n1 == 1) return $str . $form1;
        
        return $str . $form3;
    }
    
    // Генерация рандомного HEX-цвета
    public static function generateHex($key = null)
    {
        if ($key === null) {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));            
        } else {
            $colors = Constants::getColors();             
            return ($key > count($colors)-1) ? self::generateHex() : $colors[$key];
        }
    }     
    
    // Получение имени БД
    public static function getDBName($dsn)
    {
        return (preg_match('/dbname=([^;]*)/', $dsn, $match)) ? $match[1] : null;
    }
    
    // ФИО как массив
    public static function getFullNameAsArray($fullname)
    {
        $arr = [];
        $data = explode(' ', $fullname);        
        
        foreach ($data as $key=>$value) {
            $value = trim($value);
            
            switch ($key) {
                case 0:
                    $arr['f'] = $value;
                    break;
                case 1:
                    $arr['i'] = $value;
                    break;
                case 2:
                    $arr['o'] = $value;
                    break;
            }
        }
        
        return $arr;
    }

    // Приведение номера телефона к единому стандарту
    public static function normalizePhone($phone)
    {
        $resPhone = preg_replace("/[^0-9]/", "", $phone);
        
        if (strlen($resPhone) > 11) {
            $resPhone = mb_substr($resPhone, 0, 13);
        }

        if (strlen($resPhone) === 11) {
            $resPhone = preg_replace("/^8/", "7", $resPhone);
        }
        
        if (strlen($resPhone) === 10) {
            $resPhone = "7" . $resPhone;
        }
        
        return '+' . $resPhone;
    }
    
    // Локализация номера телефона
    public static function localizePhone($phone)
    {
        if (substr($phone, 0, 1) === '+') {
            return '+' . substr($phone, 1, 1) . '-' . substr($phone, 2, 3) . '-' . substr($phone, 5, 3) . '-' . substr($phone, 8);
        } else {
            return '+' . substr($phone, 0, 1) . '-' . substr($phone, 1, 3) . '-' . substr($phone, 4, 3) . '-' . substr($phone, 7);
        }
    }        

    // Сокращение ФИО (например Иванов И.И.)
    public static function shortFullname($fullname)
    {
        $data = explode(' ', $fullname);
        $result = '';       
        
        foreach ($data as $key=>$value) {
            $result .= ($key == 0) ? $value . ' ' : mb_substr($value, 0, 1) . '. ';
        }
        
        return trim($result);
    }    
    
    // Анониматор ФИО (например Иван Иванович И.)
    public static function secretFullname($fullname)
    {
        $data = explode(' ', $fullname);        
        $el = $data[0];
        
        $result = implode(' ', [
            str_replace($el, '', $fullname),
            mb_substr($el, 0, 1) . '.'
        ]);
        
        return trim($result);
    } 
    
    // Первый символ заглавный (для utf-8)
    public static function ucfirst($string, $encoding = 'utf-8')
    {
        $trim = trim($string);
        
        return mb_strtoupper(mb_substr($trim, 0, 1), $encoding) . mb_substr($trim, 1);
    }        
}