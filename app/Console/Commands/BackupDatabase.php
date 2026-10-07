<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--keep=14 : How many days of backups to keep}';

    protected $description = 'Save a copy of the database in storage/app/backups and delete old copies';

    public function handle(): int
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        $config = config('database.connections.'.config('database.default'));
        $stamp = now()->format('Y-m-d_His');

        if (($config['driver'] ?? null) === 'sqlite') {
            $source = $config['database'];

            if ($source === ':memory:' || ! is_file($source)) {
                $this->error('The SQLite database file was not found.');

                return self::FAILURE;
            }

            File::copy($source, "$dir/db-$stamp.sqlite");
        } elseif (in_array($config['driver'] ?? null, ['mysql', 'mariadb'], true)) {
            $file = "$dir/db-$stamp.sql";
            $binary = env('MYSQLDUMP_PATH', 'mysqldump');

            $result = Process::env(['MYSQL_PWD' => (string) ($config['password'] ?? '')]) // keeps the password out of the process list
                ->run([
                    $binary, '--single-transaction', '--routines', '--no-tablespaces',
                    '-h', (string) $config['host'], '-P', (string) $config['port'],
                    '-u', (string) $config['username'],
                    '--result-file='.$file,
                    (string) $config['database'],
                ]);

            if ($result->failed()) {
                File::delete($file);
                $this->error('mysqldump failed: '.trim($result->errorOutput()));
                $this->line('On XAMPP set MYSQLDUMP_PATH in .env, e.g. MYSQLDUMP_PATH="C:\\xampp\\mysql\\bin\\mysqldump.exe"');

                return self::FAILURE;
            }
        } else {
            $this->error('Backups are supported for MySQL/MariaDB and SQLite only.');

            return self::FAILURE;
        }

        $deleted = 0;
        foreach (File::files($dir) as $old) {
            if ($old->getMTime() < now()->subDays((int) $this->option('keep'))->getTimestamp()) {
                File::delete($old->getPathname());
                $deleted++;
            }
        }

        $this->info("Backup saved in storage/app/backups (removed {$deleted} old file(s)).");

        return self::SUCCESS;
    }
}
