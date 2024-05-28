<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Caregiver;
use App\Report;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Hamcrest\Core\IsNull;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public $successStatus = 200;


    //  report جلب بيانات
    public function get_report(Request $request)
    {
        // 'patient_id','caregiver_id','datetime','status','details'

        $rules = [
            'id' => 'required|exists:reports',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $report = Report::Where('id', $request['id'])->get()->first();
        $data = [];

        try {

            $data['id'] = $report->id;
            $data['patient_id'] = $report->patient_id;
            $data['caregiver_id'] = $report->caregiver_id;
            $data['details'] = $report->details;
            $data['status'] = $report->status;
            $patient = Patient::WHERE('id', $data['patient_id'])->get()->first();
            $data['patient_email'] = $patient->email;
            $data['patient_username'] = $patient->username;
            if(!is_null($report->caregiver_id)){
            $caregiver = Caregiver::WHERE('id', $data['caregiver_id'])->get()->first();
            $data['caregiver_email'] = $caregiver->email;
            $data['caregiver_username'] = $caregiver->username;
        }
            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully get report!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }



    //  reports جلب بيانات
    public function get_patient_reports(Request $request)
    {

        $rules = [
            'id' => 'required|exists:patients'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $input = $request->all();
        $reports = Report::WHERE('patient_id', $input['id'])->get();
        $data = [];
        $i = 0;
        try {
            foreach ($reports as $report) {
                $data[$i]['id'] = $report->id;
                $data[$i]['patient_id'] = $report->patient_id;
                $data[$i]['caregiver_id'] = $report->caregiver_id;
                $data[$i]['details'] = $report->details;
                $data[$i]['status'] = $report->status;
                $patient = Patient::WHERE('id', $data[$i]['patient_id'])->get()->first();
                $data[$i]['patient_email'] = $patient->email;
                $data[$i]['patient_username'] = $patient->username;
            if(!is_null($report->caregiver_id)){
                $caregiver = Caregiver::WHERE('id', $data[$i]['caregiver_id'])->get()->first();
                $data[$i]['caregiver_email'] = $caregiver->email;
                $data[$i]['caregiver_username'] = $caregiver->username;
            }
                $i++;
            }

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully get patient reports!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }

    //  reports جلب بيانات
    public function get_caregiver_reports(Request $request)
    {

        $rules = [
            'id' => 'required|exists:caregivers'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $input = $request->all();
        $reports = Report::WHERE('caregiver_id', $input['id'])->get();
        $data = [];
        $i = 0;
        try {
            foreach ($reports as $report) {
                $data[$i]['id'] = $report->id;
                $data[$i]['patient_id'] = $report->patient_id;
                $data[$i]['caregiver_id'] = $report->caregiver_id;
                $data[$i]['details'] = $report->details;
                $data[$i]['status'] = $report->status;
                $patient = Patient::WHERE('id', $data[$i]['patient_id'])->get()->first();
                $data[$i]['patient_email'] = $patient->email;
                $data[$i]['patient_username'] = $patient->username;
                $caregiver = Caregiver::WHERE('id', $data[$i]['caregiver_id'])->get()->first();
                $data[$i]['caregiver_email'] = $caregiver->email;
                $data[$i]['caregiver_username'] = $caregiver->username;
                $i++;
            }

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully get caregiver reports!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }


    //  report اضافة
    public function add_report(Request $request)
    {
        // 'patient_id','caregiver_id','datetime','status','details'

        $rules = [
            'datetime' => 'required',
            'details' => 'required',
            'patient_id' => 'required',
            'caregiver_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $input = $request->all();
        $report = Report::create($input);
        $data = [];

        try {

            $data['id'] = $report->id;
            $data['patient_id'] = $report->patient_id;
            $data['caregiver_id'] = $report->caregiver_id;
            $data['details'] = $report->details;
            $data['status'] = 0;
            $patient = Patient::WHERE('id', $data['patient_id'])->get()->first();
            $data['patient_email'] = $patient->email;
            $data['patient_username'] = $patient->username;
            if (!is_null($report->caregiver_id)) {
                $caregiver = Caregiver::WHERE('id', $data['caregiver_id'])->get()->first();
                $data['caregiver_email'] = $caregiver->email;
                $data['caregiver_username'] = $caregiver->username;
            }
            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully add report!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }

    //  report تحديث
    public function update_report(Request $request)
    {
        // 'patient_id','caregiver_id','datetime','status','details'

        $rules = [
            'id' => 'required|exists:reports'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $input = $request->all();
        $report = Report::WHERE('id', $input['id'])->update($input);
        $report = Report::WHERE('id', $input['id'])->get()->first();
        $data = [];

        try {
            $data['id'] = $report->id;
            $data['patient_id'] = $report->patient_id;
            $data['caregiver_id'] = $report->caregiver_id;
            $data['details'] = $report->details;
            $data['status'] = $report->status;
            $patient = Patient::WHERE('id', $data['patient_id'])->get()->first();
            $data['patient_email'] = $patient->email;
            $data['patient_username'] = $patient->username;
            if (!is_null($report->caregiver_id)) {
                $caregiver = Caregiver::WHERE('id', $data['caregiver_id'])->get()->first();
                $data['caregiver_email'] = $caregiver->email;
                $data['caregiver_username'] = $caregiver->username;
            }
            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully update report!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }
}
