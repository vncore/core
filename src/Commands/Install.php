<?php

namespace Vncore\Core\Commands;

use Illuminate\Console\Command;
use Throwable;
use DB;
use Illuminate\Support\Facades\Storage;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vncore:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vncore install';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Are you sure you want to install Vncore?')) {

            if (!$this->checkEnv()) {
                return Command::FAILURE;
            }
            if (!$this->checkVncoreInstalled()) {
                return Command::FAILURE;
            }

            $this->call('migrate');
            $this->info('---------------> Migrate default done!');

            \DB::connection(VNCORE_DB_CONNECTION)->table('migrations')->where('migration', '00_00_00_step1_create_tables_admin')->delete();
            $this->call('migrate', ['--path' => '/vendor/vncore/core/src/DB/migrations/00_00_00_step1_create_tables_admin.php']);
            $this->info('---------------> Migrate schema Vncore done!');

            $this->call('db:seed', ['--class' => '\Vncore\Core\DB\seeders\DataDefaultSeeder', '--force' => true]);
            $this->info('---------------> Seeding database Vncore default done!');
            $this->call('db:seed', ['--class' => '\Vncore\Core\DB\seeders\DataStoreSeeder', '--force' => true]);
            $this->info('---------------> Seeding database Vncore system done!');
            $this->call('db:seed', ['--class' => '\Vncore\Core\DB\seeders\DataLocaleSeeder', '--force' => true]);
            $this->info('---------------> Seeding database Vncore local done!');

            if (class_exists('\Vncore\Front\Commands\FrontInstall')) {
                $this->call('vncore:front-install');
            }

            $this->call('vendor:publish', ['--tag' => 'vncore:public-static']);
            $this->call('vendor:publish', ['--tag' => 'vncore:public-vendor']);
            $this->call('vendor:publish', ['--tag' => 'vncore:functions-except']);

            $this->call('storage:link');

            Storage::disk('local')->put('vncore-installed.txt', date('Y-m-d H:i:s'));

            $this->welcome();
        } else {
            $this->info('Installation canceled');
        }
    }

    private function welcome() {
        $text = "
        __      __     _____               
        \ \    / /    / ____|              
         \ \  / / __ | |     ___  _ __ ___ 
          \ \/ / '_ \| |    / _ \| '__/ _ \
           \  /| | | | |___| (_) | | |  __/
            \/ |_| |_|\_____\___/|_|  \___|
        ";

        $text .= "\n        Welcome to VnCore ".config('vncore.core-sub-version')."!";
        $text .= "\n        Admin path: yourdomain/".config('vncore-config.env.VNCORE_ADMIN_PREFIX')."";
        $text .= "\n        User/password: admin/admin";

        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $this->line($line);
        }

        return Command::SUCCESS;
    }

    private function checkEnv()
    {
        if (!file_exists(base_path() . "/.env")) {
            $this->fail("File .env not found");
            return false;
        } else if (!config('app.key')) {
            $this->fail("Not found APP_KEY in file .env");
            return false;
        }
        return true;
    }

    private function checkVncoreInstalled()
    {
        if (\Illuminate\Support\Facades\Storage::disk('local')->exists('vncore-installed.txt')) {
            $this->error("Vncore has been installed");
            $this->fail("If you want to reinstall, please delete the file vncore-installed.txt in the ".\Illuminate\Support\Facades\Storage::disk('local')->path('vncore-installed.txt'));
            return false;
        }
        return true;
    }

}
