<?php

namespace Vncore\Core\Commands;

use Illuminate\Console\Command;
use Throwable;
use DB;
use Illuminate\Support\Facades\Artisan;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vncore:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Vncore';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            Artisan::call('db:seed', 
                [
                    '--class' => '\Vncore\Core\DB\seeders\DataDefaultSeeder',
                    '--force' => true
                ]
            );
            Artisan::call('db:seed', 
                [
                    '--class' => '\Vncore\Core\DB\seeders\DataLocaleSeeder',
                    '--force' => true
                ]
            );
            $this->info('- Update database done!');
        } catch (Throwable $e) {
            vncore_report($e->getMessage());
            echo  json_encode(['error' => 1, 'msg' => $e->getMessage()]);
            exit();
        }
        try {
            Artisan::call('vncore:customize static');
            $this->info('- Update static file done!');
        } catch (Throwable $e) {
            vncore_report($e->getMessage());
            echo  json_encode(['error' => 1, 'msg' => $e->getMessage()]);
            exit();
        }
        $this->info('---------------------');
        $this->info('Front version: '.config('vncore.version'));
        $this->info('Front sub-version: '.config('vncore.sub-version'));
        $this->info('Core: '.config('vncore.core'));
        $this->info('Core sub-version: '.config('vncore.core-sub-version'));
    }
}
