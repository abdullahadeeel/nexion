<?php

namespace Phpify\Core\Console\Commands;

use Phpify\Core\Console\Command;
use Phpify\Core\Database\Database;

class MigrateCommand extends Command
{
    protected string $name = 'migrate';
    protected string $description = 'Run the database migrations';

    public function execute(array $args): int
    {
        $this->info("Running migrations...");

        $config = [
            'host' => env('DB_HOST', '127.0.0.1'),
            'dbname' => env('DB_DATABASE', 'phpify'),
            'user' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', '')
        ];

        try {
            $db = Database::connect($config);
            
            // Create migrations table if it doesn't exist
            $db->exec("CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;");

            $migrationFiles = glob(dirname(dirname(dirname(__DIR__))) . '/database/migrations/*.sql');
            
            if (empty($migrationFiles)) {
                $this->comment("No migrations found.");
                return 0;
            }

            // Get already executed migrations
            $executed = $db->query("SELECT migration FROM migrations")->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($migrationFiles as $file) {
                $filename = basename($file);
                if (in_array($filename, $executed)) {
                    continue;
                }

                $this->comment("Migrating: $filename");
                $sql = file_get_contents($file);
                $db->exec($sql);
                
                $stmt = $db->prepare("INSERT INTO migrations (migration) VALUES (?)");
                $stmt->execute([$filename]);
                
                $this->info("Migrated:  $filename");
            }

            $this->info("Migrations completed successfully.");
            return 0;

        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
            return 1;
        }
    }
}
