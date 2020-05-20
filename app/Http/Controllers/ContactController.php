<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\NewsModel;
use App\Model\RegistrationModel;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function showLoginPage() {
        return view('login');
    }

    public function createNews() {
        $id = request('id');
        $news = request('news');
        $newsModel = new NewsModel();
        if($id > 0) {
            $newsModel -> updateNews($id, $news);


        }
        else {
            $newsModel -> createNews($news);
        }
        return response()->json($this->configSuccessArray());
    }

    public function createComment() {
        $id = request('id');
        $comment = request('comment');
        $newsid = request('newsid');
        $newsModel = new NewsModel();
        if($id > 0) {
            $newsModel -> updateComment($id, $comment);
        }
        else {
            $newsModel -> createComment($comment, $newsid);
        }
        return response()->json($this->configSuccessArray());
    }

    public function showContact() {
        Log::info("In contact");
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);
        $name = $name."/".$type;
        return view('contact', [
            'navmenu1'  => 'contact',
            'admin'  => $name,

        ]);
    }



    public function showNews() {
        $name = session() -> get(SESS_USERNAME);
        $type = session() -> get(SESS_USERTYPE);
        $name = $name."/".$type;
        $newsModel = new NewsModel();
        $news = $newsModel -> getNews();
        Log::alert($news);
        $answer = array();
        $id = 0;
        foreach($news as $new) {
            $indi = array();
            $val = 0;
            $indi[$val] = $new;
            $comment = $newsModel -> getComment($new -> id);
            Log::info($comment);
            $val ++;
            $indi[$val] = $comment;
            $answer[$id] = $indi;
            $id ++;
        }
        Log::info($answer);
        return view('news', [
            'navmenu1'  => 'news',
            'admin'  => $name,
            'answer' => $answer,
            'type'   => $type
        ]);
    }

    public function deleteNews() {
        $id = request('id');
        $newsModel = new NewsModel();
        $newsModel -> deleteNews($id);
    }

    public function deleteComment() {
        $id = request('id');
        $newsModel = new NewsModel();
        $newsModel -> deleteComment($id);
    }



    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
