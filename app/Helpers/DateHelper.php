<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function getBengaliDate(?Carbon $date = null): string
    {
        $date = $date ?? Carbon::now();
        
        $dayName = $date->format('l');
        $dayNum = $date->format('d');
        $month = $date->format('F');
        $year = $date->format('Y');

        $bengaliDay = match($dayName) {
            'Sunday' => 'রবিবার',
            'Monday' => 'সোমবার',
            'Tuesday' => 'মঙ্গলবার',
            'Wednesday' => 'বুধবার',
            'Thursday' => 'বৃহস্পতিবার',
            'Friday' => 'শুক্রবার',
            'Saturday' => 'শনিবার',
            default => $dayName,
        };

        $bengaliMonth = match($month) {
            'January' => 'জানুয়ারি',
            'February' => 'ফেব্রুয়ারি',
            'March' => 'মার্চ',
            'April' => 'এপ্রিল',
            'May' => 'মে',
            'June' => 'জুন',
            'July' => 'জুলাই',
            'August' => 'আগস্ট',
            'September' => 'সেপ্টেম্বর',
            'October' => 'অক্টোবর',
            'November' => 'নভেম্বর',
            'December' => 'ডিসেম্বর',
            default => $month,
        };

        $day = self::convertToBengali($dayNum);
        $year = self::convertToBengali($year);

        return $bengaliDay . ', ' . $day . ' ' . $bengaliMonth . ' ' . $year;
    }

    private static function convertToBengali(string $number): string
    {
        $eng = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $ben = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return str_replace($eng, $ben, $number);
    }
}