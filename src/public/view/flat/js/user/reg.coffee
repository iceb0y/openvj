freezer = null

init_step1 = ->

    setTimeout ->
        mass.query('.role-reg-email')[0].focus()
    , 100

    $event.on mass.query('.role-reg-email-confirm'), 'click', ->

        target_mail = jQuery('.role-reg-email').val()

        if not /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test target_mail

            $text mass.query('.reg-hint'), 'Invalid email address ∑(O_O；)'
            return

        VJ.ajax

            action:    'registerstep1'
            data:      {mail: target_mail}
            freezer:   freezer

            onSuccess: (d) ->

                $text mass.query('.role-email'), target_mail

                $fadeout mass.query('.reg-step1'), 100, ->

                    $style.set mass.query('.reg-step1'), 'display', 'none'
                    $style.set mass.query('.reg-step1-result'), 'display', 'block'

                    setTimeout ->

                        $fadein mass.query('.reg-step1-result'), 100

            onFailure: (d) ->

                $text mass.query('.reg-hint'), d.errorMsg

    $event.on mass.query('.role-reg-email'), 'keypress', (event) ->

        $empty mass.query('.reg-hint')

        jQuery('.role-reg-email-confirm').click() if event.which is 13

    $event.on mass.query('.role-resend'), 'click', ->

        $fadeout mass.query('.reg-step1-result'), 100, ->

            $style.set mass.query('.reg-step1-result'), 'display', 'none'
            $style.set mass.query('.reg-step1'), 'display', 'block'

            setTimeout ->

                $fadein mass.query('.reg-step1'), 100
                mass.query('.role-reg-email')[0].select()


init_step2 = ->

    jQuery('input').iCheck()


$ready ->

    freezer = new VJ.Freezer
        container:  mass.query('.reg-step')
        dark:       true

    $fadein mass.query('.reg-step'), 1000

    if REG_STEP is 1

        init_step1()

    else if REG_STEP is 2

        init_step2()