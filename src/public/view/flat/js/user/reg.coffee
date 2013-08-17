freezer = null

$ready ->

    freezer = new VJ.Freezer
        container:  mass.query('.reg-step')
        dark:       true

    $fadein mass.query('.reg-step'), 1000

    $event.on mass.query('.role-reg-email-confirm'), 'click', ->

        if not /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test jQuery('.role-reg-email').val()

            $text mass.query('.reg-hint'), 'Invalid email address ∑(O_O；)'
            return

        VJ.ajax

            action:    'registerstep1'
            data:      {mail: jQuery('.role-reg-email').val()}
            freezer:   freezer

            onSuccess: (d) ->

                console.log d

            onFailure: (d) ->

                $text mass.query('.reg-hint'), d.errorMsg

    $event.on mass.query('.role-reg-email'), 'keypress', (event) ->

        $empty mass.query('.reg-hint')

        jQuery('.role-reg-email-confirm').click() if event.which is 13