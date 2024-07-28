<?php

namespace App\Http\Controllers\Api;


use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts=Contact::paginate(20);
        return response()->json(['massage'=>'Gelen Kutusu','data'=>$contacts],200);
    }
    public function store(ContactRequest $request)
    {
       
        $ip=request()->ip();

        $contact=Contact::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'subject'=>$request->subject,
            'body'=>$request->body,
            'status'=>$request->status,
            'ip'=>$ip,

        ]);
        return response()->json(['message' => 'Basarili ile Mesaj gönderildi.','data'=>$contact, 200]);   


        

    }
    public function mailSend(ContactRequest $request)
    {
        $subject = $request->input('subject');
        $message = $request->input('message');
        $email = $request->input('email');

        $contact = Contact::where('email',$email)->first();
        

        if(empty($contact)) {
            return response()->json(['message' => 'E-Posta Bulunamadı!'], 401);
        }

        $contact->message = $message;
        

            Mail::to($contact->email)->send(new ContactMail($subject, $contact));

            return response()->json(['message' => 'Mail Gönderildi'], 200);
        
        
    }
}
