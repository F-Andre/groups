<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupS3 extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 's3:backup';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Backup files to s3 disk';

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
    $this->info('Démarrage de la sauvegarde des fichiers de "' . config('app.name') . '": ');
    $this->info('');

    try {
      if (Storage::disk('s3')->exists(preg_replace('/\s/', '_', config('app.name')))) {
        Storage::disk('s3')->deleteDirectory(preg_replace('/\s/', '_', config('app.name')));
      }
      foreach ($files as $key => $value) {
        if (preg_match_all('/(\/\.|^\.)/', $files[$key]) == 0) {
          $fileOk = Storage::get($files[$key]);
          Storage::disk('s3')->put(preg_replace('/\s/', '_', config('app.name')) . '/' . $files[$key], $fileOk);
        }
        $this->info('   ' . $files[$key]);
      }
      $this->info('');
      $this->info('Sauvegarde des fichiers de "' . config('app.name') . '" terminée');
    } catch (ProcessFailedException $exception) {
      $this->info('La sauvegarde des fichiers de "' . config('app.name') . '" n\'a pas fonctionnée. Erreur: ' . $exception);
    }
  }
}
