<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Factories\DatabaseModelFactory;


/**
 * Controller responsible for managing SQL Server Agent jobs via the API.
 *
 * This class provides endpoints to list jobs, start and stop them,
 * and retrieve their execution history.
 *
 * @package App\Controllers\Api
 */
class Agent extends BaseController
{
    use ResponseTrait;

    private $model;

    public function __construct()
    {
        $this->model = DatabaseModelFactory::create();
    }

    /**
     * Retrieves a list of all SQL Server Agent jobs.
     */
    public function jobs()
    {
        $jobs = $this->model->getAgentJobs();
        return $this->respond($jobs);
    }

    /**
     * Starts a SQL Server Agent job.
     */
    public function startJob()
    {
        $jobName = $this->request->getPost('job_name');
        if (empty($jobName)) {
            return $this->fail('Job name not provided.', 400);
        }

        $result = $this->model->startAgentJob($jobName);
        if ($result['status'] === 'success') {
            return $this->respond(['message' => 'Job started successfully.']);
        }

        return $this->fail($result['message'], 500);
    }

    /**
     * Stops a SQL Server Agent job.
     */
    public function stopJob()
    {
        $jobName = $this->request->getPost('job_name');
        if (empty($jobName)) {
            return $this->fail('Job name not provided.', 400);
        }

        $result = $this->model->stopAgentJob($jobName);
        if ($result['status'] === 'success') {
            return $this->respond(['message' => 'Job stop request sent.']);
        }

        return $this->fail($result['message'], 500);
    }

    /**
     * Retrieves the history for a specific SQL Server Agent job.
     */
    public function jobHistory($jobName)
    {
        if (empty($jobName)) {
            return $this->fail('Job name not provided.', 400);
        }

        $history = $this->model->getAgentJobHistory(urldecode($jobName));
        return $this->respond($history);
    }
}
