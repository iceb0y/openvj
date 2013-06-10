( (global, undefined) ->


    VJ = global.VJ =

        Debug:  true
        Noop:   ->
        Domain: 'vijos.org'
        Host:   location.host
        Https:  location.protocol is 'https:'
        Prefix: location.protocol + '//'

    

)(window);