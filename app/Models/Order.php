<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'state',
        'postal_code',
        'subtotal',
        'shipping_fee',
        'discount_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'tracking_number',
        'courier_name',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'processing' => 'bg-blue-100 text-blue-800 border-blue-300',
            'shipped' => 'bg-purple-100 text-purple-800 border-purple-300',
            'delivered' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            'returned' => 'bg-orange-100 text-orange-800 border-orange-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'failed' => 'bg-rose-100 text-rose-800 border-rose-300',
            'refunded' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            default => 'bg-amber-100 text-amber-800 border-amber-300',
        };
    }

    /**
     * Convert the grand total into Indian currency text format (e.g. Rupees Five Hundred Only).
     */
    public function getAmountInWordsAttribute(): string
    {
        $number = (int) round($this->total_amount);
        if ($number <= 0) {
            return 'Rupees Zero Only';
        }

        $words = [
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
            6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
            11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
            30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];

        $convert = function ($n) use (&$convert, $words) {
            if ($n < 20) {
                return $words[$n];
            } elseif ($n < 100) {
                return $words[10 * (int)($n / 10)] . ($n % 10 ? ' ' . $words[$n % 10] : '');
            } elseif ($n < 1000) {
                return $words[(int)($n / 100)] . ' Hundred' . ($n % 100 ? ' and ' . $convert($n % 100) : '');
            } elseif ($n < 100000) {
                return $convert((int)($n / 1000)) . ' Thousand' . ($n % 1000 ? ' ' . $convert($n % 1000) : '');
            } elseif ($n < 10000000) {
                return $convert((int)($n / 100000)) . ' Lakh' . ($n % 100000 ? ' ' . $convert($n % 100000) : '');
            } else {
                return $convert((int)($n / 10000000)) . ' Crore' . ($n % 10000000 ? ' ' . $convert($n % 10000000) : '');
            }
        };

        return 'Rupees ' . trim($convert($number)) . ' Only';
    }
}
