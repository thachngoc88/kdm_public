<?php

namespace App\Http\Controllers;

use App\Condition;
use App\Curriculum;
use App\Message;
use Illuminate\Http\Request;
use Validator;

class MessagesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curriculums = Curriculum::with('timings.conditions.messages')->get();
        return view('messages', ['curriculums' => $curriculums]);
    }

    public function updateMsg(Request $request, $id)
    {
        try {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), ['msg' => 'required|max:256',]);
                if ($validator->fails()) {
                    return response(['error' => true, 'data' => "メッセージの内容が不正です", 'status_code' => 422]);
                }

                $msgText = $request->input('msg');
                if (!empty($id)) {
                    $msg = Message::find($id);
                    $msg->text = $msgText;
                    $msg->save();
                }
                return response(['error' => false, 'data' => "メッセージを更新しました", 'status_code' => 200]);
            }
        } catch (Exception $e) {
            return response(['error' => true, 'data' => $e->getMessage(), 'status_code' => $e->getCode()]);
        }
    }

    public function updateCond(Request $request, $id)
    {
        try {
            if ($request->isMethod('post')) {
                $time_from = $request->input('timeFrom');
                $time_until = $request->input('timeUntil');

                $cond = Condition::find($id);
                $cond->time_from = empty($time_from) ? null : date("Y-m-d H:i:s", strtotime($time_from));
                $cond->time_until = empty($time_until) ? null : date("Y-m-d H:i:s", strtotime($time_until));
                $cond->save();

                return response(['error' => false, 'data' => "条件時間を更新しました", 'status_code' => 200]);
            }
        } catch (Exception $e) {
            return response(['error' => true, 'data' => $e->getMessage(), 'status_code' => $e->getCode()]);
        }
    }


}
