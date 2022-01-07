<?php


namespace App\Helper;

use App\Admin\Ambulance;
use App\Admin\Appointment;
use App\Admin\Department;
use App\Admin\Doctor;
use App\Admin\Donor;
use App\Admin\Hospital;
use App\Admin\Schedule;
use App\Admin\Slider;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use PhpParser\Comment\Doc;

class Common
{
    const MESSAGE_ERROR = "Failed To Store Data";
    const MESSAGE_SUCCESS = "Data has been successfully stored";
    const MESSAGE_UPDATE_SUCCESS = "Data has been updated successfully";
    const MESSAGE_UPDATE_ERROR = "Failed to update !!";
    const MESSAGE_DELETE_SUCCESS = "Data has been deleted successfully";
    const MESSAGE_DELETE_ERROR = "Failed to delete !! ";
    const MESSAGE_VALIDATE = "Validation Error !! ";

    public static function _saveFiles($image, string $directory)
    {
        $path = 'image/' . $directory;
        if (!File::exists($path)) {
            File::makeDirectory($path, false, false);
        }
        $fileType = $image->getClientOriginalExtension();
        $imageName = rand() . '.' . $fileType;
        $path_info = pathinfo($imageName, PATHINFO_EXTENSION);
        $directory = $path . "/";
        if ($path_info == 'pdf' || $path_info == 'docx') {
            $imageUrl = $directory . $imageName;
            $image->move($directory, $imageName);
        } else if ($path_info == "png" || $path_info == 'jpeg' || $path_info == "jpg" || $path_info == "PNG" || $path_info == "JPEG" || $path_info == "JPG") {
            $imageUrl = $directory . $imageName;
            Image::make($image)->save($imageUrl);
        } else {
            $imageUrl = "No Valid File";
        }
        return $imageUrl;
    }

    public static function _deleteFiles($file)
    {
        File::delete($file);
    }

    public static function _response($data, $error, $message)
    {
        if ($error) {
            return 'error';
        } else {
            return 'success';
        }
//        }
//        if ($error) {
//            $response['error'] = true;
//            $response['data'] = null;
//        } else {
//            $response['error'] = false;
//            $response['data'] = $data;
//        }
//        $response['message'] = $message;
//        return response()->json($response);
    }

    public static function _insert(Request $request, $model)
    {
        if ($request->hasFile('photo')) {
            $request['image'] = self::_saveFiles($request->photo, $model);
            $request = collect($request);
            //$request->forget('photo');
        }
//        if ($request->get('_token')) {
//            $request->forget('_token');
//        }
        if ($request->get('photo')) {
            $requestedData = $request->except('_token', 'photo')->toArray();
        } else {
            $requestedData = $request->except('_token', 'photo');
        }
        if (!empty($request['id'])) {
            switch ($model) {
                case 'doctor':
                    if ($data = Doctor::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'hospital':
                    if ($data = Hospital::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'department':
                    if ($data = Department::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'appointment':
                    if ($data = Appointment::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'schedule':
                    if ($data = Schedule::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'ambulance':
                    if ($data = Ambulance::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'donor':
                    if ($data = Donor::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                case 'slider':
                    if ($data = Slider::find($request['id'])) {
                        if ($request['image']) {
                            self::_deleteFiles($data->image);
                        }
                        $data->update($requestedData);
                        return self::_response('', false, self::MESSAGE_UPDATE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_UPDATE_ERROR);
                default:
                    break;
            }
        } else {
            switch ($model) {
                case 'doctor':
//                    $validateData['name'] = $requestedData['name'];
//                    $validateData['email'] = $requestedData['email'];
//                    try {
//                        if (Common::validator($validateData)->validate()->fails()) {
//                            dd('hi');
//                            return self::_response('', true, self::MESSAGE_VALIDATE);
//                        }
//                    } catch (ValidationException $e) {
//                        dd($e);
//                        return self::_response('', true, $e);
//                    }
                    $name = $requestedData['name'];
                    $email = $requestedData['email'];

                    $role = $requestedData['role'];
                    $mobile = $requestedData['mobile'];
                    $password = Hash::make($requestedData['password']);
                    $checkEmail = User::where('email', $email)->first();
                    if ($checkEmail)
                    {
                        return self::_response('', true, self::MESSAGE_VALIDATE);
                    }
                    $newUser = new User();
                    $newUser->name = $name;
                    $newUser->email = $email;
                    $newUser->password = $password;
                    $newUser->mobile = $mobile;
                    $newUser->role = $role;
                   if ($newUser->save()){
                       $newDoctor = new Doctor();
                       $newDoctor->name = $requestedData['name'];
                       $newDoctor->department_id = $requestedData['department_id'];
                       $newDoctor->hospital_id = $requestedData['hospital_id'];
                       $newDoctor->user_id = $newUser->id;
                       $newDoctor->fee = $requestedData['fee'];
                       $newDoctor->designation = $requestedData['designation'];
                       $newDoctor->image = $requestedData['image'];
                       $newDoctor->day = $requestedData['day'];
                       if ($newDoctor->save()) {
                           return self::_response('', false, self::MESSAGE_SUCCESS);
                       }
                   }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'hospital':
                    if (Hospital::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'department':
                    if (Department::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'appointment':
                    if (Appointment::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'schedule':
                    if (Schedule::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'ambulance':
                    if (Ambulance::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'donor':
                    if (Donor::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                case 'slider':
                    if (Slider::insert($requestedData)) {
                        return self::_response('', false, self::MESSAGE_SUCCESS);
                    }
                    return self::_response('', true, self::MESSAGE_ERROR);
                default:
                    break;
            }
        }

    }

    public static function _delete($data, $model)
    {
        switch ($model) {
            case 'doctor':
                if (Doctor::find($data->id)) {
                    Doctor::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'hospital':
                if (Hospital::find($data->id)) {
                    Hospital::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'department':
                if (Department::find($data->id)) {
                    Department::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'appointment':
                if (Appointment::find($data->id)) {
                    Appointment::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'schedule':
                if (Schedule::find($data->id)) {
                    Schedule::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'ambulance':
                if (Ambulance::find($data->id)) {
                    Ambulance::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'donor':
                if (Donor::find($data->id)) {
                    Donor::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            case 'slider':
                if (Slider::find($data->id)) {
                    Slider::find($data->id)->delete();
                    return self::_response('', false, self::MESSAGE_DELETE_SUCCESS);
                }
                return self::_response('', true, self::MESSAGE_DELETE_ERROR);
            default:
                break;
        }
    }

    public static function _notify($data)
    {
        if ($data == 'success') {
            return back()->with(array(
                'message' => "Successfully Done",
                'alert-type' => $data
            ));
        }
        return back()->with(array(
            'message' => "Failed !!!",
            'alert-type' => $data
        ));
    }
    public static function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

}
