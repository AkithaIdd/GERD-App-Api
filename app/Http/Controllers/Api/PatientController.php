<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\PatientRecord;
use Validator;
use Illuminate\Http\Request;


class PatientController extends Controller
{
    // public function getPatient(Request $request , Patient $patient)
    // {
        
    //     if($request->has('name')){
    //         return $patient->where('name',$request->input('name'))->get();
    //     }

    //     if($request->has('phoneNumber')){
    //         return $patient->where('phoneNumber',$request->input('phoneNumber'))->get();
    //     }
    //     return $patient->get();
        
        
    // }

    public function getPatients(Request $request)
        //test
    {
       
        $doctorId = $request->get('doctorId');
        $searchTerm = $request->get('searchTerm');

        $search_patient = Patient::where('doctor_id', '=' , "$doctorId")
                                    // ->andWhere('name','like',"%{$searchTerm}%")
                                    // ->andWhere('phoneNumber', 'like' , "%{$searchTerm}%")
                                    ->where(function ($query) use ($searchTerm) {
                                        $query->where('name','like',"%{$searchTerm}%")
                                              ->orWhere('phoneNumber', 'like' , "%{$searchTerm}%");
                                    })
                                    ->get(); 

        return response()->json([
            'patientList' => $search_patient
        ]);
    }
    public function getPatientRecords(Request $request)
    {
        $data = $request->get('getPatientRecords');

        $search_patient_record = PatientRecord::where('patientId', "$data")
                                    ->get()->reverse()->values(); 

        return response()->json([
            'patientRecordList' => $search_patient_record
        ]);
    }
    public function addPatientRecord(Request $request)
    {
        $patientValidator = Validator::make($request->all(),[
            "patientId"=>"required",
            "date_of_test" => "required",
            // "age_of_onset" => "required",
            "age_of_patient" => "required",
            "length_of_les" => "required",
        ]);

        if($patientValidator->fails()){
            return response()->json([
               'status' => 400,
               'message' => $patientValidator->messages()->first(),
            //    'errors' => $patientValidator->errors(),
            ]);
        }

        $patientrecord = new PatientRecord();
        $patientrecord->patientId = $request->patientId;
        $patientrecord->date_of_test = $request->date_of_test;
        // $patientrecord->age_of_onset = $request->age_of_onset;
        $patientrecord->age_of_patient = $request->age_of_patient;
        $patientrecord->length_of_les = $request->length_of_les;
        $patientrecord->save();

        return response()->json([
            'status' => 200,
            'message' => "Patient Record Added"
         ]);

    }
    public function addPatient(Request $request)
    {
        $patientValidator = Validator::make($request->all(),[
            "doctor_id"=>"required",
            "name" => "required",
            "date_of_birth" => "required",
            "age_of_onset" => "required",
            "phoneNumber" => "required|min:10|max:10|unique:patients"
        ]);

        if($patientValidator->fails()){
            return response()->json([
               'status' => 400,
               'message' => $patientValidator->messages()->first(),
                
            //    'errors' => $patientValidator->errors(),
            ]);
        }

        $patient = new Patient();
        $patient->name = $request->name;
        $patient->doctor_id = $request->doctor_id;
        $patient->date_of_birth = $request->date_of_birth;
        $patient->age_of_onset = $request->age_of_onset;
        $patient->phoneNumber = $request->phoneNumber;
        $patient->age = $request->age;
        $patient->save();

        return response()->json([
            'status' => 200,
            'message' => "Patient Added",
            'patientId' => $patient->id,
         ]);

    }

    public function updatePatient(Request $request,$id)
    { 
        $phone = $request->get('phoneNumber');

        // $items = Patient::select('phoneNumber')
        //      ->where('id', $id)
        //      ->first();
        error_log("id".$id);
        $phoneNumOnDb = Patient::where('id', $id)->
        pluck('phoneNumber')
        ->first();

        error_log($phone);
        error_log("dd".$phoneNumOnDb);
       

        $patientValidator = Validator::make($request->all(),[
          
            "phoneNumber" => "required|min:10|max:10|unique:patients"
        ]);
        if($phone==$phoneNumOnDb){
            // return response()->json([
            //     'status' => 400,
            //     'message' => "phone",
            //  ]);
        }else if($patientValidator->fails()){
            return response()->json([
               'status' => 400,
               'message' => $patientValidator->messages()->first(),
            ]);
        }

        $patient = Patient::find($id);
        if($patient)
        {
            $patient->name = $request->name;
            $patient->date_of_birth = $request->date_of_birth;
            $patient->phoneNumber = $request->phoneNumber;
            $patient->age = $request->age;
            $patient->update();

            return response()->json([
                'status' => 200,
                'message' => "Patient Updated",
                'patientId' => $patient->id,
             ]);
        }

    }
}
