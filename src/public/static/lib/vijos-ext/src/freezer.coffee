if not VJ?
    VJ = window.VJ = {}

class VJ.Freezer

    container: null
    layer:     null
    active:    null

    constructor:  (obj) ->

        @container = obj.container
        @layer = $new 'div', {'class': 'vj-freezer-layer', 'tabindex': '0'}
        
        domLoading = $new 'div', {'class': 'loading vj-freezer-layer-loading'}
        $append @layer, domLoading

        if obj.dark? and obj.dark is true
            $className.add @layer, 'vj-freezer-layer-dark'
            $className.add domLoading, 'loading-dark'

        $append @container, @layer

    show: =>

        @active = document.activeElement
        $className.add @layer, 'vj-freezer-layer-show'
        @layer.focus()

    hide: =>

        @active.focus() if @active?
        $className.remove @layer, 'vj-freezer-layer-show'
        