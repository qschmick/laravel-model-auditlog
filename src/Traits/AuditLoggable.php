<?php

namespace OrisIntel\AuditLog\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use OrisIntel\AuditLog\Observers\AuditLogObserver;

trait AuditLoggable
{
    /**
     * Boots the trait and sets the observer.
     */
    public static function bootAuditLoggable(): void
    {
        static::observe(AuditLogObserver::class);
    }

    /**
     * @return string
     */
    public function getAuditLogModelName(): string
    {
        return get_class($this) . config('model-auditlog.model_suffix');
    }

    /**
     * Gets an instance of the audit log for this model.
     *
     * @return mixed
     */
    public function getAuditLogModelInstance()
    {
        $class = $this->getAuditLogModelName();

        return new $class();
    }

    /**
     * @return string
     */
    public function getAuditLogTableName(): string
    {
        return $this->getTable() . config('model-auditlog.table_suffix');
    }

    /**
     * Get fields that should be ignored from the auditlog for this model.
     *
     * @return array
     */
    public function getAuditLogIgnoredFields(): array
    {
        return [];
    }

    /**
     * Get fields that should be used as keys on the auditlog for this model.
     *
     * @return array
     */
    public function getAuditLogForeignKeyColumns(): array
    {
        return ['subject_id' => $this->getKeyName()];
    }

    /**
     * Get the columns used in the foreign key on the audit log table.
     *
     * @return array
     */
    public function getAuditLogForeignKeyColumnKeys(): array
    {
        return array_keys($this->getAuditLogForeignKeyColumns());
    }

    /**
     * Get the columns used in the unique index on the model table.
     *
     * @return array
     */
    public function getAuditLogForeignKeyColumnValues(): array
    {
        return array_values($this->getAuditLogForeignKeyColumns());
    }

    /**
     * Get the audit logs for this model.
     *
     * @return HasMany|null
     */
    public function auditLogs(): ?HasMany
    {
        return $this->hasMany($this->getAuditLogModelName(), 'subject_id');
    }
}
