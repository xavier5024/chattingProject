import axios from 'axios';

// HTTP Request & Response 관련 기본 설정
const config = {
  baseUrl: '/data'
};

/**
 * 샘플
 */
function sampleApi(method) {
  const params = new URLSearchParams();
  params.append('id', method + ' data');
  params.append('name', method + ' 홍길동');
  if(method=='gets'){
    return axios.get(`${config.baseUrl}/samples`);
  } else if(method=='post') {
    return axios.post(`${config.baseUrl}/samples`, params);
  } else if(method=='put') {
    return axios.put(`${config.baseUrl}/samples`, params);
  } else if(method=='delete') {
    return axios.delete(`${config.baseUrl}/samples/delete-id`, params);
  } else {
    return axios.get(`${config.baseUrl}/samples/get-id`);
  }
}

// 로그인
function userLoginApi (id, password) {
  const params = new URLSearchParams();
  params.append('id', id);
  params.append('password', password);
  
  return axios.post(`${config.baseUrl}/login`, params);
}

function memberJoinApi(data, profile_img){
  const params = new FormData();
  params.append('data', JSON.stringify(data));
  if(profile_img)params.append('profile_img', profile_img);
  return axios.post(`${config.baseUrl}/join`, params, {headers: {'Content-Type': 'multipart/form-data'}});
}

// 로그아웃
function userLogoutApi () {
  return axios.get(`${config.baseUrl}/logout`);
}

//공용채팅
function commonChatting (message, attachments){
  const params = new FormData();
  params.append('message', message);
  params.append('attachments', attachments);
  return axios.post(`${config.baseUrl}/commonChatting`, params, {headers: {'Content-Type': 'multipart/form-data'}});
}

//개인 채팅
function privateChatting(message, attachments, privateTo, channel_name){
  const params = new FormData();
  params.append('message', message);
  params.append('channel_name', channel_name);
  params.append('privateTo', privateTo);
  params.append('attachments', attachments);
  return axios.post(`${config.baseUrl}/privateChatting`, params, {headers: {'Content-Type': 'multipart/form-data'}});
}

//공용채팅로그
function commonChattingLog (){
  return axios.post(`${config.baseUrl}/commonChattingLog`);
}

//개인 채팅 로그
function privateChattingLog(privateToId){
  const params = new URLSearchParams();
  params.append('privateToId', privateToId);
  return axios.post(`${config.baseUrl}/privateChattingLog`, params);
}

//개인 채팅 읽음
function privateRead(privateTo){
  const params = new URLSearchParams();
  params.append('privateTo', privateTo);
  return axios.post(`${config.baseUrl}/privateRead`, params);
}

//회원 리스트
function memberListApi(){
  return axios.post(`${config.baseUrl}/memberList`);
}

//회원 등록
function memberRegisterApi(){
  return axios.post(`${config.baseUrl}/memberList`);
}

//회원 수정
function memberUpdateApi(){
  return axios.post(`${config.baseUrl}/memberList`);
}

//회원 삭제
function memberDeleteApi(){
  return axios.post(`${config.baseUrl}/memberList`);
}

export {
  sampleApi,
  userLoginApi,
  userLogoutApi,
  commonChatting,
  privateChatting,
  commonChattingLog,
  memberJoinApi,
  privateChattingLog,
  privateRead,
  memberListApi,
  memberRegisterApi,
  memberUpdateApi,
  memberDeleteApi
};
