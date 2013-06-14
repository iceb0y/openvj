loginForm = null

eventHandler_login_btnLogin_click = ->

    alert 'Not implemented'

eventHandler_login_btnCancel_click = ->

    loginForm.destroy()

VJ.Utils =

    showLogin:  ->

        loginWrapper = $new 'div'

        $_new(loginWrapper,
        ['div', {class:'form-login'},
            ['div', {class:'form-line'},
                ['label', {class:'form-login-label label-user'}],
                ['input', {type:'text', class:'textbox form-login-username'}]
            ],
            ['div', {class:'form-line'},
                ['label', {class:'form-login-label label-pass'}],
                ['input', {type:'password', class:'textbox form-login-password'}]
            ]
        ])

        $html mass.query('.label-user', loginWrapper), _('form.login.l_user')
        $html mass.query('.label-pass', loginWrapper), _('form.login.l_pass')

        if loginForm?
            loginForm.destroy()

        loginForm = new VJ.Dialog
            class:      'login'
            title:      _ 'form.login.title'
            content:    loginWrapper
            buttons:    [
                {text: _('form.login.b_login'), onClick: eventHandler_login_btnLogin_click},
                {text: _('form.login.b_cancel'), onClick: eventHandler_login_btnCancel_click}
            ]

        loginForm.show false