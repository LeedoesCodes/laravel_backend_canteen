<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GenerateErd extends Command
{
    protected $signature = 'erd:generate {--output=erd_output.md : Output file path}';
    protected $description = 'Generate a Mermaid ERD diagram from the database schema';

    // Known foreign key relationships (table => [column => referenced_table])
    protected array $foreignKeys = [
        'menu_items'      => ['category_id' => 'categories'],
        'orders'          => ['user_id' => 'users', 'cashier_id' => 'users'],
        'order_items'     => ['order_id' => 'orders', 'menu_item_id' => 'menu_items'],
        'inventory_logs'  => ['menu_item_id' => 'menu_items', 'created_by' => 'users'],
        'sessions'        => ['user_id' => 'users'],
    ];

    // System tables to skip
    protected array $skipTables = [
        'migrations',
        'failed_jobs',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'password_reset_tokens',
    ];

    public function handle(): int
    {
        $this->info('Generating ERD from database schema...');

        $tables = $this->getTables();

        if (empty($tables)) {
            $this->error('No tables found. Make sure your database is connected and migrated.');
            return Command::FAILURE;
        }

        $mermaid = $this->buildMermaid($tables);
        $output = $this->buildMarkdown($mermaid);

        $outputPath = base_path($this->option('output'));
        file_put_contents($outputPath, $output);

        $this->info("ERD generated at: {$outputPath}");
        $this->line('');
        $this->line('<fg=green>Tip:</> Open the file in VS Code with the Markdown Preview Mermaid Support extension.');

        return Command::SUCCESS;
    }

    protected function getTables(): array
    {
        $tables = Schema::getTables();
        $result = [];

        foreach ($tables as $table) {
            $tableName = is_array($table) ? $table['name'] : $table->name;

            if (in_array($tableName, $this->skipTables)) {
                continue;
            }

            $columns = Schema::getColumns($tableName);

            // Skip empty tables (likely from other schemas on same DB server)
            if (empty($columns)) {
                continue;
            }

            $result[$tableName] = $columns;
        }

        return $result;
    }

    protected function buildMermaid(array $tables): string
    {
        $lines = ['erDiagram'];

        // Build entity blocks
        foreach ($tables as $tableName => $columns) {
            $lines[] = "    {$tableName} {";
            foreach ($columns as $column) {
                $name    = is_array($column) ? $column['name'] : $column->name;
                $type    = is_array($column) ? $column['type_name'] : $column->type_name;
                $isPk    = $name === 'id';
                $isFk    = $this->isForeignKey($tableName, $name);
                $suffix  = $isPk ? ' PK' : ($isFk ? ' FK' : '');
                $lines[] = "        {$type} {$name}{$suffix}";
            }
            $lines[] = '    }';
            $lines[] = '';
        }

        // Build relationships
        foreach ($this->foreignKeys as $childTable => $fks) {
            if (!isset($tables[$childTable])) {
                continue;
            }
            foreach ($fks as $fkColumn => $parentTable) {
                if (!isset($tables[$parentTable])) {
                    continue;
                }
                // Check if column is nullable (optional relationship)
                $isNullable = $this->isNullable($tables[$childTable], $fkColumn);
                $childSide  = $isNullable ? '|o' : '||';
                $label      = str_replace('_id', '', $fkColumn);
                $lines[] = "    {$parentTable} {$childSide}--o{ {$childTable} : \"{$label}\"";
            }
        }

        return implode("\n", $lines);
    }

    protected function buildMarkdown(string $mermaid): string
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        return <<<MD
        # Singson Canteen — Entity Relationship Diagram

        > Auto-generated on {$timestamp} via `php artisan erd:generate`

        ```mermaid
        {$mermaid}
        ```

        ## How to View
        - **VS Code**: Install *Markdown Preview Mermaid Support* extension, then open this file and press `Ctrl+Shift+V`
        - **Online**: Paste the mermaid block into [mermaid.live](https://mermaid.live)
        - **GitHub**: Just push this file — GitHub renders Mermaid in Markdown automatically ✅
        MD;
    }

    protected function isForeignKey(string $table, string $column): bool
    {
        return isset($this->foreignKeys[$table][$column]);
    }

    protected function isNullable(array $columns, string $columnName): bool
    {
        foreach ($columns as $col) {
            $name = is_array($col) ? $col['name'] : $col->name;
            if ($name === $columnName) {
                return is_array($col) ? ($col['nullable'] ?? false) : $col->nullable;
            }
        }
        return false;
    }
}
