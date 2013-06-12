class VJ.Dialog

    @dialogCount:   0
    @dialogID:      0
    @effects:       'fadeIn fadeInUp fadeInDown fadeInLeft fadeInRight bounceIn rollIn'.split ' '

    dialog:         null
    dialogLayer:    null

    constructor:    (obj) ->

        @dialogLayer    = $new 'div', 'class': 'vj-dlg-layer'
        @dialog         = $new 'div', 'class': 'vj-dlg'

        $css.set @dialogLayer,  'z-index', (VJ.Dialog.dialogID*2+10).toString()
        $css.set @dialog,       'z-index', (VJ.Dialog.dialogID*2+11).toString()

        if obj.title?
            titleRegion = $append @dialog, $new('h2', 'class': 'vj-dlg-title')
            $html $append(titleRegion, $new('div', 'class': 'vj-dlg-ctr')), obj.title

        if obj.content?
            contentRegion = $append @dialog, $new('div', 'class': 'vj-dlg-cont')
            $html $append(contentRegion, $new('div', 'class': 'vj-dlg-ctr')), obj.content

        if obj.buttons?
            btnRegion = $append @dialog, $new('div', 'class': 'vj-dlg-btn')
            btnArea = $append btnRegion, $new('div', 'class': 'vj-dlg-ctr')

            for b in obj.buttons
                btn = $append btnArea, $new('div', 'class': 'button')
                $html btn, b.text
                $className.add btn, b.class if b.class?
                $event.on btn, 'click': b.onClick if b.onClick?

        ++VJ.Dialog.dialogCount
        ++VJ.Dialog.dialogID

    destroy:        =>

        $className.remove @dialog, 'show'
        $className.remove @dialogLayer, 'show'

        setTimeout =>
            $remove @dialog
            $remove @dialogLayer

            @dialog = null
            @dialogLayer = null
        , 500

        --VJ.Dialog.dialogCount

    show:          (effect = true) =>

        $append document.body, @dialogLayer
        $append document.body, @dialog

        # Set position

        h = @dialog.offsetHeight
        wh = jQuery(window).height()

        $css.set @dialog, 'top', (wh - h)/2

        # Effects

        setTimeout =>
            $className.add @dialogLayer, 'show'
        , 0

        setTimeout =>

            # Text animation

            if effect
                jQuery(@dialog)
                .find('.vj-dlg-cont .vj-dlg-ctr')
                .textillate(
                    in:
                        effect:     VJ.Dialog.effects[Math.floor(Math.random() * VJ.Dialog.effects.length)]
                        delayScale: 1
                        delay:      Math.floor(1000 / $text(@dialog).length)
                        shuffle:    true
                );

            $className.add @dialog, 'show'
        , 100

        @