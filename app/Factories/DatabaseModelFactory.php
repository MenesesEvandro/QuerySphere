<?php

namespace App\Factories;

use App\Interfaces\DatabaseModelInterface;
use App\Models\SqlServerModel;
use RuntimeException;

class DatabaseModelFactory
{
    /**
     * Creates a database model instance based on the given driver type.
     *
     * @param string|null $dbType The database driver type (e.g., 'sqlsrv').
     * @return DatabaseModelInterface
     */
    public static function create(
        ?string $dbType = null,
    ): DatabaseModelInterface {
        if ($dbType === null) {
            $dbType = session()->get('db_type');
        }

        switch ($dbType) {
            case 'sqlsrv':
                return new SqlServerModel();

            default:
                throw new RuntimeException(
                    "Unsupported database type: {$dbType}",
                );
        }
    }
}
