class VJ.Dialog

    @dialogCount:   0

    dialog:         null
    dialogLayer:    null

    constructor:    ->

        @dialogLayer = $new('div',
            'class':   'vj-dlg-layer'
        );

        @dialog = $new('div',
            'class':    'vj-dlg'
        )

        ++VJ.Dialog.dialogCount

    destroy:        =>

        $remove @dialog
        $remove @dialogLayer

        @dialog = null
        @dialogLayer = null

        --VJ.Dialog.dialogCount

    show:           =>

        $append document.body, @dialogLayer
        $append document.body, @dialog

        @