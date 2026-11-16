<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    // Defaults for every store-tunable value (BSD §settings).
    // The `___` placeholders are the store homework — the owner types the
    // real bank details into admin Settings on launch day. {name}, {no},
    // {total}, {type}, {eta} are filled in by the app when it composes
    // the WhatsApp messages.
    public function run(): void
    {
        $defaults = [
            'bank_name' => '___',
            'bank_account_number' => '___',
            'bank_account_name' => '___',
            'delivery_fee' => '1500', // placeholder — the store must confirm
            'pickup_eta' => 'Ready in ~15–20 min',
            'delivery_eta' => 'Arrives in ~30–45 min',
            'store_phones' => '0817 153 9888, 0907 982 1327, 0704 439 1134',
            'whatsapp_ready_pickup' => 'Hi {name}! 🧺 Your CitrusSplash order {no} is ready for pickup at Stick & Stone Plaza, Wuse. Total {total}. See you soon!',
            'whatsapp_ready_delivery' => 'Hi {name}! 🧺 Your CitrusSplash order {no} is on the way — a rider is bringing it to you. {eta}. Total {total}.',
            'auto_cancel_minutes' => '60',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
