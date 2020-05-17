<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Hash;
use Hash;
use App\Post;

class UserController extends Controller
{
    public function index(Request $request){
        $authUser = Auth::user();
        $user_id = Auth::id();
        $items = Post::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        $param = [
            'authUser'=>$authUser,
            'items'=>$items,
        ];

        return view('user.index',$param);
    }

    
    public function userEdit(Request $request){
        $authUser = Auth::user();
        $param = [
            'authUser'=>$authUser,
        ];
        return view('user.userEdit',$param);
    }

    public function userUpdate(Request $request){
        // Validator check
        $rules = [
            'id' => 'integer|required',
            'name' => 'required',
            'email' => 'required',
        
           

        ];
        $messages = [
            'id.integer' => 'SystemError:システム管理者にお問い合わせください',
            'id.required' => 'SystemError:システム管理者にお問い合わせください',
            'name.required' => 'ユーザー名が未入力です',
            'email.required' => 'メールアドレスが未入力です',
            
        ];
        $validator = Validator::make($request->all(),$rules,$messages);

        if($validator->fails()){
            return redirect('/user/userEdit')
                ->withErrors($validator)
                ->withInput();
        }

        
        $uploadfile = $request->file('thumbnail');

        if(!empty($uploadfile)){
            $user = User::find($request->id);
            $delimgname = $user->thumbnail;
            Storage::delete('public/user/' . $delimgname);

            $thumbnailname = $request->file('thumbnail')->hashName();
            $request->file('thumbnail')->storeAs('public/user', $thumbnailname);

            $param = [
                'name'=>$request->name,
                'email'=>$request->email,
                'thumbnail'=>$thumbnailname,
            ];
        }else{
            $param = [
                'name'=>$request->name,
                'email'=>$request->email,
                    
            ];
        }

        User::find($request->id)->update($param);
        return redirect(route('user.userEdit'))->with('success', '保存しました。');
    }

    

    public function showChangePasswordForm(){
        return view('auth.changepassword');
    }

    public function changePassword(Request $request){
        if(!(Hash::check($request->get('current-password'), Auth::user()->password))){
            return redirect()->back()->with('change_password_error', '現在のパスワードが間違っています。');
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            return redirect()->back()->with('change_password_error', '新しいパスワードが現在のパスワードと同じです。違うパスワードを設定してください。');
        }

        $validator = Validator::make($request->all(), [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return redirect('/user/changepassword')
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();

        return redirect()->back()->with('change_password_success', 'パスワードを変更しました。');

    }

    
    public function delete(Request $request){
        return view('user.delete');
    }

    public function remove(Request $request){
        $user = Auth::user();
        $delthumbnail = $user->thumbnail;
        Storage::delete('public/user/' . $delthumbnail);

        $id = Auth::id();
        $deleteposts = Post::where('user_id', $id);
        if(!empty($deleteposts)){
            $delposts = Post::where('user_id', $id)->get();
            foreach($delposts as $delpost){
                $delimage = $delpost->image;
                Storage::delete('public/post/' . $delimage);
            }
            $deleteposts->delete();
        }

        User::find($id)->delete();
        return redirect('/');
    }




}
