<?php

return [
    // عطلة نهاية الأسبوع: الجمعة والسبت
    'weekend_days' => [Carbon\Carbon::FRIDAY, Carbon\Carbon::SATURDAY],

    // العطل الرسمية (تواريخ بصيغة Y-m-d)
    'holidays' => [
        // '2025-08-15',
    ],

    // نوافذ الزمن
    // 'check_in_open'      => '07:30:00',
    // 'check_in_deadline'  => '08:00:00',  // حتى هذا الوقت نعتبره في الموعد
    // 'check_in_last'      => '14:00:00',  // بعده مرفوض

    // 'check_out_open'     => '14:00:00',
    // 'check_out_deadline' => '14:30:00',  // بعده مرفوض
];
