if not VJ?
    VJ = window.VJ = {}

VJ.Debug =

    _execute : (namespace, data) ->
    
        func = @

        return if not VJ.Debug || not func?

        func.call console, ('[{time}] {ns} > ').format({
            time : new Date().format 'Y-m-d H:i:s'
            ns   : namespace
        }), data.toString()
    
    log : (namespace, data) ->
    
        VJ.Debug._execute.apply(console.log, arguments) if console?
    
    warn : (namespace, data) ->
    
        VJ.Debug._execute.apply(console.warn, arguments) if console?
    
    error : (namespace, data) ->
    
        VJ.Debug._execute.apply(console.error, arguments) if console?