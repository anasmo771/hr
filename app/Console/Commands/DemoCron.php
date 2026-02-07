<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Deprecated] هذا الأمر أُلغي بعد نقل منطق الاستحقاقات إلى العلاوات/التقدير. لا يقوم بأي إجراء.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // مُعطَّل: تمت إزالة كل منطق الترقية الزمنية (last_sett/سنوات خدمة).
        // إذا أردت لاحقًا استخدام الأمر لتنظيف/إحصاءات، اكتب المنطق هنا.
        $this->info('demo:cron معطَّل — لا يوجد شيء للتنفيذ.');
        return Command::SUCCESS;
    }
}
