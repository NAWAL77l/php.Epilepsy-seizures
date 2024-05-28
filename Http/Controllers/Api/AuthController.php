<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Patient;
use App\Caregiver;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Hamcrest\Core\IsNull;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public $successStatus = 200;


    //  patient جلب بيانات
    public function get_profile_patient(Request $request)
    {

        $rules = [
            'id' => 'required|exists:patients',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $patient = Patient::Where('id', $request['id'])->get()->first();
        $data = [];

        try {
            $data['id'] = $patient->id;
            $data['email'] = $patient->email;
            $data['password'] = $patient->password;
            $data['username'] = $patient->username;
            $data['fullname'] = $patient->fullname;
            $data['phone'] = $patient->phone;
            $data['address'] = $patient->address;
            $data['age'] = $patient->age;
            $data['gender'] = $patient->gender;
            $data['caregiver_id'] = $patient->caregiver_id;
            if (!is_null($data['caregiver_id'])) {
                $caregiver = Caregiver::WHERE('id', $patient->caregiver_id)->get()->first();
                $data['caregiver_username'] = $caregiver->username;
                $data['caregiver_fullname'] = $caregiver->fullname;
                $data['caregiver_email'] = $caregiver->email;
            }

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully get profile patient!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }

    // create new patient account
    public function register_patient(Request $request)
    {
        $rules = [
            //         'username','fullname', 'email','password','phone','address','age','caregiver_id'
            'username' => 'required',
            'fullname' => 'required',
            'email' => 'required|unique:patients',
            'password' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'age' => 'required',
            'gender' => 'required'
            
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }
        $data = [];
        $input = $request->all();


        try {
            $patient = Patient::create($input);
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }

        //         'username','fullname', 'email','password','phone','address','age','caregiver_id'


        $data['id'] = $patient->id;
        $data['email'] = $patient->email;
        $data['password'] = $patient->password;
        $data['username'] = $patient->username;
        $data['fullname'] = $patient->fullname;
        $data['phone'] = $patient->phone;
        $data['address'] = $patient->address;
        $data['age'] = $patient->age;
        $data['gender'] = $patient->gender;

        return response()->json(
            [
                'status' => '1',
                'data' => $data,
                'message' => 'Successfully created patient account!'
            ],
            $this->successStatus
        );
    }





    // update patient account
    public function update_patient(Request $request)
    {
        $rules = [
            'id' => 'required|exists:patients'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }
        $data = [];
        $input = $request->all();

        try {
            $patient = Patient::WHERE('id', $input['id'])->update($input);
            $patient = Patient::WHERE('id', $input['id'])->get()->first();
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }

        $data['id'] = $patient->id;
        $data['email'] = $patient->email;
        $data['password'] = $patient->password;
        $data['username'] = $patient->username;
        $data['fullname'] = $patient->fullname;
        $data['phone'] = $patient->phone;
        $data['address'] = $patient->address;
        $data['age'] = $patient->age;
        $data['gender'] = $patient->gender;
        
        $data['caregiver_id'] = $patient->caregiver_id;
        if (!is_null($data['caregiver_id'])) {
            $caregiver = Caregiver::WHERE('id', $patient->caregiver_id)->get()->first();
            $data['caregiver_username'] = $caregiver->username;
            $data['caregiver_fullname'] = $caregiver->fullname;
            $data['caregiver_email'] = $caregiver->email;
        }

        return response()->json(
            [
                'status' => '1',
                'data' => $data,
                'message' => 'Successfully updated patient account!'
            ],
            $this->successStatus
        );
    }



    //  patient تسجيل دخول
    public function login_patient(Request $request)
    {

        $rules = [
            'email' => 'required|exists:patients',
            'password' => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }
        $countpatient = Patient::Where('email', $request['email'])
            ->where('password', $request['password'])
            ->count();
        $data = [];
        if ($countpatient != 0) {
            $patient = Patient::Where('email', $request['email'])
                ->where('password', $request['password'])
                ->get()->first();
            $data['id'] = $patient->id;
            $data['email'] = $patient->email;
            $data['password'] = $patient->password;
            $data['username'] = $patient->username;
            $data['fullname'] = $patient->fullname;
            $data['phone'] = $patient->phone;
            $data['address'] = $patient->address;
            $data['age'] = $patient->age;
            $data['gender'] = $patient->gender;
            $data['caregiver_id'] = $patient->caregiver_id;
            if (!is_null($data['caregiver_id'])) {
                $caregiver = Caregiver::WHERE('id', $patient->caregiver_id)->get()->first();
                $data['caregiver_username'] = $caregiver->username;
                $data['caregiver_fullname'] = $caregiver->fullname;
                $data['caregiver_email'] = $caregiver->email;
            }

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully login patient!'
                ],
                $this->successStatus
            );
        } else {
            return response()->json(['data' => 'check your email and password', 'status' => '-1'], $this->successStatus);
        }
    }


    //  caregivers جلب بيانات
    public function get_caregivers(Request $request)
    {

        $rules = [];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $caregivers = Caregiver::get();
        $data = [];
        $i = 0;
        try {
            foreach ($caregivers as $caregiver) {
                $data[$i]['id'] = $caregiver->id;
                $data[$i]['username'] = $caregiver->username;
                $data[$i]['fullname'] = $caregiver->fullname;
                $data[$i]['email'] = $caregiver->email;
                $data[$i]['password'] = $caregiver->password;
                $data[$i]['phone'] = $caregiver->phone;
                $data[$i]['gender'] = $caregiver->gender;
                $i++;
            }

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully get caregivers!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }


    // register caregiver 
    public function register_caregiver(Request $request)
    {
        $rules = [
            // 'username','fullname', 'email','password','phone'
            'username' => 'required',
            'fullname' => 'required',
            'email' => 'required|unique:caregivers',
            'password' => 'required',
            'phone' => 'required',
            'gender' => 'required'
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $data = [];
        $input = $request->all();
        $caregiver = Caregiver::create($input);

        try {
            $data['id'] = $caregiver->id;
            $data['username'] = $caregiver->username;
            $data['fullname'] = $caregiver->fullname;
            $data['email'] = $caregiver->email;
            $data['password'] = $caregiver->password;
            $data['phone'] = $caregiver->phone;
            $data['gender'] = $caregiver->gender;

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully add caregiver!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }


    // update caregiver 
    public function update_caregiver(Request $request)
    {
        $rules = [
            'id' => 'required|exists:caregivers'
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $data = [];
        $input = $request->all();
        $caregiver = Caregiver::WHERE('id', $request->id)->update($input);
        $caregiver = Caregiver::WHERE('id', $request->id)->get()->first();


        try {
            $data['id'] = $caregiver->id;
            $data['username'] = $caregiver->username;
            $data['fullname'] = $caregiver->fullname;
            $data['email'] = $caregiver->email;
            $data['password'] = $caregiver->password;
            $data['phone'] = $caregiver->phone;
            $data['gender'] = $caregiver->gender;

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully updated caregiver!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }


    //  caregiver جلب بيانات
    public function get_profile_caregiver(Request $request)
    {

        $rules = [
            'id' => 'required|exists:caregivers',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }

        $caregiver = Caregiver::Where('id', $request['id'])->get()->first();
        $data = [];
        try {
            $data['id'] = $caregiver->id;
            $data['username'] = $caregiver->username;
            $data['fullname'] = $caregiver->fullname;
            $data['email'] = $caregiver->email;
            $data['password'] = $caregiver->password;
            $data['phone'] = $caregiver->phone;
            $data['gender'] = $caregiver->gender;

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully get caregiver!'
                ],
                $this->successStatus
            );
        } catch (Exception $e) {
            return response()->json(['error' => $e, 'status' => '-1'], $this->successStatus);
        }
    }


    //  caregiver تسجيل دخول
    public function login_caregiver(Request $request)
    {

        $rules = [
            'email' => 'required|exists:caregivers',
            'password' => 'required|min:8',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'status' => '-1'], $this->successStatus);
        }
        $countcaregiver = Caregiver::Where('email', $request['email'])
            ->where('password', $request['password'])
            ->count();
        $data = [];
        if ($countcaregiver != 0) {
            $caregiver = Caregiver::Where('email', $request['email'])
                ->where('password', $request['password'])
                ->get()->first();
            $data['id'] = $caregiver->id;
            $data['username'] = $caregiver->username;
            $data['fullname'] = $caregiver->fullname;
            $data['email'] = $caregiver->email;
            $data['password'] = $caregiver->password;
            $data['phone'] = $caregiver->phone;
            $data['gender'] = $caregiver->gender;

            return response()->json(
                [
                    'status' => '1',
                    'data' => $data,
                    'message' => 'Successfully login caregiver!'
                ],
                $this->successStatus
            );
        } else {
            return response()->json(['data' => 'check your email and password', 'status' => '-1'], $this->successStatus);
        }
    }
}
