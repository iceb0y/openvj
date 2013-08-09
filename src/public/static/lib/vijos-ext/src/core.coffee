$_new = window.$_new = (te, ne) ->
    
    return if not ne?

    if typeof ne is 'string'

        te.appendChild document.createTextNode(ne)

    else if ne.length?
        
        te = te.appendChild $new(ne[0], ne[1])
        $_new te, ne[i] for i in [2..ne.length-1]

if not VJ?
    VJ = window.VJ = {}

VJ.Debug = true
VJ.Noop = ->
VJ.Domain = 'vijos.org'
VJ.Host = location.host
VJ.Https = location.protocol is 'https:'
VJ.Prefix = location.protocol + '//'
