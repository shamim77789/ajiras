<?php

namespace App\Http\Controllers;

use Aloha\Twilio\Twilio;
use App\Helpers\GeneralHelper;
use App\Helpers\Infobip;
use App\Helpers\RouteSms;
use App\Models\Email;
use App\Models\Member;
use App\Models\MemberTag;
use App\Models\Sms;
use App\Models\Tag;
use App\Models\CustomField;
use App\Models\CustomFieldMeta;
use App\Models\Setting;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Clickatell\Rest;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;

class TagController extends Controller
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
        $data = Tag::all();
        $menus = array(
            'items' => array(),
            'parents' => array()
        );
        // Builds the array lists with data from the SQL result
        foreach (Tag::all() as $items) {
            // Create current menus item id into array
            $menus['items'][$items['id']] = $items;
            // Creates list of all items with children
            $menus['parents'][$items['parent_id']][] = $items['id'];
        }
        return view('tag.data', compact('data', 'menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $tags = array();
        foreach (Tag::all() as $key) {
            $tags[$key->id] = $key->name;
        }
        return view('tag.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $tag = new Tag();
        $tag->name = $request->result[0];
        if ($request->parent_id == 1) {
            $tag->parent_id = 0;
        } else {
            $tag->parent_id = $request->parent_id;
        }

        $tag->user_id = $request->user_id;
        $tag->notes = $request->result[1];
        $tag->save();
        $json = array();
        $json['id'] = $tag->id;
        $json['success'] = 1;
        $json['text'] = $tag->name . " (0 " . trans_choice('general.people', 2) . ')';
        echo json_encode($json, JSON_UNESCAPED_SLASHES);
    }


    public function show($tag)
    {
        $tags = array();
        foreach (MemberTag::where('tag_id', $tag->id)->get() as $key) {
            array_push($tags, $key->member_id);
        }
        $members = array();
        foreach (Member::all() as $key) {
            if (!in_array($key->id, $tags)) {
                $members[$key->id] = $key->first_name . ' ' . $key->middle_name . ' ' . $key->last_name . '(' . $key->id . ')';
            }
        }
        return view('tag.show', compact('tag', 'members'));
    }


    public function edit($tag)
    {
        $tags = array();
        foreach (Tag::all() as $key) {
            $tags[$key->id] = $key->name;
        }
        return view('tag.edit', compact('tag', 'tags'));
    }

    public function tag_data($tag)
    {
        $json = array();
        $json["name"] = $tag->name;
        $json["notes"] = $tag->notes;
        echo json_encode($json, JSON_UNESCAPED_SLASHES);
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
        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->notes = $request->notes;
        $tag->save();
        Flash::success(trans('general.successfully_saved'));
        return redirect('tag/data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        Tag::destroy($id);
        Tag::where('parent_id', $id)->delete();
        MemberTag::where('tag_id', $id)->delete();
        Flash::success(trans('general.successfully_deleted'));
        return redirect('tag/data');
    }

    public function remove_member(Request $request, $id)
    {
        MemberTag::destroy($request->id);
        Flash::success(trans('general.successfully_deleted'));
        return redirect('tag/' . $id . '/show');
    }

    public function add_members(Request $request)
    {
        if (!empty($request->members_id)) {

            foreach ($request->members_id as $k) {
                $tag = new MemberTag();
                $tag->member_id = $k;
                $tag->tag_id = $request->tag_id;
                $tag->user_id = Sentinel::getUser()->id;
                $tag->save();

            }
        }
        Flash::success(trans('general.successfully_saved'));
        return redirect()->back();
    }

    public function email_members(Request $request)
    {
        if (!Sentinel::hasAccess('communication.create')) {
            Flash::warning("Permission Denied");
            return redirect('/');
        }
        $body = $request->message;
        $recipients = 1;
        $tag=Tag::find($request->tag_id);
        foreach ($tag->members as $member) {
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
                Mail::raw($body, function ($message) use ($request, $email) {
                    $message->from(Setting::where('setting_key', 'company_email')->first()->setting_value,
                        Setting::where('setting_key', 'company_name')->first()->setting_value);
                    $message->to($email);
                    $headers = $message->getHeaders();
                    $message->setContentType('text/html');
                    $message->setSubject($request->subject);

                });

            }
            $recipients = $recipients + 1;
        }
        $mail = new Email();
        $mail->user_id = Sentinel::getUser()->id;
        $mail->message = $body;
        $mail->subject = $request->subject;
        $mail->recipients = $recipients;
        $mail->send_to = 'All Members for event with id:' . $request->tag_id;
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
        $tag=Tag::find($request->tag_id);
        if (Setting::where('setting_key', 'sms_enabled')->first()->setting_value == 1) {
            $active_sms = Setting::where('setting_key', 'active_sms')->first()->setting_value;
            foreach ($tag->members as $member) {
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
            $sms = new Sms();
            $sms->user_id = Sentinel::getUser()->id;
            $sms->message = $body;
            $sms->gateway = $active_sms;
            $sms->recipients = $recipients;
            $sms->send_to = 'All members for tag with id:'.$request->tag_id;
            $sms->save();
            GeneralHelper::audit_trail("Sent SMS   to all members");
            Flash::success("SMS successfully sent");
            return redirect()->back();
        }
        Flash::success("Email successfully sent");
        return redirect()->back();
    }
}
