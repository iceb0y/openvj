if not VJ?
    VJ = window.VJ = {}

VJ.ajax = (options) ->

    return false if not options.url? and not options.action?

    options = VJ.Utils.fillParams options,

        url:            '/ajax'
        method:         'post'
        expectFormat:   'json'
        onFailure:      VJ.Noop
        onSuccess:      VJ.Noop
        onError:        VJ.Noop

    data = {}

    if options.action?

        if options.url is '/ajax'

            options.url += '/' + options.action

            if user_info? and user_info.sid?

                options.url += '?sid=' + user_info.sid

        else

            data.action = options.action

        if options.data?

            data = VJ.Utils.mergeObject data, options.data

    else

        data = options.data

    jQuery.ajax

        type:       options.method
        url:        options.url
        data:       data
        dataType:   'text'
        success:    (data, status, xhr) ->

            if not data?

                options.onError 'Invalid return value'
                VJ.Debug.error 'VJ.ajax', 'action={action}, url={url} | Empty result'.format(options)

                return false

            if options.expectFormat is 'json'
                objData = JSON.parse data.toString()
            else
                objData = data

            if not objData?

                options.onError 'Server internal error'
                VJ.Debug.error 'VJ.ajax', 'action={action}, url={url}, data={data} | Server internal error'.format
                    action: options.action
                    url:    options.url
                    data:   data

                return false

            if objData.succeeded is false

                options.onFailure objData
                return false;

            options.onSuccess objData

        error: (jqXHR, textStatus, errorThrown) ->

            options.onError textStatus
            VJ.Debug.error 'VJ.ajax', 'action={action},url={url},error={error} | Network error.'.format
                action: options.action
                url:    options.url
                error:  textStatus