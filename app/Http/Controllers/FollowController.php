<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Follow;

class FollowController extends Controller
{
    public function index()
    {   
        $logged_user_id = Auth::User()->id;
        $count = Follow::where('user_id' , $logged_user_id)->count();
        return $count;
    }
    public function follow($id)
    {
        try {

            $user = User::find($id); 
            $user_id = $user->id;   
            $logged_user_id = Auth::User()->id;
    
            // check if user and following user id is not the same ,
            if ($user_id == $logged_user_id) 
            {
                return [
                    'status' => false,
                    'message' => 'You can not Follow UnFollow yourself'
                ];
            }
            if ($user_id && $logged_user_id)
            {
                // check if user is not already followed,
                $check = Follow::where('user_id', $user_id)->where('follower_id' , $logged_user_id)->count();
                
                if ($check == 0)
                {
                    $follow = new Follow ;
                    $follow->user_id = $user_id ;
                    $follow->follower_id = $logged_user_id ;
                    if ($follow->save())
                    {
                        return [
                            'status' => true,
                            'message' => 'You have followed',$user->name
                        ];
                    }
                }
                else
                { 
                    return [
                        'status' => false,
                        'message' => 'You have already followed',$user->name
                    ];
                }
            }

        }catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'Something went wrong, please try again'
            ];
        }
    }

        public function unfollow($id)
        {
            try {

            $user = User::find($id); 
            $user_id = $user->id;   
            $logged_user_id = Auth::User()->id;
    
            // check if user and following user id is not the same ,
            if ($user_id == $logged_user_id) 
            {
                return [
                    'status' => false,
                    'message' => 'You can not Follow/UnFollow yourself'
                ];
            }
            if ($user_id && $logged_user_id)
            {
                // check if user is followed,
                $check = Follow::where('user_id', $user_id)->where('follower_id' , $logged_user_id)->count();
                
                if ($check == 1)
                {
                    $delete_follow = Follow::where('user_id',$user_id)->where('follower_id',$logged_user_id)->delete() ;
                    if ($delete_follow)
                    {
                        return [
                            'status' => true,
                            'message' => 'You have unfollowed',$user->name
                        ];
                    }
                }
                else
                { 
                    return [
                        'status' => false,
                        'message' => 'You have not followed',$user->name,
                    ];
                }
            }

        }catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'Something went wrong, please try again'
            ];
        }
            
    }

}
