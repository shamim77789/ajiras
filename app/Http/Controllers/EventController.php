<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;
use App\Helpers\Infobip;
use App\Helpers\RouteSms;
use App\Models\Asset;
use App\Models\AssetType;
use App\Models\ContributionBatch;
use App\Models\AssetValuation;
use App\Models\Borrower;
use App\Models\Branch;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\ContributionType;
use App\Models\Fund;
use App\Models\Email;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventCalendar;
use App\Models\EventLocation;
use App\Models\EventPayment;
use App\Models\EventVolunteer;
use App\Models\Member;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Sms;
use App\Models\User;
use App\Models\VolunteerRole;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Api\ClickatellHttp;
use Clickatell\Rest;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use App\Models\Group;
class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('sentinel');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Sentinel::hasAccess('events')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $locations = array();
        foreach (EventLocation::all() as $key) {
            $locations[$key->id] = $key->name;
        }
        $calendars = array();
        foreach (EventCalendar::all() as $key) {
            $calendars[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
        //build event data
        $events = [];
        foreach (Event::all() as $event) {
            //determine color
            if (!empty($event->calendar)) {
                $color = $event->calendar->color;
            } else {
                $color = "#283593";
            }
            if ($event->all_day == 1) {
                array_push($events, array(
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'color' => $color,
                    'type' => 'event',
                    'url' => url('event/' . $event->id . '/show')
                ));
            } else {
                array_push($events, array(
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start_date . 'T' . $event->start_time,
                    'end' => $event->end_date . 'T' . $event->end_time,
                    'color' => $color,
                    'type' => 'event',
                    'url' => url('event/' . $event->id . '/show')
                ));
            }

        }
        $events = json_encode($events);
		$contribution_type = ContributionType::get();
		$funds = Fund::get();
		$batches = ContributionBatch::select('id','name')->get();
			
        return view('event.data', compact('events', 'calendars', 'locations','branches','contribution_type','funds','batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $locations = array();
        foreach (EventLocation::all() as $key) {
            $locations[$key->id] = $key->name;
        }
        $calendars = array();
        foreach (EventCalendar::all() as $key) {
            $calendars[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
		$batches = ContributionBatch::select('id','name')->get();
		$groups = Group::select('id','group_name')->get();
		
		//get custom fields
        $custom_fields = CustomField::where('category', 'events')->get();
        return view('event.create', compact('branches', 'custom_fields','batches','groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $event = new Event();
        $event->user_id = Sentinel::getUser()->id;
        $event->branch_id = $request->branch_id;

        $event->event_location_id = $request->event_location_id;
        $event->event_calendar_id = $request->event_calendar_id;
        $event->cost = $request->cost;
        $event->name = $request->name;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->notes = $request->notes;
        $event->latitude = $request->latitude;
        $event->longitude = $request->longitude;
		$event->contribution_type = $request->contribution_type;
		$event->fund = $request->fund;
		$event->batches = $request->batches;
        if (!empty($request->all_day)) {
            $event->all_day = $request->all_day;
        } else {
            $event->all_day = 0;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
        }
        if (!empty($request->recurring == 1)) {
            $event->recurring = $request->recurring;
            $event->recur_frequency = $request->recur_frequency;
            $event->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $event->recur_end_date = $request->recur_end_date;
            }

            $event->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                'Y-m-d');

            $event->recur_type = $request->recur_type;
        } else {
            $event->recurring = 0;
        }
        if ($request->hasFile('featured_image')) {
            $file = array('featured_image' => Input::file('featured_image'));
            $rules = array('featured_image' => 'required|mimes:jpeg,jpg,bmp,png');
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $event->featured_image = $request->file('featured_image')->getClientOriginalName();
                $request->file('featured_image')->move(public_path() . '/uploads',
                    $request->file('featured_image')->getClientOriginalName());
            }

        }
        /* $files = array();
         if (!empty($request->file('files'))) {
             $count = 0;
             foreach ($request->file('files') as $key) {
                 $file = array('files' => $key);
                 $rules = array('files' => 'required|mimes:jpeg,jpg,bmp,png,pdf,docx,xlsx');
                 $validator = Validator::make($file, $rules);
                 if ($validator->fails()) {
                     Flash::warning(trans('general.validation_error'));
                     return redirect()->back()->withInput()->withErrors($validator);
                 } else {
                     $files[$count] = $key->getClientOriginalName();
                     $key->move(public_path() . '/uploads',
                         $key->getClientOriginalName());
                 }
                 $count++;
             }
         }*/
        $event->files = serialize([]);
        $event->gallery = serialize([]);
        //files
        $event->save();
        GeneralHelper::audit_trail("Added event  with id:" . $event->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/data');
    }


    public function show($event)
    {
        if (!Sentinel::hasAccess('events.view')) 
		{
            Flash::warning("Permission Denied");
            return redirect('/');
        }
		
		$batch = ContributionBatch::where('id', $event->batches)->select('name')->first();
		
		//get custom fields
        $custom_fields = CustomField::where('category', 'events')->get();
        return view('event.show', compact('event', 'custom_fields','batch'));
    }

    public function attender($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $checked = array();
        foreach (EventAttendance::where('event_id', $event->id)->get() as $key) {
            array_push($checked, $key->member_id);
        }
        $members = array();
        $members["-1"] = trans_choice('general.check_as_anonymous', 1);
        foreach (Member::all() as $key) {
            if (!in_array($key->id, $checked)) {
                $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
            }
        }
        return view('event.attender', compact('event', 'members'));
    }

    public function volunteer($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $volunteers = array();
        foreach (EventVolunteer::where('event_id', $event->id)->get() as $key) {
            array_push($volunteers, $key->member_id);
        }
        $members = array();
        foreach (Member::all() as $key) {
            if (!in_array($key->id, $volunteers)) {
                $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
            }
        }
        $roles = array();
        foreach (VolunteerRole::all() as $key) {
            $roles[$key->id] = $key->name;
        }
        return view('event.volunteer', compact('event', 'members', 'roles'));
    }

    public function check_in($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $checked = array();
        foreach (EventAttendance::where('event_id', $event->id)->get() as $key) {
            array_push($checked, $key->member_id);
        }
        $members = array();
        $members["0"] = trans_choice('general.check_as_anonymous', 1);
        foreach (Member::all() as $key) {
            if (!in_array($key->id, $checked)) {
                $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
            }
        }
        return view('event.check_in', compact('event', 'members'));
    }

    public function report($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $male = 0;
        $female = 0;
        $unassigned_gender = 0;
        $under6 = 0;
        $six12 = 0;
        $thirteen18 = 0;
        $nineteen29 = 0;
        $thirty49 = 0;
        $fifty64 = 0;
        $sixty_five79 = 0;
        $eight_plus = 0;
        $unassigned_age = 0;
        $single = 0;
        $married = 0;
        $engaged = 0;
        $separated = 0;
        $widowed = 0;
        $divorced = 0;
        $unassigned_marital_status = 0;
        $attender = 0;
        $visitor = 0;
        $member = 0;
        $inactive = 0;

        $unassigned_status = 0;
        foreach ($event->attenders as $key) {
            if (!empty($key->member)) {
                if ($key->member->gender == 'male') {
                    $male = $male + 1;
                }
                if ($key->member->gender == 'female') {
                    $female = $female + 1;
                }
                if ($key->member->gender == 'unknown') {
                    $unassigned_gender = $unassigned_gender + 1;
                }
                if ($key->member->status == 'unknown') {
                    $unassigned_status = $unassigned_status + 1;
                }
                if ($key->member->status == 'attender') {
                    $attender = $attender + 1;
                }
                if ($key->member->status == 'visitor') {
                    $visitor = $visitor + 1;
                }
                if ($key->member->status == 'member') {
                    $member = $member + 1;
                }
                if ($key->member->status == 'inactive') {
                    $inactive = $inactive + 1;
                }
                if ($key->member->marital_status == 'unknown') {
                    $unassigned_marital_status = $unassigned_marital_status + 1;
                }
                if ($key->member->marital_status == 'single') {
                    $single = $single + 1;
                }
                if ($key->member->marital_status == 'engaged') {
                    $engaged = $engaged + 1;
                }
                if ($key->member->marital_status == 'married') {
                    $married = $married + 1;
                }
                if ($key->member->marital_status == 'divorced') {
                    $divorced = $divorced + 1;
                }
                if ($key->member->marital_status == 'widowed') {
                    $widowed = $widowed + 1;
                }
                if ($key->member->marital_status == 'separated') {
                    $separated = $separated + 1;
                }
                //determine age
                if (!empty($key->member->dob)) {
                    $age = date("Y-m-d") - $key->member->dob;
                    if ($age < 6) {
                        $under6 = $under6 + 1;
                    }
                    if ($age > 5 && $age < 13) {
                        $six12 = $six12 + 1;
                    }
                    if ($age > 12 && $age < 19) {
                        $thirteen18 = $thirteen18 + 1;
                    }
                    if ($age > 18 && $age < 30) {
                        $nineteen29 = $nineteen29 + 1;
                    }
                    if ($age > 29 && $age < 50) {
                        $thirty49 = $thirty49 + 1;
                    }
                    if ($age > 49 && $age < 65) {
                        $fifty64 = $fifty64 + 1;
                    }
                    if ($age > 64 && $age < 80) {
                        $sixty_five79 = $sixty_five79 + 1;
                    }
                    if ($age > 79) {
                        $eight_plus = $eight_plus + 1;
                    }
                } else {
                    $unassigned_age = $unassigned_age + 1;
                }
            } else {
                $unassigned_gender = $unassigned_gender + 1;
                $unassigned_age = $unassigned_age + 1;
                $unassigned_marital_status = $unassigned_marital_status + 1;
                $unassigned_status = $unassigned_status + 1;
            }
        }
        $data = [
            "male" => $male,
            "female" => $female,
            "unassigned_gender" => $unassigned_gender,
            "under6" => $under6,
            "six12" => $six12,
            "thirteen18" => $thirteen18,
            "nineteen29" => $nineteen29,
            "thirty49" => $thirty49,
            "fifty64" => $fifty64,
            "sixty_five79" => $sixty_five79,
            "eight_plus" => $eight_plus,
            "unassigned_age" => $unassigned_age,
            "single" => $single,
            "married" => $married,
            "engaged" => $engaged,
            "separated" => $separated,
            "widowed" => $widowed,
            "divorced" => $divorced,
            "unassigned_marital_status" => $unassigned_marital_status,
            "attender" => $attender,
            "visitor" => $visitor,
            "member" => $member,
            "inactive" => $inactive,
            "unassigned_status" => $unassigned_status
        ];

        return view('event.report', compact('event', 'data'));
    }


    public function add_checkin(Request $request)
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $attendance = new EventAttendance();
        $attendance->event_id = $request->event_id;
        $attendance->user_id = Sentinel::getUser()->id;
        if ($request->member_id == "0") {
            $attendance->anonymous = 1;
        } else {
            $attendance->anonymous = 0;
            $attendance->member_id = $request->member_id;
        }
        $attendance->date = date("Y-m-d");
        $attendance->save();

        GeneralHelper::audit_trail("Added attendance to event  with id:" . $request->event_id);
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();

    }

    public function remove_checkin($id)
    {
        if (!Sentinel::hasAccess('events.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        EventAttendance::destroy($id);
        GeneralHelper::audit_trail("Removed attendance for event  with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }

    public function get_volunteer($volunteer)
    {
        $json = [];
        $json["notes"] = $volunteer->notes;
        $roles = [];
        if (count(unserialize($volunteer->roles)) > 0) {
            foreach (unserialize($volunteer->roles) as $role) {
                if (!empty(VolunteerRole::find($role))) {
                    $vrole = VolunteerRole::find($role);
                    $roles[$role] = $vrole->name;
                }
            }
        }
        $json["roles"] = $roles;
        return json_encode($json, JSON_UNESCAPED_SLASHES);
    }

    public function volunteer_detail($volunteer)
    {

        return View::make('event.volunteer_detail', compact('volunteer'))->render();
    }

    public function add_volunteer(Request $request)
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $volunteer = new EventVolunteer();
        $volunteer->user_id = Sentinel::getUser()->id;
        $volunteer->event_id = $request->event_id;
        $volunteer->member_id = $request->member_id;
        $volunteer->notes = $request->notes;
        $roles = [];
        if (!empty($request->roles)) {
            $roles = serialize($request->roles);
        } else {
            $roles = serialize($roles);
        }
        $volunteer->roles = $roles;
        $volunteer->save();
        //notify member of assignment
        if (Setting::where('setting_key', 'email_volunteer_assignment')->first()->setting_value == 1) {
            $member = Member::find($request->member_id);
            $event = Event::find($request->event_id);
            if (!empty($member->email)) {
                $member_name = $member->first_name . ' ' . $member->middle_name . ' ' . $member->last_name;
                $event_name = $event->name;
                $event_description = $event->description;
                $event_dates = $event->start_date . '(' . $event->start_time . ')' . ' to ' . $event->end_date . '(' . $event->end_time . ')';
                $notes = $request->notes;
                $roles = "";
                if (!empty($request->roles)) {
                    foreach ($request->roles as $key) {
                        $r = VolunteerRole::find($key);
                        $roles .= $r->name . '(' . $r->notes . ')<br>';
                    }
                }
                //send the email
                $body = Setting::where('setting_key',
                    'volunteer_assignment_email_template')->first()->setting_value;
                $body = str_replace('{memberName}', $member_name, $body);
                $body = str_replace('{eventName}', $event_name, $body);
                $body = str_replace('{roles}', $roles, $body);
                $body = str_replace('{eventDates}', $event_dates, $body);
                $body = str_replace('{eventDescription}', $event_description, $body);
                $body = str_replace('{notes}', $notes, $body);
                Mail::send([],[], function ($message) use ($member,$body) {
                    $message->from(Setting::where('setting_key', 'company_email')->first()->setting_value,
                        Setting::where('setting_key', 'company_name')->first()->setting_value);
                    $message->to($member->email);
                    $message->setBody($body);
                    $message->setContentType('text/html');
                    $message->setSubject(Setting::where('setting_key',
                        'volunteer_assignment_email_subject')->first()->setting_value);

                });

            }
        }

        GeneralHelper::audit_trail("Added volunteer to event  with id:" . $request->event_id);
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();

    }

    public function update_volunteer(Request $request, $id)
    {
        if (!Sentinel::hasAccess('events.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $volunteer = EventVolunteer::find($id);
        $volunteer->notes = $request->notes;
        $roles = [];
        if (!empty($request->roles)) {
            $roles = serialize($request->roles);
        } else {
            $roles = serialize($roles);
        }
        $volunteer->roles = $roles;
        $volunteer->save();
        //notify member of assignment

        GeneralHelper::audit_trail("Updated volunteer to event  with id:" . $volunteer->event_id);
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();

    }

    public function remove_volunteer($id)
    {
        if (!Sentinel::hasAccess('events.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        EventVolunteer::destroy($id);
        GeneralHelper::audit_trail("Removed volunteer for event  with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect()->back();
    }

    public function edit($event)
    {
        if (!Sentinel::hasAccess('events.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $locations = array();
        foreach (EventLocation::all() as $key) {
            $locations[$key->id] = $key->name;
        }
        $calendars = array();
        foreach (EventCalendar::all() as $key) {
            $calendars[$key->id] = $key->name;
        }
        $branches = array();
        foreach (Branch::get() as $key) {
            $branches[$key->id] = $key->name;
        }
		$batches = ContributionBatch::select('id','name')->get();

		//get custom fields
        $custom_fields = CustomField::where('category', 'events')->get();
        return view('event.edit', compact('event', 'locations', 'custom_fields', 'calendars','branches','batches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Sentinel::hasAccess('events.update')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $event = Event::find($id);
        $event->branch_id = $request->branch_id;
        $event->event_location_id = $request->event_location_id;
        $event->event_calendar_id = $request->event_calendar_id;
        $event->cost = $request->cost;
        $event->name = $request->name;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->notes = $request->notes;
        $event->latitude = $request->latitude;
        $event->longitude = $request->longitude;
		$event->batches = $request->batches;
        if (!empty($request->all_day)) {
            $event->all_day = $request->all_day;
        } else {
            $event->all_day = 0;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
        }
        if (!empty($request->recurring == 1)) {
            $event->recurring = $request->recurring;
            $event->recur_frequency = $request->recur_frequency;
            $event->recur_start_date = $request->recur_start_date;
            if (!empty($request->recur_end_date)) {
                $event->recur_end_date = $request->recur_end_date;
            }

            $event->recur_next_date = date_format(date_add(date_create($request->recur_start_date),
                date_interval_create_from_date_string($request->recur_frequency . ' ' . $request->recur_type . 's')),
                'Y-m-d');

            $event->recur_type = $request->recur_type;
        } else {
            $event->recurring = 0;
        }
        if ($request->hasFile('featured_image')) {
            $file = array('featured_image' => Input::file('featured_image'));
            $rules = array('featured_image' => 'required|mimes:jpeg,jpg,bmp,png');
            $validator = Validator::make($file, $rules);
            if ($validator->fails()) {
                Flash::warning(trans('general.validation_error'));
                return redirect()->back()->withInput()->withErrors($validator);
            } else {
                $event->featured_image = $request->file('featured_image')->getClientOriginalName();
                $request->file('featured_image')->move(public_path() . '/uploads',
                    $request->file('featured_image')->getClientOriginalName());
            }

        }
        /* $files = array();
         if (!empty($request->file('files'))) {
             $count = 0;
             foreach ($request->file('files') as $key) {
                 $file = array('files' => $key);
                 $rules = array('files' => 'required|mimes:jpeg,jpg,bmp,png,pdf,docx,xlsx');
                 $validator = Validator::make($file, $rules);
                 if ($validator->fails()) {
                     Flash::warning(trans('general.validation_error'));
                     return redirect()->back()->withInput()->withErrors($validator);
                 } else {
                     $files[$count] = $key->getClientOriginalName();
                     $key->move(public_path() . '/uploads',
                         $key->getClientOriginalName());
                 }
                 $count++;
             }
         }*/
        $event->files = serialize([]);
        $event->gallery = serialize([]);
        //files
        $event->save();
        GeneralHelper::audit_trail("Updated event  with id:" . $event->id);
        Flash::success(trans('general.successfully_saved'));
        return redirect('event/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if (!Sentinel::hasAccess('events.delete')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        Event::destroy($id);
        GeneralHelper::audit_trail("Deleted event  with id:" . $id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('event/data');
    }


    public function deleteFile(Request $request, $id)
    {
        $event = Event::find($id);
        $files = unserialize($event->files);
        @unlink(public_path() . '/uploads/' . $files[$request->id]);
        $files = array_except($files, [$request->id]);
        $event->files = serialize($files);
        $event->save();


    }

    public function email_members(Request $request)
    {
        if (!Sentinel::hasAccess('communication.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $body = $request->message;
        $recipients = 1;
        $event = Event::find($request->event_id);
        foreach ($event->attenders as $attender) {
            if (!empty($attender->member)) {
                $member = $attender->member;

                $body = $request->message;
//lets build and replace available tags
                $body = str_replace('{firstName}', $member->first_name, $body);
                $body = str_replace('{middleName}', $member->middle_name, $body);
                $body = str_replace('{lastName}', $member->last_name, $body);
                $body = str_replace('{address}', $member->address, $body);
                $body = str_replace('{homePhone}', $member->home_phone, $body);
                $body = str_replace('{mobilePhone}', $member->mobile_phone_phone, $body);
                $body = str_replace('{email}', $member->email, $body);
                $email = $member->email;
                if (!empty($email)) {
                    Mail::send([],[], function ($message) use ($request, $email,$body) {
                        $message->from(Setting::where('setting_key', 'company_email')->first()->setting_value,
                            Setting::where('setting_key', 'company_name')->first()->setting_value);
                        $message->to($email);
                       $message->setBody($body);
                        $message->setContentType('text/html');
                        $message->setSubject($request->subject);

                    });

                }
                $recipients = $recipients + 1;
            }
        }
        $mail = new Email();
        $mail->user_id = Sentinel::getUser()->id;
        $mail->message = $body;
        $mail->subject = $request->subject;
        $mail->recipients = $recipients;
        $mail->send_to = 'All Members for event with id:' . $request->event_id;
        $mail->save();
        GeneralHelper::audit_trail("Send  email to all members");
        Flash::success("Email successfully sent");
        return redirect()->back();
    }

    public function sms_members(Request $request)
    {
        if (!Sentinel::hasAccess('communication.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $body = $request->message;
        $recipients = 1;
        $event = Event::find($request->event_id);
        if (Setting::where('setting_key', 'sms_enabled')->first()->setting_value == 1) {
            $active_sms = Setting::where('setting_key', 'active_sms')->first()->setting_value;
            foreach ($event->volunteers as $volunteer) {
                if (!empty($volunteer->member)) {
                    $member = $volunteer->member;
//lets build and replace available tags
                    $body = str_replace('{firstName}', $member->first_name, $body);
                    $body = str_replace('{middleName}', $member->middle_name, $body);
                    $body = str_replace('{lastName}', $member->last_name, $body);
                    $body = str_replace('{address}', $member->address, $body);
                    $body = str_replace('{homePhone}', $member->home_phone, $body);
                    $body = str_replace('{mobilePhone}', $member->mobile_phone, $body);
                    $body = str_replace('{email}', $member->email, $body);
                    $body = trim(strip_tags($body));
                    if (!empty($member->mobile_phone)) {
                        $active_sms = Setting::where('setting_key', 'active_sms')->first()->setting_value;
                        if ($active_sms == 'twilio') {
                            $twilio = new Twilio(Setting::where('setting_key', 'twilio_sid')->first()->setting_value,
                                Setting::where('setting_key', 'twilio_token')->first()->setting_value,
                                Setting::where('setting_key', 'twilio_phone_number')->first()->setting_value);
                            $twilio->message('+' . $member->mobile_phone, $body);
                        }
                        if ($active_sms == 'routesms') {
                            $host = Setting::where('setting_key', 'routesms_host')->first()->setting_value;
                            $port = Setting::where('setting_key', 'routesms_port')->first()->setting_value;
                            $username = Setting::where('setting_key', 'routesms_username')->first()->setting_value;
                            $password = Setting::where('setting_key', 'routesms_password')->first()->setting_value;
                            $sender = Setting::where('setting_key', 'sms_sender')->first()->setting_value;
                            $SMSText = $body;
                            $GSM = $member->mobile_phone;
                            $msgtype = 2;
                            $dlr = 1;
                            $routesms = new RouteSms($host, $port, $username, $password, $sender, $SMSText, $GSM,
                                $msgtype,
                                $dlr);
                            $routesms->Submit();
                        }
                        if ($active_sms == 'clickatell') {
                            $clickatell = new Rest(
                                Setting::where('setting_key', 'clickatell_api_id')->first()->setting_value);
                            $response = $clickatell->sendMessage(array($member->mobile_phone), $body);
                        }
                        if ($active_sms == 'infobip') {
                            $infobip = new Infobip(Setting::where('setting_key',
                                'sms_sender')->first()->setting_value, $body,
                                $member->mobile_phone);
                        }

                    }
                    $recipients = $recipients + 1;
                }
            }
            $sms = new Sms();
            $sms->user_id = Sentinel::getUser()->id;
            $sms->message = $body;
            $sms->gateway = $active_sms;
            $sms->recipients = $recipients;
            $sms->send_to = 'All members for event with id:' . $request->event_id;
            $sms->save();
            GeneralHelper::audit_trail("Sent SMS   to all members");
            Flash::success("SMS successfully sent");
            return redirect()->back();
        }
        Flash::success("Email successfully sent");
        return redirect()->back();
    }

    public function email_volunteers(Request $request)
    {
        if (!Sentinel::hasAccess('communication.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $body = $request->message;
        $recipients = 1;
        $event = Event::find($request->event_id);
        foreach ($event->volunteers as $volunteer) {
            if (!empty($volunteer->member)) {
                $member = $volunteer->member;

                $body = $request->message;
//lets build and replace available tags
                $body = str_replace('{firstName}', $member->first_name, $body);
                $body = str_replace('{middleName}', $member->middle_name, $body);
                $body = str_replace('{lastName}', $member->last_name, $body);
                $body = str_replace('{address}', $member->address, $body);
                $body = str_replace('{homePhone}', $member->home_phone, $body);
                $body = str_replace('{mobilePhone}', $member->mobile_phone_phone, $body);
                $body = str_replace('{email}', $member->email, $body);
                $email = $member->email;
                if (!empty($email)) {
                    Mail::send([],[], function ($message) use ($request, $email,$body) {
                        $message->from(Setting::where('setting_key', 'company_email')->first()->setting_value,
                            Setting::where('setting_key', 'company_name')->first()->setting_value);
                        $message->to($email);
                        $message->setBody($body);
                        $message->setContentType('text/html');
                        $message->setSubject($request->subject);

                    });

                }
                $recipients = $recipients + 1;
            }
        }
        $mail = new Email();
        $mail->user_id = Sentinel::getUser()->id;
        $mail->message = $body;
        $mail->subject = $request->subject;
        $mail->recipients = $recipients;
        $mail->send_to = 'All volunteers for event with id:' . $request->event_id;
        $mail->save();
        GeneralHelper::audit_trail("Send  email to all members");
        Flash::success("Email successfully sent");
        return redirect()->back();
    }

    public function sms_volunteers(Request $request)
    {
        if (!Sentinel::hasAccess('communication.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $body = $request->message;
        $recipients = 1;
        $event = Event::find($request->event_id);
        if (Setting::where('setting_key', 'sms_enabled')->first()->setting_value == 1) {
            $active_sms = Setting::where('setting_key', 'active_sms')->first()->setting_value;
            foreach ($event->attenders as $attender) {
                if (!empty($attender->member)) {
                    $member = $attender->member;
//lets build and replace available tags
                    $body = str_replace('{firstName}', $member->first_name, $body);
                    $body = str_replace('{middleName}', $member->middle_name, $body);
                    $body = str_replace('{lastName}', $member->last_name, $body);
                    $body = str_replace('{address}', $member->address, $body);
                    $body = str_replace('{homePhone}', $member->home_phone, $body);
                    $body = str_replace('{mobilePhone}', $member->mobile_phone, $body);
                    $body = str_replace('{email}', $member->email, $body);
                    $body = trim(strip_tags($body));
                    if (!empty($member->mobile_phone)) {
                        $active_sms = Setting::where('setting_key', 'active_sms')->first()->setting_value;
                        if ($active_sms == 'twilio') {
                            $twilio = new Twilio(Setting::where('setting_key', 'twilio_sid')->first()->setting_value,
                                Setting::where('setting_key', 'twilio_token')->first()->setting_value,
                                Setting::where('setting_key', 'twilio_phone_number')->first()->setting_value);
                            $twilio->message('+' . $member->mobile_phone, $body);
                        }
                        if ($active_sms == 'routesms') {
                            $host = Setting::where('setting_key', 'routesms_host')->first()->setting_value;
                            $port = Setting::where('setting_key', 'routesms_port')->first()->setting_value;
                            $username = Setting::where('setting_key', 'routesms_username')->first()->setting_value;
                            $password = Setting::where('setting_key', 'routesms_password')->first()->setting_value;
                            $sender = Setting::where('setting_key', 'sms_sender')->first()->setting_value;
                            $SMSText = $body;
                            $GSM = $member->mobile_phone;
                            $msgtype = 2;
                            $dlr = 1;
                            $routesms = new RouteSms($host, $port, $username, $password, $sender, $SMSText, $GSM,
                                $msgtype,
                                $dlr);
                            $routesms->Submit();
                        }
                        if ($active_sms == 'clickatell') {
                            $clickatell = new Rest(
                                Setting::where('setting_key', 'clickatell_api_id')->first()->setting_value);
                            $response = $clickatell->sendMessage(array($member->mobile_phone), $body);
                        }
                        if ($active_sms == 'infobip') {
                            $infobip = new Infobip(Setting::where('setting_key',
                                'sms_sender')->first()->setting_value, $body,
                                $member->mobile_phone);
                        }

                    }
                    $recipients = $recipients + 1;
                }
            }
            $sms = new Sms();
            $sms->user_id = Sentinel::getUser()->id;
            $sms->message = $body;
            $sms->gateway = $active_sms;
            $sms->recipients = $recipients;
            $sms->send_to = 'All volunteers for event with id:' . $request->event_id;
            $sms->save();
            GeneralHelper::audit_trail("Sent SMS   to all members");
            Flash::success("SMS successfully sent");
            return redirect()->back();
        }
        Flash::success("Email successfully sent");
        return redirect()->back();
    }

    public function payment($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $members = array();
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
        }
        $payment_methods = array();
        foreach (PaymentMethod::all() as $key) {
            $payment_methods[$key->id] = $key->name;
        }
        return view('event.payment', compact('event', 'members', 'payment_methods'));
    }

    public function create_payment($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $members = array();
        $members["0"] = trans_choice('general.anonymous', 1);
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
        }
        $payment_methods = array();
        foreach (PaymentMethod::all() as $key) {
            $payment_methods[$key->id] = $key->name;
        }
		
		$groups = Group::select('id','group_name')->get();
		
        return view('event.create_payment', compact('event', 'members', 'payment_methods'));
    }

    public function store_payment(Request $request, $id)
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $payment = new EventPayment();
        $payment->user_id = Sentinel::getUser()->id;
        $payment->event_id = $id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->member_id = $request->member_id;
        $payment->amount = $request->amount;
		$payment->group_id = $request->group_id;
		$payment->date = $request->date;
        $date = $request->date;
        $payment->year = $date[0];
        $payment->month = $date[1];
        $payment->notes = $request->notes;
        $payment->save();

        $event = Event::find($id);

        $body = "You have received a payment of " . $request->amount . ' for event(' . $event->name . '-#' . $event->id . ') on ' . $request->date;
        if (!empty(Sentinel::getUser()->email)) {
            Mail::send([],[], function ($message) use($body) {
                $message->from(Setting::where('setting_key', 'company_email')->first()->setting_value,
                    Setting::where('setting_key', 'company_name')->first()->setting_value);
                $message->to(Setting::where('setting_key', 'company_email')->first()->setting_value);
                $message->setBody($body);
                $message->setContentType('text/html');
                $message->setSubject("Payment Received");

            });

        }
        GeneralHelper::audit_trail("Added payment with event with id " . $id);
        Flash::success("Successfully saved");
        return redirect('event/' . $id . '/payment');
    }

    public function edit_payment($event_payment)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        $members = array();
        $members["0"] = trans_choice('general.anonymous', 1);
        foreach (Member::all() as $key) {
            $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
        }
        $payment_methods = array();
        foreach (PaymentMethod::all() as $key) {
            $payment_methods[$key->id] = $key->name;
        }
        return view('event.edit_payment', compact('event_payment', 'members', 'payment_methods'));
    }

    public function update_payment(Request $request, $id)
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $payment = EventPayment::find($id);
        $payment->payment_method_id = $request->payment_method_id;
        $payment->member_id = $request->member_id;
        $payment->amount = $request->amount;
		$payment->group_id = $request->group_id;
		$payment->date = $request->date;
        $date = $request->date;
        $payment->year = $date[0];
        $payment->month = $date[1];
        $payment->notes = $request->notes;
        $payment->save();
        GeneralHelper::audit_trail("Updated payment with event with id " . $payment->event_id);
        Flash::success("Successfully saved");
        return redirect('event/' . $payment->event_id . '/payment');
    }

    public function print_event($event)
    {
        if (!Sentinel::hasAccess('events.view')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }

        return view('event.print', compact('event'));
    }

    public function delete_payment($id)
    {
        if (!Sentinel::hasAccess('events.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        EventPayment::destroy($id);
        GeneralHelper::audit_trail("Deleted payment  with id " . $id);
        Flash::success("Successfully deleted");
        return redirect()->back();
    }
}
