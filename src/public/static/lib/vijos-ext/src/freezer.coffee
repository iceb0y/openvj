if not VJ?
    VJ = window.VJ = {}

class VJ.Freezer

    container: null
    layer:     null
    active:    null

    constructor:  (obj) ->

        @container = obj.container
        @layer = $new 'div', {'class': 'vj-freezer-layer', 'tabindex': '0'}

        $append @container, @layer

    show: =>

        active = document.activeElement
        $className.add @layer, 'show'

        setTimeout ->
            @layer.focus()
        , 0

    hide: =>

        setTimeout ->
            active.focus() if active?
        , 0

        $className.remove @layer, 'show'
        