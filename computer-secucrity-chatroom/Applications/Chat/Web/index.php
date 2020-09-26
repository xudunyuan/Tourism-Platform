<?php
session_start();
if(!isset($_SESSION['username'])||empty($_SESSION['username'])){
    header('../../../login_register.php');
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css"
          rel="stylesheet">
    <link rel="stylesheet" href="ui.css">
</head>

<body>
<div class="container">
    <h3 class=" text-center">Messaging</h3>
    <div class="messaging">
        <div class="inbox_msg">
            <div class="inbox_people">
                <div class="headind_srch">
                    <div class="recent_heading">
                        <h4>Recent</h4>
                    </div>
                    <div class="srch_bar">
            <span class="stylish-input-group">
              <input type="text" class="search-bar" placeholder="Search">
              <span class="input-group-addon">
                  <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span>
            </span>
                        <span>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_modal">Private Chat Select</button>
              </span>
                    </div>
                </div>
                <div class="inbox_chat"></div>
            </div>
            <div class="mesgs">
                <div class="msg_history">
                </div>
                <div class="type_msg row">
                    <div class="input_msg_write col-9">
                        <input type="text" class="write_msg" placeholder="Type a message"/>
                        <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                    </div>
                    <div class="col-3">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="contact_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">add_contact</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select id="client_list" class="custom-select"></select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="save_contact" type="button" class="btn btn-primary" data-dismiss="modal">save contact</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="js/crypto-js.js"></script>
<script type="text/javascript" src="js/jsencrypt.js"></script>
<script type="text/javascript" src="js/jsencrypt.min.js"></script>
<script>

if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
var ws, name, client_list={}, encrypt, key, iv, encrypt;
function base64EncodeUnicode(str) {
// First we escape the string using encodeURIComponent to get the UTF-8 encoding of the characters, 
// then we convert the percent encodings into raw bytes, and finally feed it to btoa() function.
    utf8Bytes = encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    });
    return btoa(utf8Bytes);
}
function base64DecodeUnicode(str) {
// Going backwards: from bytestream, to percent-encoding, to original string.
    return decodeURIComponent(atob(str).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}
function base64toHEX(base64) {
    var raw = atob(base64);
    var HEX = '';
    for ( i = 0; i < raw.length; i++ ) {
        var _hex = raw.charCodeAt(i).toString(16)
        HEX += (_hex.length==2?_hex:'0'+_hex);
    }
    return HEX.toUpperCase();
}
// 加密函数，加密算法：AES-256-CBC
function aesEncrypt(message, key, iv) {
    var ciphertext = CryptoJS.AES.encrypt(message, CryptoJS.enc.Hex.parse(key), 
    {
        iv: CryptoJS.enc.Hex.parse(iv),
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });
    return ciphertext.toString();
}
// 解密函数
function aesDecrypt(ciphertext, key, iv) {
    var decrypted = CryptoJS.AES.decrypt(ciphertext, CryptoJS.enc.Hex.parse(key), 
    {
        iv: CryptoJS.enc.Hex.parse(iv),
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });
    return decrypted.toString(CryptoJS.enc.Utf8);
}
class chatConfig {
    classSelf;
    constructor() {
        this.classSelf=this;
        this.user_name = null;
        this.user_list = {};

        this.websocket = new WebSocket(`ws://${document.domain}:7272`);
        this.websocket.onopen = this.onopen.bind(this);
        this.websocket.onmessage = this.onmessage.bind(this);
        this.websocket.onclose = this.onclose.bind(this);
        this.websocket.onerror = this.onerror.bind(this);

        this.gen_group_list();

        document.querySelector('.msg_send_btn').addEventListener('click',this.onSubmit.bind(this.classSelf));

    }

    /**
     * Gets the ping json.
     *
     * @return     {<string>}  The ping json.
     */
    get_ping_json() {
        let data_obj = {};
        data_obj.type = "pong";
        return JSON.stringify(data_obj);
    }

    /**
     * Gets the login data json.
     *
     * @return     {<string>}  The login data json.
     */
    get_login_data_json() {
        encrypt = new JSEncrypt({default_key_size: 1024});
        console.log(encrypt.getPublicKey());
        let data_obj = {};
        data_obj.type = "login";
        data_obj.client_name = this.user_name;
        data_obj.room_id = <?php echo isset($_GET['room_id']) ? $_GET['room_id'] : 1; ?>;
        data_obj.pubKey = encrypt.getPublicKey().replace(/\n/g,"?");
        return JSON.stringify(data_obj);
    }

    /**
     * customize the websocket onopen event
     *
     * @return {<void>}
     */
    onopen() {
        this.user_name= "<?php echo !isset($_SESSION['username'])|| empty($_SESSION['username'])?'illegal':'default';?>";

        if (!this.user_name||this.user_name==='illegal') {
            window.location="../../../login_register/index.php";
        }
        else{
            this.user_name = prompt('Please input your Nickname', '');
            if (!this.user_name || this.user_name === 'null') {
                this.user_name = Math.floor(Math.random() * 10);
            }
            let data = this.get_login_data_json();
            console.log(data);
            this.websocket.send(data);
        }
        
    }


    /**
     * customize the websocket onmessage event
     *
     * @param      {<string>}  e       { JSON data received from server }
     */
    onmessage(e) {
        console.log("on_message"+e.data);
        let data=JSON.parse(e.data);
        if(data['type']==='ping'){
            this.websocket.send(this.get_ping_json());
        }
        else if (data['type'] === 'login') {
            console.log(data['client_list']);
            this.send_msg(data['client_id'], data['client_name'], data['client_name'] + ' is now in chatroom', data['time']);
            if (data['client_list']) {
                this.client_list = data['client_list'];
            } else {
                //first user to login
                this.client_list[data['client_id']] = data['client_name'];
            }
            this.refresh_client_list();
            console.log(data['client_name'] + "login successfully");
        }
        else if(data['type']==='send_msg'){
            let content = aesDecrypt(data['content'], key, iv);
            // console.log(name + " received: "+ content);
            this.send_msg(data['from_client_id'], data['from_client_name'], content, data['time']);
        }
        else if(data['type']==='received_msg'){
            let content = aesDecrypt(data['content'], key, iv);
            this.received_msg(data['from_client_id'], data['from_client_name'], content, data['time'])
        }
        else if(data['type']==='logout'){
            this.send_msg(data['from_client_id'], data['from_client_name'], `${data['from_client_name']} just left `, data['time']);
                delete this.client_list[data['from_client_id']];
                this.refresh_client_list();
        }
        else if(data['type']=='shareKey'){
            //{"type":"shareKey", "key":"xxx"}
            var encrypted_key_base64 = data['key'].replace("\\","");
            var encrypted_iv_base64 = data['iv'].replace("\\","");
            console.log("Encrypted Key: " + encrypted_key_base64);
            console.log("Encrypted IV: " + encrypted_iv_base64);
            // var encrypted_key = base64DecodeUnicode(encrypted_key_base64);
            iv = encrypt.decrypt(encrypted_iv_base64);
            key = encrypt.decrypt(encrypted_key_base64);
            console.log("key: " + key);
            console.log("IV: " + iv);
            key = base64toHEX(key);
            iv = base64toHEX(iv);
            console.log("key (Hex Value): " + key);
            console.log("IV (Hex Value): " + iv);
        }
    }

    received_msg(client_id, client_name, content, time) {
        let message_box=document.querySelector('.write_msg');
        let msg_history=document.querySelector('.msg_history');
        msg_history.appendChild(this.gen_incoming_chat_html(content,time,client_name));
    }

    send_msg(client_id, client_name, content, time) {
        let message_box=document.querySelector('.write_msg');
        let msg_history=document.querySelector('.msg_history');
        msg_history.append(this.gen_outgoing_chat_html(content,time))
    }

    gen_outgoing_chat_html(content,time){
        let outer_div=document.createElement('div');
        let inner_div=document.createElement('div');
        let para=document.createElement('p');
        let span=document.createElement('span');
        span.classList.add('time_date');

        outer_div.classList.add('outgoing_msg');
        inner_div.classList.add('sent_msg');
        para.innerHTML=content;
        span.innerText=time;

        inner_div.appendChild(para);
        inner_div.appendChild(span);
        outer_div.appendChild(inner_div);
        return outer_div;
    }

    gen_incoming_chat_html(content,time,client_name){
        let outer_div=document.createElement('div');
        let inner_icon_div=document.createElement('div');
        let inner_text_wrapper_div=document.createElement('div');
        let inner_text_wrapper2_div=document.createElement('div');
        let client_name_block=document.createElement('B');
        client_name_block.innerHTML=`${client_name} <br>`;
        let para=document.createElement('p');
        let span=document.createElement('span');
        let icon=document.createElement('img');

        span.classList.add('time_date');
        inner_text_wrapper2_div.classList.add('received_withd_msg');
        inner_text_wrapper_div.classList.add('received_msg');

        icon.src="https://ptetutorials.com/images/user-profile.png";
        para.innerHTML=content;
        span.innerText=time;

        inner_text_wrapper2_div.appendChild(client_name_block);
        inner_text_wrapper2_div.appendChild(para);
        inner_text_wrapper2_div.appendChild(span);
        inner_text_wrapper_div.appendChild(inner_text_wrapper2_div);

        inner_icon_div.appendChild(icon);
        inner_icon_div.classList.add("incoming_msg_img");

        outer_div.appendChild(inner_icon_div);
        outer_div.appendChild(inner_text_wrapper_div);

        outer_div.classList.add('incoming_msg');

        return outer_div;
    }

    gen_group_list(){
        let chat_list=document.querySelector('.inbox_chat');
        chat_list.innerHTML="";
        for(let i=0;i<5;i++){
            let name=`room ${i+1}`;
            chat_list.appendChild(this.gen_group_list_html(name,i+1));
        }
    }

    gen_group_list_html(group_name,room_id){
        let outer_div=document.createElement('div');
        let inner_div=document.createElement('div');
        let icon_div=document.createElement('div');
        let contact_div=document.createElement('div');

        outer_div.classList.add('chat_list');
        outer_div.classList.add('active_chat');
        // outer_div.onclick=`'location.href="/?room_id=${room_id}"'`;

        inner_div.classList.add('chat_people');
        icon_div.innerHTML='<img src="https://ptetutorials.com/images/user-profile.png">';
        icon_div.classList.add('chat_img');
        contact_div.classList.add('chat_ib');
        contact_div.innerHTML=`<h5><a href="?room_id=${room_id}">${group_name}
        </a><h5><span class="chat_date"></span></h5>`;

        inner_div.appendChild(icon_div);
        inner_div.appendChild(contact_div);

        outer_div.appendChild(inner_div);

        return outer_div;
    }

    access_non_numeric_key_obj(obj,index){
        let object={
            key:(n)=>{
                return this[Object.keys(this)[n]];
            }
        };
        return object.key.call(obj,index);
    }

    refresh_client_list(){
        let selects=document.getElementById("client_list");
        selects.innerHTML="";
        
        // append a 'all' to the select menu
        let option_all=document.createElement('option');
        option_all.value='all';
        option_all.innerText='all';
        selects.appendChild(option_all);
        

        for (const [key, value] of Object.entries(this.client_list)) {
            let option=document.createElement('option');
            option.value=key;
            option.innerText=value;
            console.log(`k: ${key}, v: ${value}`);
            selects.appendChild(option);
        }
    }
    onSubmit() {
        let send_content = document.querySelector('.write_msg').value;
        let to_client_id=document.getElementById("client_list").value;
        let to_client_name=$('#client_list :selected').text();
        if(!send_content||send_content===''){
            return;
        }
        this.websocket.send(this.get_send_msg_json(to_client_id,to_client_name,send_content));
    }
    get_send_msg_json(to_client_id, to_client_name, content) {
        let data_obj = {};
        let enc_content = aesEncrypt(content, key, iv);
        data_obj.type = "send_msg";
        data_obj.to_client_id=to_client_id;
        data_obj.to_client_name=to_client_name;
        data_obj.content=enc_content;
        return JSON.stringify(data_obj);
    }
    onclose(){
        console.log('connection closing');
    }
    onerror(){
        console.log("error");
    }
}
new chatConfig();
</script>
</body>

</html>
