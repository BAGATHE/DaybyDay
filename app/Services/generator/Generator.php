<?php

namespace App\Services\generator;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Generator
{
    public static function generateRandomVat(): string
    {
        return 'BE' . rand(1000, 9999) . rand(1000, 9999);
    }

    public static function generateClientEmail(string $clientName): string
    {
        $cleanName = Str::slug($clientName, '');
        return "contact@{$cleanName}.com";
    }

    public static function generateClientNumber(): int
    {
        $datePart = date('Ymd');
        $uniquePart = random_int(1000, 9999);
        $clientNumber = (int)($datePart . $uniquePart);
        return $clientNumber;
    }
    public static function generatePhoneNumber(): string
    {
        $prefix = '0' . rand(1, 9); // 01 à 09
        $number = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        $formattedNumber = $prefix . ' ' . substr($number, 0, 2) . ' ' . substr($number, 2, 2) . ' ' . substr($number, 4, 2) . ' ' . substr($number, 6, 2);

        return $formattedNumber;
    }

    public static function generateRandomDate(): Carbon
    {
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 3, 31);

        $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);

        return Carbon::createFromTimestamp($randomTimestamp);
    }
}