<?php
define('AES_256_CBC', 'aes-256-cbc');
use \GatewayWorker\Lib\Gateway;

class Events
{
   public static function onMessage($client_id, $message)
   {
        // debug message
        echo "client_id:".$client_id." onMessage: ".$message;        
        $message_data = json_decode($message, true);

        // stop when receiving empty message
        if(!$message_data||empty($message_data))
        {
            return ;
        }
        
        
        switch($message_data['type'])
        {
            // keep connection stable
            case 'pong':
                return;
            
            
            case 'login':
                // login message without a room, which is an error
                if(!isset($message_data['room_id']))
                {
                    echo "\$message_data['room_id'] is empty which is not allowed\n";
                    exit(1);
                }
                
                $room_id = $message_data['room_id'];
                $client_name = htmlspecialchars($message_data['client_name']);
                $_SESSION['room_id'] = $room_id;
                $_SESSION['client_name'] = $client_name;
              
                // retrive all the sessions in the room 
                $clients_list = Gateway::getClientSessionsByGroup($room_id);
                foreach($clients_list as $tmp_client_id=>$item)
                {
                    $clients_list[$tmp_client_id] = $item['client_name'];
                }
                $clients_list[$client_id] = $client_name;
                
                // send to existing group mambers
                $new_message = array('type'=>$message_data['type'], 'client_id'=>$client_id, 'client_name'=>htmlspecialchars($client_name), 'time'=>date('Y-m-d H:i:s'));
                Gateway::sendToGroup($room_id, json_encode($new_message));

                // after sending, then join group
                Gateway::joinGroup($client_id, $room_id);
                $new_message['client_list'] = $clients_list;
                Gateway::sendToCurrentClient(json_encode($new_message));
                
				// 给当前用户分配密钥，加密方式 AES_256_CBC
				$key = openssl_random_pseudo_bytes(32);
				$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
				echo "key: ", base64_encode($key), "\n";
				echo "iv: ", base64_encode($iv), "\n";
				openssl_public_encrypt(base64_encode($key), $encrypted_key, str_replace('?', "\n", $message_data['pubKey']));
				openssl_public_encrypt(base64_encode($iv), $encrypted_iv, str_replace('?', "\n", $message_data['pubKey']));
				$encrypted_key_base64 = base64_encode($encrypted_key);
				$encrypted_iv_base64 = base64_encode($encrypted_iv);
				echo "encrypted key: ", $encrypted_key_base64, "\n";
				$new_message = array('type'=>"shareKey", 'key'=>$encrypted_key_base64, 'iv'=>$encrypted_iv_base64);
				Gateway::sendToCurrentClient(json_encode($new_message));
				$_SESSION['key'] = $key;
				$_SESSION['iv'] = $iv;
				echo "Key (Hex value): ", bin2hex($key), "\n";
                return;
                
            // on send message
            case 'send_msg':
                // sending message without a room, which is an error
                if(!isset($_SESSION['room_id']))
                {
                    echo "\$message_data['room_id'] is empty which is not allowed\n";
                    exit(1);
                }

                $room_id = $_SESSION['room_id'];
                $client_name = $_SESSION['client_name'];
                $client_list = Gateway::getClientSessionsByGroup($room_id);
				$key = $_SESSION['key'];
				$iv = $_SESSION['iv'];
				
				echo "Client: ", $client_name, ", Key: ", base64_encode($key), ", IV: ", base64_encode($iv), "\n";
				$decrypted = openssl_decrypt(base64_decode($message_data['content']), AES_256_CBC, $key, OPENSSL_RAW_DATA, $iv);
                // 私聊
                if($message_data['to_client_id'] != 'all')
                {
					
					$msg = "PRIVATE MESSAGE <br>".nl2br(htmlspecialchars($decrypted));
					$ciphertext = base64_encode(
						openssl_encrypt($msg, 
						AES_256_CBC, 
						$client_list[$message_data['to_client_id']]['key'], 
						$option=OPENSSL_RAW_DATA, 
						$client_list[$message_data['to_client_id']]['iv']
					));
                    $new_message = array(
                        'type'=>'received_msg',
                        'from_client_id'=>$client_id, 
                        'from_client_name' =>$client_name,
                        'to_client_id'=>$message_data['to_client_id'],
                        'content'=>$ciphertext,
                        'time'=>date('Y-m-d H:i:s'),
                    );
                    Gateway::sendToClient($message_data['to_client_id'], json_encode($new_message));
					$msg = "PRIVATE MESSAGE TO ".$message_data['to_client_name']."<br>".nl2br(htmlspecialchars($decrypted));
					$ciphertext = base64_encode(
						openssl_encrypt($msg, 
						AES_256_CBC, 
						$key, 
						$option=OPENSSL_RAW_DATA, 
						$iv
					));
					$new_message['type'] = 'send_msg';
                    $new_message['content'] = $ciphertext;
                    return Gateway::sendToCurrentClient(json_encode($new_message));
                }


              $clients_list = Gateway::getClientSessionsByGroup($room_id);
              foreach($clients_list as $tmp_client_id=>$item){
				$msg = nl2br(htmlspecialchars($message_data['content']));
				$ciphertext = base64_encode(openssl_encrypt(nl2br(htmlspecialchars($decrypted)), 
					AES_256_CBC, 
					$clients_list[$tmp_client_id]['key'], 
					$option=OPENSSL_RAW_DATA, 
					$clients_list[$tmp_client_id]['iv']
				));
				echo "User ID: ", $tmp_client_id, ", Key: ", base64_encode($clients_list[$tmp_client_id]['key']), ", IV: ", base64_encode($clients_list[$tmp_client_id]['iv']), "\n";
                if($tmp_client_id==$client_id){
                  $new_message = array(
                    'type'=>'send_msg',
                      'from_client_id'=>$client_id,
                      'from_client_name' =>$client_name,
                      'to_client_id'=>'all',
                      'content'=>$ciphertext,
                      'time'=>date('Y-m-d H:i:s'),
                  );
                  Gateway::sendToCurrentClient(json_encode($new_message));
                }
                else{
                  $new_message = array(
                      'type'=>'received_msg',
                      'from_client_id'=>$client_id,
                      'from_client_name' =>$client_name,
                      'to_client_id'=>'all',
                      'content'=>$ciphertext,
                      'time'=>date('Y-m-d H:i:s'),
                  );
                  Gateway::sendToClient($tmp_client_id, json_encode($new_message));
                }
              }
        }
   }
   
   /**
    * gateway worker on close event
    */
   public static function onClose($client_id)
   {
       // debug
       echo " client_id: ".$client_id.'has shutdown';
       
    //    check if has a room id
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           $new_message = array('type'=>'logout', 'from_client_id'=>$client_id, 'from_client_name'=>$_SESSION['client_name'], 'time'=>date('Y-m-d H:i:s'));
           Gateway::sendToGroup($room_id, json_encode($new_message));
       }
   }
  
}
