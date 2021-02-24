<?php

namespace DTApi\Repository;

use App\Observers\BookingObserver;
use App\Observers\BookingObserver;
use App\Observers\BookingObserver;
use App\Observers\BookingObserver;
use App\Services\BookingService;
use DTApi\Models\Job;

/**
 * Class BookingRepository
 * @package DTApi\Repository
 */

//Note:Only did the Refactoring in the controller file I will use the observers and service here to manage the this code.
class BookingRepository extends BaseRepository
{

    protected $model;
    protected $mailer;
    protected $logger;

    /**
     * @param Job $model
     */
    function __construct(Job $model, MailerInterface $mailer)
    {
        parent::__construct($model);
        $this->mailer = $mailer;
        $this->logger = new Logger('admin_logger');

        $this->logger->pushHandler(new StreamHandler(storage_path('logs/admin/laravel-' . date('Y-m-d') . '.log'), Logger::DEBUG));
        $this->logger->pushHandler(new FirePHPHandler());
    }

    /**
     * @param $user_id
     * @return array
     */
    public function getUsersJobs($user_id,BookingService $bookingservice)
    {
      return  $bookingservice->repogetuserjobs($user_id);
    }

    /**
     * @param $user_id
     * @return array
     */
    public function getUsersJobsHistory($user_id, Request $request,,BookingService $bookingservice)
    {
        return  $bookingservice->repogetuserjobs($user_id,$request);
    }

    /**
     * @param $user
     * @param $data
     * @return mixed
     */
    public function store($user, $data)
    {
       $user->created($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function storeJobEmail($job,$data)
    {
         $jobs->created($data);

    }

    /**
     * @param $job
     * @return array
     */
    public function jobToData($job,BookingService $bookingservice)
    {
        return  $bookingservice->repotodata($user_id,$request);  
    }

    /**
     * @param array $post_data
     */
    public function jobEnd(Job $Job)
    {
         $jobs->updated($data);
    }

    /**
     * Function to get all Potential jobs of user with his ID
     * @param $user_id
     * @return array
     */
    public function getPotentialJobIdsWithUserId($user_id,BookingService $bookingservice)
    {
        return  $bookingservice->repopublicjobid($user_id); 
    }

    /**
     * @param $job
     * @param array $data
     * @param $exclude_user_id
     */
    public function sendNotificationTranslator($job, $data = [], $exclude_user_id,BookingService $bookingservice)
    {
         return  $bookingservice->notificationtrans($job,$exclude_user_id,$data); 
    }

    /**
     * Sends SMS to translators and retuns count of translators
     * @param $job
     * @return int
     */
    public function sendSMSNotificationToTranslator($job,BookingService $bookingservice)
    {
        return  $bookingservice->reponotitrans($job);
    }

    /**
     * Function to delay the push
     * @param $user_id
     * @return bool
     */
    public function isNeedToDelayPush($user_id,BookingService $bookingservice)
    {
       return  $bookingservice->repodelaypush($user_id);
    }

   // Like that way i will make seprate services and observer and notification class to manage the code.