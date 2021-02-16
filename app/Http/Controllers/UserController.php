<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('agreement')->except(['agreement_form', 'agreement_submit']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$users = User::with(['department','major'])->get();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        /*
         @Rayan you can access $user varebal from view for Ex:
             to print user name:
         {{$user->name}}
            to print department:
        {{$user->department->name}}
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
        $user = Auth::user();

        return view('user.form')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $userData = $this->validate($request, [
            "email"             => "required|email",
            "identity"          => "required|mimes:pdf,png,jpg,jpeg|max:4000",
            "degree"            => "required|mimes:pdf,png,jpg,jpeg|max:4000",
            "payment_receipt"   => "required_if:traineeState,trainee,employee,employeeSon|mimes:pdf,png,jpg,jpeg|max:4000",
            "traineeState"      => "required",
        ],[
            'payment_receipt.required_if' => 'إيصال السداد مطلوب'
        ]);

        $national_id = Auth::user()->national_id;

        $img_name = 'identity.' . $userData['identity']->getClientOriginalExtension();
        Storage::disk('userDocuments')->put('/' . $national_id . '/' . $img_name, File::get($userData['identity']));

        $img_name = 'degree.' . $userData['degree']->getClientOriginalExtension();
        Storage::disk('userDocuments')->put('/' . $national_id . '/' . $img_name, File::get($userData['degree']));

        if ($userData['traineeState'] != 'privateState') {
            $img_name = 'payment_receipt.' . $userData['payment_receipt']->getClientOriginalExtension();
            Storage::disk('userDocuments')->put('/' . $national_id . '/' . $img_name, File::get($userData['payment_receipt']));
        }

        try {
            Auth::user()->update($userData);
            return redirect('/home');
            // return back()->with('success', ' تم تحديث المعلومات بنجاح');    

        } catch (\Throwable $e) {
            return back()->with('error', ' تعذر تحديث بيانات المستخدم حدث خطأ غير معروف '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function agreement_form()
    {
        $user = Auth::user();
        // if (Hash::check("bct12345", $user['password'])) {
        //     return redirect(route('UpdatePasswordForm'))->with('info', 'يرجى تغيير كلمة المرور الافتراضية');
        // }
        if ($user->agreement == 1) {
            return redirect(route('EditOneUser'));
        } else {
            $error =  'يجب الموافقة لإكمال التسجيل';
            return view("user.agreement_from")->with(compact('error'));
        }
    }

    public function agreement_submit(Request $request)
    {
        // dd($request);
        if ($request->input('agree') == 1) {

            $user = Auth::user();
            try {
                $user->update(['agreement' => true]);
            } catch (\Throwable $ex) {
                return back()->with('error', 'خطأ أثناء اعتماد الموافقة');
            }
            return redirect(route('EditOneUser'));
        } else {
            return redirect(route('AgreementForm'))->with('error', 'يجب الموافقة على الشروط اولا');
        }
    }



    public function UpdatePasswordForm()
    {
        return view('user.update_password');
    }


    public function UpdatePassword(Request $request)
    {
        $newpass = $this->validate($request, [
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($newpass['password'] != "bct12345") {
            Auth::user()->update([
                'password' => Hash::make($newpass['password']),
            ]);

            return redirect(route('AgreementForm'))->with('succuss', 'تم تغيير كلمة المرور بنجاح');
        } else {
            return back()->with('error', 'خطأ يجب تغيير كلمة المرور الفتراضية');
        }

        return back()->with('error', 'تعذر تغيير كلمة المرور حدث خطأ غير معروف');
    }
}
