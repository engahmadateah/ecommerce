<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Indexes for the columns the shop and the admin filter and sort by most. */
return new class extends Migration
{
    private array $indexes = [
        'orders' => [['status'], ['paid_at'], ['user_id', 'status'], ['created_at']],
        'products' => [['is_published', 'created_at'], ['stock']],
        'reviews' => [['product_id']],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $sets) {
            foreach ($sets as $columns) {
                $this->attempt($table, fn (Blueprint $t) => $t->index($columns, $this->name($table, $columns)));
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $sets) {
            foreach ($sets as $columns) {
                $this->attempt($table, fn (Blueprint $t) => $t->dropIndex($this->name($table, $columns)));
            }
        }
    }

    private function name(string $table, array $columns): string
    {
        return 'perf_'.$table.'_'.implode('_', $columns);
    }

    /** An index that already exists (or a missing column) must not stop the migration. */
    private function attempt(string $table, callable $callback): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        try {
            Schema::table($table, $callback);
        } catch (Throwable $e) {
            // ignore
        }
    }
};
