<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Factories\DatabaseModelFactory;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller responsible for managing MySQL Events via the API.
 */
class MySqlEvents extends BaseController
{
    use ResponseTrait;

    private $model;

    public function __construct()
    {
        // Use the factory to get the appropriate model
        $this->model = DatabaseModelFactory::create();
    }

    /**
     * Retrieves a list of all MySQL Events.
     */
    public function index()
    {
        if (method_exists($this->model, 'getEvents')) {
            $events = $this->model->getEvents();
            return $this->respond($events);
        }
        return $this->fail(
            'This feature is not supported for the connected database.',
            400,
        );
    }

    /**
     * Toggles the status of a MySQL Event.
     */
    public function toggleStatus()
    {
        $eventName = $this->request->getPost('event_name');
        $status = $this->request->getPost('status');

        if (empty($eventName) || empty($status)) {
            return $this->fail('Event name or status not provided.', 400);
        }

        if (method_exists($this->model, 'toggleEventStatus')) {
            $result = $this->model->toggleEventStatus($eventName, $status);
            if ($result['status'] === 'success') {
                return $this->respond([
                    'message' => 'Event status updated successfully.',
                ]);
            }
            return $this->fail($result['message'], 500);
        }

        return $this->fail(
            'This feature is not supported for the connected database.',
            400,
        );
    }

    /**
     * Retrieves the definition for a specific MySQL Event.
     */
    public function definition($eventName)
    {
        if (empty($eventName)) {
            return $this->fail('Event name not provided.', 400);
        }

        if (method_exists($this->model, 'getEventDefinition')) {
            $definition = $this->model->getEventDefinition(
                urldecode($eventName),
            );
            return $this->respond(['sql' => $definition]);
        }

        return $this->fail(
            'This feature is not supported for the connected database.',
            400,
        );
    }
}
