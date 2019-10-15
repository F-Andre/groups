<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanBackup extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'db:cleanbackup';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Keep only last 10 files';

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
    $files = Storage::files('backups/');
    rsort($files);
    foreach ($files as $key => $file) {
      if ($key > 10) {
        Storage::delete($files[$key]);
      }      
    }
  }
}
