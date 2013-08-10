if not VJ?
    VJ = window.VJ = {}

loginForm = null

eventHandler_login_textbox_keypress = (e) ->

    # Enter
    if e.which is 13
        action_login loginForm.dialog
        return false

eventHandler_login_btnLogin_click = ->

    action_login loginForm.dialog
    
eventHandler_login_btnCancel_click = ->

    loginForm.destroy()

action_login = (dialog) ->

    if not RSAKey?
        VJ.Dialog.alert('登录时遇到问题：RSA库载入失败。请刷新后重试。', 'Error')
        return

    if not RSA_PUBLIC?
        VJ.Dialog.alert('登录时遇到问题：RSA私钥载入失败。请刷新后重试。', 'Error')
        return

    user = mass.query('.role-form-login-username', loginForm.dialog)[0].value
    pass = mass.query('.role-form-login-password', loginForm.dialog)[0].value

    data = JSON.stringify
        user: user
        pass: pass
        timestamp: Math.floor(new Date().getTime() / 1000) - RSA_PUBLIC.timestamp_offset

    rsa = new RSAKey()
    rsa.setPublic RSA_PUBLIC.key, RSA_PUBLIC.e

    data_encrypted = rsa.encrypt data

    VJ.ajax

        action:     'login'
        data:       {encrypted: data_encrypted}
        freezer:    loginForm.freezer

        onSuccess:  (d) ->

            console.log d

        onFailure: (d) ->

            VJ.Dialog.alert d.errorMsg, 'Login'


VJ.Utils =

    showLogin:  ->

        ######################################
        # Load RSA library and RSA key

        if not RSAKey?

            $append document.body, $new('script', 'src': '/static/lib/rsa/rsa-bundle.js')

        if not RSA_PUBLIC?

            VJ.ajax

                action:     'rsa'
                onSuccess:  (d) ->

                    # Calculate time delta
                    d.timestamp_offset = Math.floor(new Date().getTime() / 1000) - d.timestamp
                    
                    window.RSA_PUBLIC = d

        ######################################

        loginWrapper = $new 'div'

        $_new(loginWrapper,
        ['div', {class:'form-login'},
            ['form', {action:'/ajax/login', method:'post'},
                ['div', {class:'form-line'},
                    ['label', {class:'form-login-label label-user', innerHTML:_('form.login.l_user')}],
                    ['input', {type:'text', class:'textbox role-form-login-username'}]
                ],
                ['div', {class:'form-line'},
                    ['label', {class:'form-login-label label-pass', innerHTML:_('form.login.l_pass')}],
                    ['input', {type:'password', class:'textbox role-form-login-password'}]
                ]
            ]
        ])

        if loginForm?
            loginForm.destroy()

        $event.on mass.query('.textbox', loginWrapper), 'keypress', eventHandler_login_textbox_keypress
        
        loginForm = new VJ.Dialog
            class:      'login'
            title:      _ 'form.login.title'
            content:    loginWrapper
            buttons:    [
                {text: _('form.login.b_login'), class: 'button-def', onClick: eventHandler_login_btnLogin_click},
                {text: _('form.login.b_cancel'), onClick: eventHandler_login_btnCancel_click}
            ]

        loginForm.freezer = new VJ.Freezer
            container:  loginForm.dialog

        loginForm.show false
        mass.query('.role-form-login-username', loginWrapper)[0].focus()

    fillParams: (options, defaultValue) ->

        return defaultValue if not options?

        ret = jQuery.extend {}, options

        for key, value of defaultValue
            ret[key] = value if not ret[key]?
        
        ret

    mergeObject: (obj1, obj2) ->

        return undefined if not obj1? and not obj2?

        ret = obj1
        ret[key] = value for key, value of obj2

        ret