<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanStreamUrls extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'clean:streamurls';
    protected $description = 'Clean bad stream URL data (0 or empty strings) from tbl_livestreams.link column';
    protected $usage       = 'clean:streamurls [options]';
    protected $arguments   = [];
    protected $options     = [
        '--dry-run' => 'Show what would be cleaned without actually modifying data',
    ];

    public function run(array $params)
    {
        $db = \Config\Database::connect('default');
        $isDryRun = isset($params['dry-run']);

        CLI::write('Stream URL Data Cleanup', 'green');
        CLI::write(str_repeat('=', 50));

        // Check for bad data
        $builder = $db->table('tbl_livestreams');
        $builder->select('COUNT(*) as total');
        $badDataCount = $db->table('tbl_livestreams')
            ->where("link = '0' OR link = 0 OR link = ''")
            ->countAllResults();

        CLI::write('');
        CLI::write("Found {$badDataCount} records with bad stream URL data", 'yellow');
        CLI::write('');

        if ($badDataCount === 0) {
            CLI::write('✓ No bad data found. Database is clean!', 'green');
            return;
        }

        if ($isDryRun) {
            CLI::write('DRY RUN MODE - No changes will be made', 'cyan');
            CLI::write('');

            // Show what would be deleted
            $rows = $db->table('tbl_livestreams')
                ->where("link = '0' OR link = 0 OR link = ''")
                ->get()
                ->getResult();

            CLI::write('Records that would be updated:', 'cyan');
            foreach ($rows as $row) {
                CLI::write("  ID: {$row->id} | Title: {$row->title} | Current link: '{$row->link}'");
            }
            CLI::write('');
        } else {
            if (!$this->confirm('Continue with cleaning?')) {
                CLI::write('Cleanup cancelled.', 'yellow');
                return;
            }

            // Perform the cleanup
            $result = $db->table('tbl_livestreams')
                ->set('link', null)
                ->where("link = '0' OR link = 0 OR link = ''")
                ->update();

            CLI::write('');
            CLI::write("✓ Successfully cleaned {$badDataCount} records", 'green');
            CLI::write('  All problematic stream URLs have been set to NULL', 'green');
        }

        CLI::write('');
        CLI::write('Database cleanup complete!', 'green');
    }

    /**
     * Display a confirmation message and get yes/no response.
     */
    private function confirm(string $message): bool
    {
        return CLI::prompt($message, ['y', 'n']) === 'y';
    }
}
