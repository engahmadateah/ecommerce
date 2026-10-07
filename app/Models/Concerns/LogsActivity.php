<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Records who changed what in the admin panel (an audit trail).
 * Only changes made by a logged-in admin are recorded, so customer activity
 * and automatic jobs (payments, stock) don't flood the log.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn (Model $m) => static::logActivity($m, 'created', $m->getAttributes()));
        static::deleted(fn (Model $m) => static::logActivity($m, 'deleted', []));
        static::updated(function (Model $m) {
            $changes = [];
            foreach ($m->getChanges() as $key => $new) {
                if ($key === 'updated_at') {
                    continue;
                }
                $changes[$key] = [$m->getOriginal($key), $new];
            }

            if ($changes !== []) {
                static::logActivity($m, 'updated', $changes, true);
            }
        });
    }

    private static function logActivity(Model $model, string $action, array $data, bool $pairs = false): void
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'admin') {
            return;
        }

        $clean = [];
        foreach ($data as $key => $value) {
            if (preg_match('/password|secret|token|key$/i', (string) $key) || $key === 'updated_at' || $key === 'created_at') {
                continue;
            }
            $clean[$key] = $pairs ? $value : [null, is_scalar($value) || $value === null ? $value : json_encode($value)];
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'subject_type' => class_basename($model),
            'subject_id' => $model->getKey(),
            'subject_label' => (string) ($model->name ?? $model->code ?? $model->site_name ?? '#'.$model->getKey()),
            'changes' => $clean ?: null,
        ]);
    }
}
