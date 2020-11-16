
export default [
    {
        path: "/",
        name: 'index',
        meta: {
            title: '首页',
            hideInMenu: false
        },
        component: resolve => (require(['@/views/home/main'],resolve)),
        children:[
            {
                path:'/home',
                name:'home',
                meta:{
                    title:'首页'
                },
                component: resolve => require(['@/views/home/home'],resolve)
            }
        ]
    }
]