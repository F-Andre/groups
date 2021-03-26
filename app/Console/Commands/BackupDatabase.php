<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database backup';


    protected $process;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->process = sprintf(
            'mysqldump --user=%s --password=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('app/backups/database-backup-' . now()->timestamp . '.sql')
        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Storage::directories('backups/') ? '' : Storage::makeDirectory('backups');
        try {
            exec($this->process);
            $this->info('La sauvegarde de la bdd "' . config('database.connections.mysql.database') . '" a réussie - ' . Carbon::now());
        } catch (ProcessFailedException $exception) {
            $this->error('La sauvegarde de la bdd "' . config('database.connections.mysql.database') . '" a échouée - ' . Carbon::now());
        }
    }
}
