VJ.I18N =

    resMap: {}
    lang:   null

    register:   (lang, map) ->

        VJ.I18N.resMap[lang] = map
        VJ.I18N.lang = lang if not VJ.I18N.lang?

    get:        (key, rep) ->

        if VJ.I18N.resMap[VJ.I18N.lang][key]?
            res = VJ.I18N.resMap[VJ.I18N.lang][key]
        else
            res = key

        if rep?
            res = res.format rep

        res

window._ = ->

    VJ.I18N.get.apply @, arguments