<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Polls;
use App\Models\Answers;
use App\Models\follow;
use DB;

class pollController extends Controller
{

    public function createPoll(Request $request){
        $poll = new Polls;
        if($user = Auth::user())
            $id = $user->id;
        else
            $id = null; 
        $data = ['question' => $request->question, 'multivote' => $request->multivote, 'user_id' => null];
        $id = $poll->insertGetId($data);

        foreach($request->answers as $key => $value)
        {
            if($value != '')
            {
                $data = ['answer' => $value, 'poll_id' => $id, 'votes' => 0];
                $answer = new Answers;
                $answer->insert($data);
            }
        }
        
        return $id;
    }

    public function getPoll(){
        $id = $_GET['p'];
        $poll = DB::table('polls')->select('question', 'multivote')->where('id', $id)->get();
        $answers =  DB::table('answers')->select('answer', 'votes')->where('poll_id', $id)->get();

        return view('poll')->with('poll', $poll)->with('answers', $answers);
    }

    public function vote(Request $request){
        
        if(is_array($request->answers))
            foreach($request->answers as $key => $value)
            {
                $poll = DB::table('answers')->where('poll_id', $request->id)->where('answer', $value)->increment('votes');
            }
        else
            $poll = DB::table('answers')->where('poll_id', $request->id)->where('answer', $request->answers)->increment('votes');
            

        setcookie('has_voted_'.$request->id, true, time()+31556926);
    
        return $poll;
    }

    public function userPolls() {
        $user = Auth::user();
        $polls = DB::table('polls')->select('id', 'question')->where('user_id', $user->id)->get();

        return view('my_polls')->with('id', $polls);
    }

    public function followPolls(Request $request) {
        if($user = Auth::user())
        {
            $exists = array();
            $exists = DB::table('follow')->where('poll_id', $request->poll_id)->where('user_id', $user->id)->get();
            if(count($exists) == 1)
            {
                DB::table('follow')->where('poll_id', $request->poll_id)->where('user_id', $user->id)->delete();
                return 'false';
            }else{
                $follow = new follow;
                $data = ['user_id' => $user->id, 'poll_id' => $request->poll_id];
                $follow->insert($data);
                return 'true';
            }
        }
    }

    public function getFollowPolls() {
        if($user = Auth::user())
        {
            $polls = DB::table('follow')->join('users', 'users.id', '=', 'follow.user_id')->join('polls', 'polls.id', '=', 'follow.poll_id')->select('polls.id', 'question', 'users.name')->where('follow.user_id', $user->id)->get();
            return view('my_follow_polls')->with('p', $polls);
        }
    }
}
