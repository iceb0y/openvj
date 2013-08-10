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

        @active = document.activeElement
        $className.add @layer, 'show'
        @layer.focus()

    hide: =>

        @active.focus() if @active?
        $className.remove @layer, 'show'
        