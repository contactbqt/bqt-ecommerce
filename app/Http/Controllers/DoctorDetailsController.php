<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\AppointmentBookingSchedule;
use App\Models\AppointmentTimeslot;
use App\Models\User;

use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBookMail;

class DoctorDetailsController extends Controller
{

    public function index(Request $request, User $doctor)
    {
        $patient_details = [];
        $disabledDays = [];
        // get patient details if logged in
        if( auth()->guard('web')->check() ) {
            $patient_details = User::with('userDetail')->where('id', auth()->guard('web')->user()->id)->first();
            //dd($patient_details);
            $patient_details['patient_name'] = $patient_details->name;
            $patient_details['patient_email'] = $patient_details->email;
            $patient_details['patient_phone'] = $patient_details->phone;
            $patient_details['patient_address'] = $patient_details->address;
        }

        $doc_details = User::with('userDetail', 'schedules', 'departments')->find($doctor->id);

        /* Prepare disabled days from schedules */
        if($doc_details->schedules->isNotEmpty()) {
            $disabledDays = $doc_details->schedules->pluck('day_of_week')->toArray();
        }
        //dd($this->disabledDays);

        //dd($doc_details);
        return view('doctor-details', [
            'doc_details' => $doc_details,
            'disabledDays' => $disabledDays,
            'patientDetails' => $patient_details
        ]);
    }

    public function getTimeslots(Request $request)
    {
        $dayNo = $request->input('dayNo');
        $doctorId = $request->input('doctor_id');
        $timeslots = AppointmentTimeslot::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayNo)
            ->selectRaw('id,
                        day_of_week,
                        CONCAT( DATE_FORMAT(start_time, "%h:%i %p"), " - ", DATE_FORMAT(end_time, "%h:%i %p")) as timeslot'
            )
            ->orderBy('day_of_week', 'ASC')
            ->get()
            ->toArray();

            $str = '';
            $str .= '<select class="form-select" id="time" name="appointment_time">';
            $str .= '<option value="">Select Time Slot</option>';
            if(!empty($timeslots)) {
                foreach($timeslots as $timeslot) {
                    $str .= '<option value="'.$timeslot['id'].'">'.$timeslot['timeslot'].'</option>';
                }
            }
            $str .= '</select>';

        return response()->json(['timeslots' => $str]);
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|max:10',
            'patient_address' => 'required|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);
        //dd($request->all());

        // save the appointment details
        $dbformatted_appointment_date = Carbon::createFromFormat('d-m-Y', $request->input('appointment_date'))->format('Y-m-d');
        $dayNo = Carbon::parse($dbformatted_appointment_date)->dayOfWeek;
        $timeslot_id = $request->input('appointment_time');
        $doctor_id = $request->input('doctor_id');

        //check the timeslot is available for the doctor
        $get_timeslot = AppointmentTimeslot::find($timeslot_id);
        //dd($get_timeslot);
        if($get_timeslot && $get_timeslot->doctor_id == $doctor_id )
        {
            $start_time = $get_timeslot->start_time;
            $end_time = $get_timeslot->end_time;

            //check if the patient is login
            if( auth()->guard('web')->check() ) //Patient is logged In
            {
                $patient_id = auth()->guard('web')->user()->id;

                $appointment = new \App\Models\AppointmentBookingSchedule();
                $appointment->appointment_date = $dbformatted_appointment_date;
                $appointment->patient_id = $patient_id;
                $appointment->patient_name = $request->input('patient_name');
                $appointment->patient_email = $request->input('patient_email');
                $appointment->patient_contact = $request->input('patient_phone');
                $appointment->patient_address = $request->input('patient_address');
                $appointment->patient_remarks = $request->input('patient_remarks');
                $appointment->doctor_id = $doctor_id;
                $appointment->start_time = $start_time;
                $appointment->end_time = $end_time;
                $appointment->status = 'booked';
                $appointment->remarks = NULL;
                $appointment->save();
                $adminMail = config('constants.admin_mail');
                if ($adminMail) {
                    Mail::to($adminMail)->send(new AppointmentBookMail($appointment));
                }

                return response()->json(['status' => true, 'status_code'=>'100', 'message' => 'Appointment booked successfully']);

            }
            else  //Patient is not logged In
            {
                //check the given email is exists or not
                $user_exists = User::where('email', $request->input('patient_email'))->where('user_type', 'patient')->get();
                if($user_exists->isNotEmpty()) //User exists
                {
                    return response()->json(['status' => false, 'status_code'=>'104', 'message' => 'User Already Exists']);
                }
                else //New user, register, save appointment data and login
                {
                    //register a user
                    $newUser = new User();
                    $newUser->user_type = 'patient';
                    $newUser->name = $request->input('patient_name');
                    $newUser->email = $request->input('patient_email');
                    $newUser->password = Hash::make('temp@1234'); // default password
                    $newUser->save();

                    //save it into user details table
                    $newUser->userDetail()->create([
                        'contact_number' => $request->input('patient_phone'),
                        'address' => $request->input('patient_address')
                    ]);

                    // save appointment for new user
                    $appointment = new \App\Models\AppointmentBookingSchedule();
                    $appointment->appointment_date = $dbformatted_appointment_date;
                    $appointment->patient_id = $newUser->id;
                    $appointment->patient_name = $request->input('patient_name');
                    $appointment->patient_email = $request->input('patient_email');
                    $appointment->patient_contact = $request->input('patient_phone');
                    $appointment->patient_address = $request->input('patient_address');
                    $appointment->patient_remarks = $request->input('patient_remarks');
                    $appointment->doctor_id = $doctor_id;
                    $appointment->start_time = $start_time;
                    $appointment->end_time = $end_time;
                    $appointment->status = 'booked';
                    $appointment->remarks = NULL;
                    $appointment->save();
                    $adminMail = config('constants.admin_mail');
                    if ($adminMail) {
                        Mail::to($adminMail)->send(new AppointmentBookMail($appointment));
                    }

                    // auto-login the new user
                    auth()->guard('web')->login($newUser);

                    return response()->json(['status' => true, 'status_code'=>'101', 'message' => 'Registered And Appointment booked successfully']);

                }

            }

        }
        else //Something went wrong
        {
            return response()->json(['status' => false, 'status_code'=>'105', 'message' => 'Appointment booked successfully']);
        }


        return redirect()->route('doctor.appointments')->with('success', 'Appointment booked successfully');
    }




}
