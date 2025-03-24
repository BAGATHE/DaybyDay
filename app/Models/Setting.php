<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'client_number',
        'invoice_number',
        'company',
        'country',
        'currency',
        'vat',
        'language',
        'remise_invoice'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->belongsTo(Task::class);
    }

    public static function setGlobalRemise(float $newRemise): bool
    {
        $settings = self::first();
        if ($settings) {
            return $settings->update(['remise_invoice' => $newRemise]);
        }
        return false;
    }

    public static function getGlobalRemise(): float
    {
        $settings = self::first();
        if ($settings) {
            return $settings->remise_invoice;
        }
        return false;
    }

}
