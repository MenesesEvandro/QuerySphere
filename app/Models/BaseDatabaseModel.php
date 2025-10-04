<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Interfaces\DatabaseModelInterface;
use App\Libraries\QueryLogger;

/**
 * Abstract base class for database models.
 *
 * This class provides common functionality, like the QueryLogger,
 * to all specific database models, reducing code duplication.
 *
 * @package App\Models
 */
abstract class BaseDatabaseModel extends Model implements DatabaseModelInterface
{
    /**
     * Instance of the QueryLogger.
     * @var QueryLogger
     */
    protected $queryLogger;

    /**
     * Constructor: initializes the QueryLogger.
     */
    public function __construct()
    {
        parent::__construct();
        $this->queryLogger = new QueryLogger();
    }

    // Methods from the interface that are implemented by child classes
    // will be enforced by the 'abstract' nature of this class.
}
