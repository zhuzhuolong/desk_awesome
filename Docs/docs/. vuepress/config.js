module.exports = {
    title: 'Hello VuePress',
    description: 'Just playing around',
    themeConfig: {
        logo: '/assets/img/logo.png',
        nav: [
            { text: 'Home', link: '/' },
            { text: 'Guide', link: '/guide/' },
            { text: 'External', link: 'https://google.com' },
            { text: 'External_self', link: 'https://google.com', target:'_self', rel:'' },
            { text: 'Blank', link: '/guide/', target:'_blank' },
            { text: 'Menu', 
                ariaLabel: 'Language Menu',
                items: [
                    { text: 'Group1', items: [
                        { text: 'Japanese', link: '/language/japanese/' },
                        { text: 'Japanese', link: '/language/japanese/' }
                    ] },
                    { text: 'Japanese', link: '/language/japanese/' }
                ]
            }
          ],
        sidebar: [
            '/',
            '/page-a',
            ['/page-b', 'Explicit link text']
        ],
        displayAllHeaders: true,
        smoothScroll: true
      }
  }