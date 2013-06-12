$_new = window.$_new = (te, ne) ->
    return if not ne?

    if typeof ne is 'string'

        te.appendChild document.createTextNode(ne)

    else if ne.length?

        te = te.appendChild $new(ne[0], ne[1])

        for i in [2..ne.length-1]
            $_new te, ne[i]

VJ = window.VJ =

    Debug:  true

    Noop:   ->

    Domain: 'vijos.org'

    Host:   location.host
    Https:  location.protocol is 'https:'
    Prefix: location.protocol + '//'
