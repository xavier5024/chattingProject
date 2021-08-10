export default [
  {
    _name: 'CSidebarNav',
    _children: [
      {
        _name: 'CSidebarNavItem',
        name: '대시보드',
        to: '/member/dashboard',
        icon: 'cil-speedometer',
        // badge: {
        //   color: 'primary',
        //   text: 'NEW'
        // }
      },
      {
         _name: 'CSidebarNavItem',
         name: '채팅',
         to: '/member/chatting',
         icon: 'cil-3d'
      },
      {
        _name: 'CSidebarNavItem',
        name: '회원',
        to: '/member/memberList',
        icon: 'cil-people'
     },

      // {
      // _name: 'CSidebarNavItem',
      //   name: '테스트',
      //   to: '/admin/test_context',
      //   icon: 'cil-3d'
      // },
      // {
      //   _name: 'CSidebarNavTitle',
      //   _children: ['상품/질문 관리']
      // },
      // {
      //   _name: 'CSidebarNavItem',
      //   name: '상품 관리',
      //   to: '/admin/goods',
      //   icon: 'cil-3d'
      // },
      // {
      //   _name: 'CSidebarNavItem',
      //   name: '상품 타입 관리',
      //   to: '/admin/goods',
      //   icon: 'cil-3d'
      // },
      // {
      //   _name: 'CSidebarNavDropdown',
      //   name: '질문 관리',
      //   route: '/admin/questions',
      //   icon: 'cil-pencil',
      //   items: [
      //     {
      //       name: '기본 질문',
      //       to: '/admin/questions/standard'
      //     },
      //     // {
      //     //   name: '상황 제시형',
      //     //   to: '/admin/questions/standard'
      //     // },
      //     // {
      //     //   name: '심층 구조화',
      //     //   to: '/admin/questions/standard'
      //     // }
      //   ]
      // },
 
    ]
  }
]