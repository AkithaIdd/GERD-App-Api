<?php

namespace App\Http\Controllers\Api;
use App\Models\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Validator;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function addimage(Request $request)
    {
        $validator = Validator::make($request->all(),[
    
            "image" => "required",
            "doctor_id" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
               'status' => 400,
               'message' => "Missing Image Or Doctor Id"
            ]);
        }
        
        $image = new Image;
        // $image->doctor_id = $request->doctor_id;

        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time().'.'.$extension;
            $path = public_path().'/assets/profileImages';
            $uplaod = $file->move($path,$fileName);

            $image->uri = $uplaod;          

            // $path = $request->file('image')->store('images');
            // $image->uri = $path;
        }

        $user = Image::updateOrCreate(
            ['doctor_id' => $request->doctor_id],
            ['uri' =>  $fileName]

        );
        
       

        // $image->updateOrCreate();



        return response()->json([
            'status' => 200,
            'message' => "Image Uploaded",
         ]);
    } 

    public function getImage($id)
    {
        $doc_image = Image::where('doctor_id',"$id")->pluck('uri')->first();
        $file_basename = basename($doc_image);
        $path ='http://192.168.8.158:8083/assets/profileImages/';
        $image_path = $path.$file_basename;

        $user = Image::where('doctor_id',"$id")->pluck('uri')->first();
        if($user !== null){
            // return Response::download($image_path);
            return response()->json([
                'status' => 200,
                'imageUrl' => $image_path,
             ]);
        }else{
            return response()->json([
                'status' => 400,
                'message' => "User Id Invalid",
             ]);
        }

        
        
    } 
}
