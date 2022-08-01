<?php

namespace App;
use Illuminate\Http\Request;

class FCMPushNotification {
     public function sendNotification(Request $req){
         $url = 'https://fcm.googleapis.com/fcm/send';
         // $dataArr = array('click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'id' => $req->id,'status'=>"done");
         // $notification = array('title' =>$req->title, 'body' => $req->body, 'image'=> $req->img, 'sound' => 'default', 'badge' => '1');
         

         $dataArr = array("title"  => $_POST["title"], "body" => $_POST["body"] , "screen" => $_POST['screen'], "statut" => $_POST['statut'], 'click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'status'=>"done");
         $notification = array('title' => $_POST["title"], 'body' => $_POST["body"], 'image'=> $_POST["image"], 'sound' => 'default', 'badge' => '1', 'click_action' => 'FLUTTER_NOTIFICATION_CLICK');

         $arrayToSend = array('to' => $_POST["token"], 'notification' => $notification, 'data' => $dataArr, 'priority'=>'high');
         $fields = json_encode ($arrayToSend);
         $headers = array (
             'Authorization: key=' . "AAAAvQfXlDs:APA91bE6PoIUqaOTZcwCj98u37K6Su49Wtg8rIMT0GiQP_obKNNXhCyhiqJ4gFxKSCjJdtELM-YQiwfsBX8-zFLClwy5BRsus79B2mJN5WGYLX3GspPWRBHIpzCVR4ucmPN4r9UcAR1N",
             'Content-Type: application/json'
         );

         $ch = curl_init ();
         curl_setopt ( $ch, CURLOPT_URL, $url );
         curl_setopt ( $ch, CURLOPT_POST, true );
         curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
         curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
         curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
         curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
         curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
         
         $result = curl_exec ( $ch );
         if ($result === false) 
            $result = curl_error($ch);

        echo stripslashes($result);

         //var_dump($result);
         curl_close ( $ch );
         // return $result;
         return response()->json(["msg" => "Notif sended", "result" => $result]);

     }
}