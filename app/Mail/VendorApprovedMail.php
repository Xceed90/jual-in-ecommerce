<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendorName;

    public function __construct($vendorName)
    {
        $this->vendorName = $vendorName;
    }

    public function build()
    {
        return $this->subject('Selamat! Toko Anda di jual.in Telah Disetujui 🎉')
                    ->html('
                        <h2>Halo, '.$this->vendorName.'!</h2>
                        <p>Selamat, pendaftaran toko Anda (Vendor) telah <b>disetujui</b> oleh Super Admin kami.</p>
                        <p>Sekarang Anda sudah bisa login, mengunggah produk, dan mulai berjualan di <b>jual.in</b>.</p>
                        <br>
                        <p>Salam Sukses,<br><b>Tim Super Admin jual.in</b></p>
                    ');
    }
}