freezer = null

init_step1 = ->

    setTimeout ->
        $_query('.role-reg-email')[0].focus()
    , 100

    $event.on $_query('.role-reg-email-confirm'), 'click', ->

        target_mail = $_query('.role-reg-email')[0].value

        if not /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test target_mail

            $text $_query('.reg-hint'), 'Invalid email address ∑(O_O；)'
            return

        VJ.ajax

            action:    '/user/register'
            data:      {email: target_mail}
            freezer:   freezer

            onSuccess: (d) ->

                $text $_query('.role-email'), target_mail

                $fadeout $_query('.reg-step1'), 100, ->

                    $style.set $_query('.reg-step1'), 'display', 'none'
                    $style.set $_query('.reg-step1-result'), 'display', 'block'

                    setTimeout ->

                        $fadein $_query('.reg-step1-result'), 100

            onFailure: (d) ->

                $text $_query('.reg-hint'), d.errorMsg

            onError: (errorText) ->

                VJ.Dialog.alert errorText, 'Error'

    $event.on $_query('.role-reg-email'), 'keypress', (event) ->

        $empty $_query('.reg-hint')

        jQuery('.role-reg-email-confirm').click() if event.which is 13

    $event.on $_query('.role-resend'), 'click', ->

        $fadeout $_query('.reg-step1-result'), 100, ->

            $style.set $_query('.reg-step1-result'), 'display', 'none'
            $style.set $_query('.reg-step1'), 'display', 'block'

            setTimeout ->

                $fadein $_query('.reg-step1'), 100
                $_query('.role-reg-email')[0].select()

init_step2 = ->

    setTimeout ->
        $_query('.role-reg-nickname')[0].focus()
    , 100

    jQuery('.textbox').tipsy
        title:      'data-tip'
        gravity:    'e'
        trigger:    'focus'
        offset:     120
        className:  'tipsy-reg'

    jQuery('.role-reg-agree').tipsy
        title:      'data-tip'
        gravity:    'e'
        trigger:    'manual'
        offset:     10
        className:  'tipsy-reg'

    jQuery('input').iCheck()

    jQuery('.role-reg-agree').on 'ifChecked', ->

        jQuery(@).tipsy 'hide'

    dom_password = $_query '.role-reg-password'

    $event.on dom_password, 'focus', ->

        this.type = 'text'

    $event.on dom_password, 'blur', ->

        this.type = 'password'

    $event.on dom_password, 'keyup', ->

        if this.value.match /[^\x00-\xff]/g
            this.value = this.value.replace /[^\x00-\xff]/g, ''

    $event.on $_query('.role-reg-submit'), 'click', ->

        dom = $_query('.role-reg-nickname')[0]

        if not dom.value.match /^[^ ^\t]{1,15}$/
            dom.select()
            return false

        dom = $_query('.role-reg-username')[0]

        if not dom.value.match /^[^ ^\t]{3,30}$/
            dom.select()
            return false
        
        dom = $_query('.role-reg-password')[0]

        if not dom.value.match /^.{5,30}$/
            dom.select()
            return false

        dom = $_query('.role-reg-agree')[0]

        if not dom.checked
            jQuery('.role-reg-agree').tipsy 'show'
            return false

        VJ.ajax

            action:    '/user/register'
            data:      
                email: REG_PARAM.mail
                code: REG_PARAM.code
                nick: $_query('.role-reg-nickname')[0].value
                user: $_query('.role-reg-username')[0].value
                pass: $_query('.role-reg-password')[0].value
                gender: $_query('[name="reg-gender"]:checked')[0].value
                agreement: 'accept'

            freezer:   freezer

            onSuccess: (d) ->

                window.location = CONFIG.basePrefix + '/user/hello'

            onFailure: (d) ->

                VJ.Dialog.alert d.errorMsg, 'Error'

            onError: (errorText) ->

                VJ.Dialog.alert errorText, 'Error'

window.onInitEnd.push ->

    freezer = new VJ.Freezer
        container:  $_query('.reg-step')
        dark:       true

    $fadein $_query('.reg-step'), 1000

    if REG_STEP is 1

        init_step1()

    else if REG_STEP is 2

        init_step2()