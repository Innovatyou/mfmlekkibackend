<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Helpers\StreamIdHelper;

/**
 * Verify and clean all stream/video ID fields in database
 * 
 * Usage:
 *   php spark verify:streams [--fix] [--report]
 */
class VerifyStreams extends BaseCommand
{
    protected $group       = 'Streams';
    protected $name        = 'verify:streams';
    protected $description = 'Verify and report on all stream IDs and their types. Use --fix to auto-correct, --report for detailed report.';
    protected $usage       = 'verify:streams [--fix] [--report] [--verbose]';
    protected $arguments   = [];
    protected $options     = [
        'fix'     => 'Automatically fix invalid/null stream IDs (convert to empty string)',
        'report'  => 'Generate detailed report of issues',
        'verbose' => 'Show all records, not just issues'
    ];

    public function run(array $params)
    {
        $fix     = $this->option('fix') ?? false;
        $report  = $this->option('report') ?? false;
        $verbose = $this->option('verbose') ?? false;

        CLI::write('=' . str_repeat('=', 79), 'cyan');
        CLI::write('  STREAM ID VERIFICATION & VALIDATION', 'cyan');
        CLI::write('=' . str_repeat('=', 79), 'cyan');
        CLI::newLine();

        $db = \Config\Database::connect('default');

        // Check tables that contain stream/video IDs
        $tables = [
            'tbl_media' => ['source', 'link'],
            'tbl_livestreams' => ['link'],
        ];

        $totalIssues = 0;
        $totalFixed = 0;

        foreach ($tables as $table => $fields) {
            if (!$db->tableExists($table)) {
                CLI::write("⚠️  Table '{$table}' does not exist. Skipping...", 'yellow');
                continue;
            }

            CLI::write("📊 Checking table: {$table}", 'green');
            CLI::newLine();

            foreach ($fields as $field) {
                $issues = $this->verifyField($db, $table, $field, $fix, $verbose);
                $totalIssues += count($issues);

                if ($fix && count($issues) > 0) {
                    $fixed = $this->fixIssues($db, $table, $field, $issues);
                    $totalFixed += $fixed;
                }
            }

            CLI::newLine();
        }

        // Summary
        CLI::write(str_repeat('=', 80), 'cyan');
        CLI::write('📋 VERIFICATION SUMMARY', 'cyan');
        CLI::write(str_repeat('=', 80), 'cyan');
        CLI::write("Total issues found: {$totalIssues}", $totalIssues > 0 ? 'red' : 'green');
        if ($fix) {
            CLI::write("Total issues fixed: {$totalFixed}", 'green');
        }
        CLI::newLine();

        if ($totalIssues === 0) {
            CLI::write('✅ All stream IDs are valid!', 'green');
        } else {
            if (!$fix) {
                CLI::write('Run with --fix flag to automatically correct issues:', 'yellow');
                CLI::write('  php spark verify:streams --fix', 'cyan');
            }
        }

        if ($report) {
            $this->generateDetailedReport($db, $tables);
        }

        CLI::newLine();
    }

    /**
     * Verify a single field in a table
     */
    private function verifyField($db, $table, $field, $fix = false, $verbose = false): array
    {
        $builder = $db->table($table);
        $records = $builder->select("id, {$field}")->get()->getResult();

        $issues = [];
        $valid = 0;
        $invalid = 0;

        foreach ($records as $record) {
            $value = $record->$field ?? null;
            $validation = StreamIdHelper::sanitizeStreamId($value);

            if (!$validation['is_valid'] && $validation['value'] !== '') {
                $issues[] = $record;
                $invalid++;
            } elseif ($validation['is_valid']) {
                $valid++;
            }
        }

        // Report
        $total = count($records);
        CLI::write("  Field: {$field}", 'blue');
        CLI::write("    Total records: {$total}", 'white');
        CLI::write("    ✅ Valid: {$valid}", 'green');
        CLI::write("    ❌ Invalid: {$invalid}", $invalid > 0 ? 'red' : 'green');

        if ($verbose && count($issues) > 0) {
            foreach ($issues as $record) {
                CLI::write("      → Record ID {$record->id}: {$record->$field}", 'yellow');
            }
        }

        return $issues;
    }

    /**
     * Fix issues in field
     */
    private function fixIssues($db, $table, $field, $issues): int
    {
        $fixed = 0;

        foreach ($issues as $record) {
            $db->table($table)
                ->where('id', $record->id)
                ->update([$field => '']);
            $fixed++;
        }

        CLI::write("    🔧 Fixed {$fixed} record(s)", 'green');
        return $fixed;
    }

    /**
     * Generate detailed validation report
     */
    private function generateDetailedReport($db, $tables): void
    {
        CLI::write(str_repeat('=', 80), 'cyan');
        CLI::write('📈 DETAILED VALIDATION REPORT', 'cyan');
        CLI::write(str_repeat('=', 80), 'cyan');
        CLI::newLine();

        foreach ($tables as $table => $fields) {
            if (!$db->tableExists($table)) {
                continue;
            }

            foreach ($fields as $field) {
                CLI::write("Table: {$table} | Field: {$field}", 'blue');
                
                $builder = $db->table($table);
                $records = $builder->select("id, {$field}")->get()->getResult();

                $report = StreamIdHelper::generateValidationReport($records);

                CLI::write("  {$report['summary']}", 'white');

                if (count($report['issues']) > 0) {
                    CLI::write('  Issues:', 'red');
                    foreach (array_slice($report['issues'], 0, 10) as $issue) {
                        CLI::write("    • Record {$issue['record_id']}: {$issue['reason']}", 'yellow');
                    }
                    if (count($report['issues']) > 10) {
                        CLI::write("    ... and " . (count($report['issues']) - 10) . " more", 'yellow');
                    }
                }

                CLI::newLine();
            }
        }
    }
}
