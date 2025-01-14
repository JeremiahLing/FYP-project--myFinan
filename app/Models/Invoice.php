<?php

// app/Models/Invoice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // Find the highest current invoice number
            $latestInvoice = Invoice::orderBy('id', 'desc')->first();

            // Determine the new invoice number
            $nextNumber = $latestInvoice ? intval(substr($latestInvoice->invoice_number, 4)) + 1 : 1;

            // Set the new invoice number
            $invoice->invoice_number = 'INV ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
    
    protected $fillable = [
        'invoice_number',
        'client_name',
        'client_email',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}