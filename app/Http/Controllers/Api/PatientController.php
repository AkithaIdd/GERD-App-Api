<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Patient;
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
    {
        $data = $request->get('getPatient');

        $search_patient = Patient::where('name', 'like' , "%{$data}%")
                                    ->orWhere('phoneNumber', 'like' , "%{$data}%")
                                    ->get();

        return response()->json([
            'patientList' => $search_patient
        ]);
    }
    public function addPatient(Request $request)
    {
        $patientValidator = Validator::make($request->all(),[
            "name" => "required",
            "date_of_birth" => "required",
            "phoneNumber" => "required|min:10|max:10|unique:patients"
        ]);

        if($patientValidator->fails()){
            return response()->json([
               'status' => 400,
               'message' => "Bad Request",
               'errors' => $patientValidator->errors(),
            ]);
        }

        $patient = new Patient();
        $patient->name = $request->name;
        $patient->date_of_birth = $request->date_of_birth;
        $patient->phoneNumber = $request->phoneNumber;
        $patient->age = $request->age;
        $patient->save();

        return response()->json([
            'status' => 200,
            'message' => "Patient Added"
         ]);

    }
}