<?php

namespace Vncore\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Throwable;

class Infomation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vncore:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get infomation Vncore';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->welcome();
        $this->info(config('vncore.name'));
        $this->info(config('vncore.auth').' <'.config('vncore.email').'>');
        $this->info('Front version: '.config('vncore.version'));
        $this->info('Front sub-version: '.config('vncore.sub-version'));
        $this->info('Core: '.config('vncore.core'));
        $this->info('Core sub-version: '.config('vncore.core-sub-version'));
        $this->info('Homepage: '.config('vncore.homepage'));
        $this->info('Github: '.config('vncore.github'));
        $this->info('Facebook: '.config('vncore.facebook'));
        $this->info('API: '.config('vncore-config.env.VNCORE_LIBRARY_API'));
    }

    private function welcome()
    {
        $text = "
        __      __     _____               
        \ \    / /    / ____|              
         \ \  / / __ | |     ___  _ __ ___ 
          \ \/ / '_ \| |    / _ \| '__/ _ \
           \  /| | | | |___| (_) | | |  __/
            \/ |_| |_|\_____\___/|_|  \___|
        ";

        $text .= "\n        Welcome to VnCore ".config('vncore.core-sub-version')."!";

        $terminalWidth = exec('tput cols');
        $terminalHeight = exec('tput lines');

        $lines = explode("\n", $text);
        $textHeight = count($lines);

        $paddingTop = max(0, ($terminalHeight - $textHeight) / 2);

        for ($i = 0; $i < $paddingTop; $i++) {
            $this->line('');
        }
        foreach ($lines as $line) {
            $this->line(str_pad($line, $terminalWidth, ' ', STR_PAD_BOTH));
        }
    }
}
