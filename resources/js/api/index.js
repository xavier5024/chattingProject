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

// 로그아웃
function userLogoutApi () {
  return axios.get(`${config.baseUrl}/logout`);
}

//공용채팅
function commonChatting (message, attachments){
  const params = new URLSearchParams();
  params.append('message', message);
  params.append('attachments', attachments);
  return axios.post(`${config.baseUrl}/commonChatting`, params);
}

//공용채팅로그
function commonChattingLog (){
  return axios.post(`${config.baseUrl}/commonChattingLog`);
}

export {
  sampleApi,
  userLoginApi,
  userLogoutApi,
  commonChatting,
  commonChattingLog
};
