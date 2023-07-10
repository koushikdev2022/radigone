<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\SupportAttachment;
use App\SupportMessage;
use App\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function storeSupportTicket(Request $request)
    {
        try {
            $ticket = new SupportTicket();
            $message = new SupportMessage();

            $files = $request->file('attachments');
            $allowedExts = array('jpg', 'png', 'jpeg', 'pdf','doc','docx');


            $validateUser = Validator::make($request->all(),
                [
                    'attachments' => [
                        'max:4096',
                        function ($attribute, $value, $fail) use ($files, $allowedExts) {
                            foreach ($files as $file) {
                                $ext = strtolower($file->getClientOriginalExtension());
                                if (($file->getSize() / 1000000) > 2) {
                                    return $fail("Images MAX  2MB ALLOW!");
                                }
                                if (!in_array($ext, $allowedExts)) {
                                    return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
                                }
                            }
                            if (count($files) > 5) {
                                return $fail("Maximum 5 files can be uploaded");
                            }
                        },
                    ],
                    'subject' => 'required|max:100',
                    'message' => 'required',
                ]);

            if($validateUser->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 500);
            }


            $ticket->user_id = Auth::guard('api')->id();
            $random = rand(100000, 999999);
            $ticket->ticket = $random;
            $ticket->name = Auth::guard('api')->user()->firstname.' '.Auth::guard('api')->user()->lastname;
            $ticket->email = Auth::guard('api')->user()->email;
            $ticket->subject = $request->subject;
            $ticket->last_reply = Carbon::now();
            $ticket->status = 0;
            $ticket->save();

            $message->supportticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();


            $path = imagePath()['ticket']['path'];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as  $file) {
                    try {
                        $attachment = new SupportAttachment();
                        $attachment->support_message_id = $message->id;
                        $attachment->attachment = uploadFile($file, $path);
                        $attachment->save();
                    } catch (\Exception $exp) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Could not upload your '.$file,
                            'data' => null,
                        ]);
                    }
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'ticket created successfully!',
                'data' => null,
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ]);
        }

    }

    public function supportTicket()
    {
        $supports = SupportTicket::where('user_id', Auth::guard('api')->id())->with('supportMessage', 'supportMessage.attachments')
            ->latest()
            ->paginate(getPaginate());
        $custom = collect([
            'success' => 'true',
            'message' => null,
        ]);
        return $custom->merge($supports);
    }
}
