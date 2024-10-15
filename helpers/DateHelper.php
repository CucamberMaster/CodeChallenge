<?php
namespace Helpers;

class DateHelper
{
    public static function format(string $dateString): string
    {
        $date = new \DateTime($dateString);
        return htmlspecialchars($date->format('F j, Y'));
    }
}
