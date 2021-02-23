<?php

namespace DTApi\Http\Controllers;

use DTApi\Models\Job;
use DTApi\Http\Requests;
use DTApi\Models\Distance;
use Illuminate\Http\Request;
use DTApi\Repository\BookingRepository;
use App\Http\Requests\Bookingrequest;
use App\Http\Requests\Jobsemailrequest;
/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */

/*Note: Use the service for the logic,and i have use the $request->validated() to replace $request->all() for the security purpose*/
class BookingController extends Controller
{

    /**
     * @var BookingRepository
     */
    protected $repository;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request,BookingService $bookingservice)
    {
        //Logic should be in service
        return response($bookingservice->controllerindexlogic($request));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    { 
    //find give us the null if record not exists but findorfail give the error if record not exists
        $job = $this->repository->with('translatorJobRel.user')->firstOrFail($id);

        return response($job);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Bookingrequest $request)
    {
        $data =  $request->validated();
        $response = $this->repository->store($request->__authenticatedUser, $data);

        return response($response);

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Bookingrequest $request)
    {
        $data = $request->validated();
        $cuser = $request->__authenticatedUser;
        $response = $this->repository->updateJob($id, array_except($data, ['_token', 'submit']), $cuser);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Jobsemailrequest $request)
    {
        //$adminSenderEmail = config('app.adminemail'); 
        $data = $request->validated();
        $response = $this->repository->storeJobEmail($data);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request,BookingService $bookingservice)
    {
         return $bookingservice->controllerhistorylogic($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJob($data, $user);

        return response($response);
    }

    public function acceptJobWithId(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        $response = $this->repository->acceptJobWithId($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Canceljobs $request)
    {
        $data = $request->validated();
        $user = $request->__authenticatedUser;

        $response = $this->repository->cancelJobAjax($data, $user);

        return response($response);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Endjobs $request)
    {
        $data = $request->validated();
        $data = $request->all();
        $response = $this->repository->endJob($data);

        return response($response);

    }

    public function customerNotCall(Notcall $request)
    {
        $data = $request->validated();
        $response = $this->repository->customerNotCall($data);

        return response($response);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(potentialjobs $request)
    {
        $data = $request->validated();
        $user = $request->__authenticatedUser;
        $response = $this->repository->getPotentialJobs($user);

        return response($response);
    }

    public function distanceFeed(distancefeed $request)
    {
        $data = $request->validated();
          
        return $bookingservice->controllerdistancefeedlogic($data);
      
    }

    public function reopen(Reopen $request)
    {
        $data = $request->validated();
        $response = $this->repository->reopen($data);

        return response($response);
    }

    public function resendNotifications(resendnotification $request)
    {
        $data = $request->resendnotification();
        $job = $this->repository->findorfail($data['jobid']);
        $job_data = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $job_data, '*');

        return response(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Resendsms $request)
    {
        $data = $request->validated();
        $job = $this->repository->find($data['jobid']);
        $job_data = $this->repository->jobToData($job);

        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }

}
