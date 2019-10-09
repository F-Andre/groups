<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupFtp extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'ftp:backup';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Backup files to ftp server';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $files = Storage::allFiles('/');
    $barFile = $this->output->createProgressBar(count($files));
    $barFile->start();

    foreach ($files as $key => $value) {
      if (preg_match_all('/(\/\.|^\.)/', $files[$key]) == 0) {
        $fileOk = Storage::get($files[$key]);

        if (!Storage::disk('ftp')->exists('backups_groups/' . $files[$key], $fileOk)) {
          Storage::disk('ftp')->put('backups_groups/' . $files[$key], $fileOk);
        }
      }
      $barFile->advance();
    }

    $barFile->finish();
  }
}
