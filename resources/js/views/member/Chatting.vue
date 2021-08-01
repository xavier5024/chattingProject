<template>
  <CContainer>
    <privateChat
      v-if="privateChatModal"
      id="privateChatView"
      :privateTo="privateTo"
    />
    <CRow>
      <CCol md="4" class="bg-success py-3">
        <CCard bodyWrapper color="dark">
          <CCardHeader>
            <div class="input-group">
							<input type="text" placeholder="Search..." name="" class="form-control search">
							<div class="input-group-prepend">
								<span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
							</div>
						</div>
          </CCardHeader>
          <CCardBody style="height:500px;">
            <CListGroup :flush="true" style="overflow:auto">
              <CListGroupItem href="#" v-for="(user, idx) in users" :key="idx" >
                <div class="d-flex bd-highlight" @click="privateChat(user)">
                  <div class="img_cont">
                    <img :src="user.profile_src" class="rounded-circle user_img" >
                    <span class="online_icon"></span>
                  </div>
                  <div class="user_info">
                    <span v-text="user.name"></span><span style="color:red;" v-if="user.noti_cnt && user.noti_cnt != 0" v-text="'('+user.noti_cnt+')'"></span>
                    <span class="whisper_cnt whisper"><span id='whisper_cnt_"+users[idx].idx+"' class='whisper_cnt whisper'></span></span>
                    <p v-text="user.name+' is online'"></p>
                  </div>
                </div>
              </CListGroupItem>
              
            </CListGroup>
          </CCardBody>
        </CCard>
      </CCol>
      <CCol md="8" class="bg-info py-3" 
          @drop.stop.prevent="dropInputTag" 
          @dragover.prevent>
        <chat ref="chat"
                    @sendmessage="sendMessage"
                    style="max-width:100%;"
                    :messages-placeholder="'... Type your message ...'"
                    :user-name="'MyName'">
        </chat>
        <CElementCover :opacity="0.8" v-if="dragIn">
          <h1 class="d-inline" style="color:green">파일을 추가하시겠습니까? </h1>
        </CElementCover>
      </CCol>
    </CRow>
  </CContainer>
</template>

<script>
import { Chat } from '@progress/kendo-chat-vue-wrapper'
import privateChat from './PrivateChatting';

import {commonChatting,commonChattingLog} from '../../api';
import $ from "jquery";

export default {
  name: 'Chatting',
    components: {
        'chat': Chat,
        'privateChat' : privateChat
    },
    data() {
      return {
        userAuth,
        users:[],
        noti:[],
        dragIn:false,
        file:null,
        privateChatModal:false,
        privateTo:null,
      }
    },
    methods: {
      sendMessage (e) {
        let attachments = this.file
        this.file = null
        commonChatting(e.text, attachments)
        .then(response => {
          if(response.status != 200){
            this.$swal({ text: "에러발생!", icon: 'error' })
          }
        })
      },
      dropInputTag(e){
        let file = Array.from(e.dataTransfer.files, v => v)[0]
        this.file = file
        this.sendMessage({"text":""})
        //this.uploadImage(file)
      },
      privateChat(user){
        if(this.userAuth.id == user.id)return;
        this.privateTo = user;
        this.privateChatModal = true;
        //window.open("/member/privateChat/"+user.id);
      },
      renderMessage(value, sender){
        let chat = this.$refs.chat.kendoWidget()
        let vm = this
        if(vm.userAuth.id == value.id){
          sender.id = chat.getUser().id;
        }
        switch(value.type){
          case "message" : chat.renderMessage({ type: "text", "text" : value.content, timestamp: new Date() }, sender); break;
          case "image"   : 
            chat.renderAttachments({
              attachments: [{
                contentType: "img_chat",
                content: {
                  "sender_name": value.name,
                  "sender_img" : value.profile_src,
                  "file_url"   : value.file,
                  "content"    : value.content,
                  "sender_id"  : value.id,
                }
              }],
              attachmentLayout: "list"
            }, sender);
          break;     
          case "file"   : 
            chat.renderAttachments({
              attachments: [{
                contentType: "file_chat",
                content: {
                  "sender_name": value.name,
                  "sender_img" : value.profile_src,
                  "file_url"   : value.file,
                  "content"    : value.content,
                  "sender_id"  : value.id,
                }
              }],
              attachmentLayout: "list"
            }, sender);                    
          break;     
        }
        $(".write_"+vm.userAuth.id).addClass("k-alt");
        $(".write_"+vm.userAuth.id).removeClass("k-message-group");
        $(".write_"+vm.userAuth.id).parent(".k-card-list").addClass("k-message-group k-alt");
      },
      registerTemplates () {
        var IMG_CHAT_TEMPLATE = kendo.template(
          '<div #:sender_name#="" class="k-message-group write_#:sender_id#"><p class="k-author">#:sender_name#</p><img src="#:sender_img#" alt="#:sender_name#" class="k-avatar">'+
          '<div class="k-card k-card-type-rich">' +
                '<div class="k-card-body quoteCard">' +
                      '<img src="#:file_url#" style="width:200px"/><span>#:content#</span>' +
                '</div>' +
            '</div>'+
          '</div>'
        )
        kendo.chat.registerTemplate('img_chat', IMG_CHAT_TEMPLATE)

        var FILE_CHAT_TEMPLATE = kendo.template(
          '<div #:sender_name#="" class="k-message-group write_#:sender_id#"><p class="k-author">#:sender_name#</p><img src="#:sender_img#" alt="#:sender_name#" class="k-avatar">'+
          '<div class="k-card k-card-type-rich">' +
                '<div class="k-card-body quoteCard">' +
                      '<a href="#:file_url#" target="_blank"><button type="button" class="btn btn-primary" style="min-width:200px">#:content#</button></a>' +
                '</div>' +
            '</div>'+
          '</div>'
        )
        kendo.chat.registerTemplate('file_chat', FILE_CHAT_TEMPLATE)

      }

    },
    mounted () {
        this.registerTemplates()
        let vm = this
        commonChattingLog()
          .then(response => {
            if(response.data.length>0){
              response.data.forEach(function(value, index) {
                let sender = {id : value.id, name:value.name, iconUrl:value.profile_src}
                vm.renderMessage(value, sender)
              })
            }
          })
        window.Echo.join('monitoring.common')
        .here((users) => {
          let noti = users.find((user) => user.id === this.userAuth.id).noti;
          this.noti = noti;
          for(let i=0;i<noti.length;i++){
            if(noti[i].send_cnt && users.find((user) => user.id === noti[i].send_id))users.find((user) => user.id === noti[i].send_id).noti_cnt = noti[i].send_cnt
          }
          this.users = users.filter((user) => user.id === this.userAuth.id).concat(users.filter((user) => user.id !== this.userAuth.id))
        })
        .joining((user) => {
          if(this.noti.find((val)=>val.send_id === user.id))user.noti_cnt = this.noti.find((val)=>val.send_id === user.id).send_cnt;
          this.users.push(user);
        })
        .leaving((user) => {
          this.users = this.users.filter(value => value.id !== user.id)
        })
        .listen('CommonChatting', (data) => {
          if(data.data.type != "message" || data.user.id !== this.userAuth.id){
            let value = {
                  "type" : data.data.type,
                  "name": data.user.name,
                  "profile_src" : data.user.profile_src,
                  "file"   : data.data.file,
                  "content"    : (data.data.message) ?? '',
                  "id"  : data.user.id
                  }
            let sender = {id : data.user.id, name:data.user.name, iconUrl:data.user.profile_src}
            this.renderMessage(value, sender)
          }
        })
				window.Echo.private('App.Models.User.' + this.userAuth.id)
				.notification((notification) => {
					if(notification.type == "clear"){
						this.noti.find((val)=>val.send_id === notification.send_id).send_cnt = 0;
            if(this.users.find((user) => user.id === notification.send_id))this.users.find((user) => user.id === notification.send_id).noti_cnt = 0;
					}else{
            if(this.noti.find((val)=>val.send_id === notification.send_id)){
              this.noti.find((val)=>val.send_id === notification.send_id).send_cnt =  notification.noti_cnt;
            }else{
              let obj = {"send_id" : notification.send_id, "send_cnt" : notification.noti_cnt};
              this.noti.push(obj);
            }
            if(this.users.find((user) => user.id === notification.send_id))this.users.find((user) => user.id === notification.send_id).noti_cnt = notification.noti_cnt;

					}
				});

    },
    destroyed(){
      window.Echo.leave('monitoring.common')
    }
}
</script>
<style scoped>
#privateChatView {position:absolute;z-index: 2021;}
.drag-over { background-color: #ff0; }
.active{
    background-color: rgba(0,0,0,0.3);
}
.user_img{
    height: 70px;
    width: 70px;
    border:1.5px solid #f5f6fa;

}
.user_img_msg{
    height: 40px;
    width: 40px;
    border:1.5px solid #f5f6fa;

}
.img_cont{
    position: relative;
    height: 70px;
    width: 70px;
}
.img_cont_msg{
    height: 40px;
    width: 40px;
}
.upload_img{
    max-height:150px;

}
.online_icon{
position: absolute;
height: 15px;
width:15px;
background-color: #4cd137;
border-radius: 50%;
bottom: 0.2em;
right: 0.4em;
border:1.5px solid white;
}
.offline{
background-color: #c23616 !important;
}
.user_info{
margin-top: auto;
margin-bottom: auto;
margin-left: 15px;
}
.user_info span{
font-size: 20px;
color: white;
}
.user_info p{
font-size: 10px;
color: rgba(255,255,255,0.6);
}
.search_btn{
    border-radius: 0 15px 15px 0 !important;
    background-color: rgba(0,0,0,0.3) !important;
    border:0 !important;
    color: white !important;
    cursor: pointer;
}
.search:focus{
     box-shadow:none !important;
   outline:0px !important;
}
</style>