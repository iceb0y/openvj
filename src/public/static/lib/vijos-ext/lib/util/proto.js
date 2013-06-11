String.prototype.padLeft = function(len, repeatChar)
{
    repeatChar |= '0';

    var ret = '';
    var loopTimes = len - this.length;

    for(var i = 0; i < loopTimes; ++i)
    ret += repeatChar;

    return ret + this.toString();
};

String.prototype.padRight = function(len, repeatChar)
{
    repeatChar |= '0';

    var ret = '';
    var loopTimes = len - this.length;

    for(var i = 0; i < loopTimes; ++i)
    ret += repeatChar;

    return this + ret.toString();
};

String.prototype.format = function(obj)
{
    // http://www.planabc.net/2011/05/31/simple_javascript_template_substitute

    if(!(Object.prototype.toString.call(obj) === '[object Object]' && 'isPrototypeOf' in obj))
        return this;

    return this.replace(/\{([^{}]+)\}/g, function(match, key)
    {
        var value = obj[key];
        return (value !== undefined) ? value : match;
    });
};

String.prototype.parseURI = function(strict)
{
    // http://blog.stevenlevithan.com/archives/parseuri

    strict = strict || true;

    var o =
    {
        strictMode : strict,
        key : ["source", "protocol", "authority", "userInfo", "user", "password", "host", "port", "relative", "path", "directory", "file", "query", "anchor"],
        q :
        {
            name : "queryKey",
            parser : /(?:^|&)([^&=]*)=?([^&]*)/g
        },
        parser :
        {
            strict : /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
            loose : /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
        }
    };

    var m = o.parser[o.strictMode ? "strict" : "loose"].exec(this.toString()), uri = {}, i = 14;

    while(i--)
    uri[o.key[i]] = m[i] || "";

    uri[o.q.name] = {};
    uri[o.key[12]].replace(o.q.parser, function($0, $1, $2)
    {
        if($1)
            uri[o.q.name][$1] = $2;
    });
    return uri;
}

String.prototype.parseQuery = function()
{
    var ret = {};

    var uriSection = this.toString().split('?');
    ret.link = this.toString();
    ret.page = uriSection[0];
    ret.queryString = uriSection[1];
    ret.query = {};

    if(uriSection[1] != undefined)
    {
        uriSection[1].replace(/(?:^|&)([^&=]*)=?([^&]*)/g, function($0, $1, $2)
        {
            if($1)
                ret.query[$1] = $2;
        });
    }

    return ret;
}

Date.prototype.format = function(formatStr)
{
    //http://www.codingforums.com/archive/index.php/t-11088.html
    
    var heap = formatStr.split('');
    var resHeap = new Array(heap.length);
    var escapeChar = '\\';
    
    // go through array and extract identifiers from its fields
    for (var i = 0; i < heap.length; i++)
    {
        switch(heap[i]) 
        {
            case escapeChar:
                resHeap[i] = heap[i+1];
                i++;
                break;

            case "a": // "am" or "pm"
                var temp = this.getHours();
                resHeap[i] = (temp < 12) ? "am" : "pm";
                break;

            case "A": // "AM" or "PM"
                var temp = this.getHours();
                resHeap[i] = (temp < 12) ? "AM" : "PM";
                break;

            case "d": // day of the month, 2 digits with leading zeros; i.e. "01" to "31"
                var temp = String(this.getDate());
                resHeap[i] = (temp.length > 1) ? temp : "0" + temp;
                break;

            case "g": // hour, 12-hour format without leading zeros; i.e. "1" to "12"
                var temp = this.getHours();
                resHeap[i] = (temp <= 12) ? temp : (temp - 12);
                break;

            case "G": // hour, 24-hour format without leading zeros; i.e. "0" to "23"
                resHeap[i] = String(this.getHours());
                break;

            case "h": // hour, 12-hour format; i.e. "01" to "12"
                var temp = String(this.getHours());
                temp = (temp <= 12) ? temp : (temp - 12);
                resHeap[i] = (temp.length > 1) ? temp : "0" + temp;
                break;

            case "H": // hour, 24-hour format; i.e. "00" to "23"
                var temp = String(this.getHours());
                resHeap[i] = (temp.length > 1) ? temp : "0" + temp;
                break;

            case "i": // minutes; i.e. "00" to "59" 
                var temp = String(this.getMinutes());
                resHeap[i] = (temp.length > 1) ? temp : "0" + temp;
                break;

            case "m": // month; i.e. "01" to "12"
                var temp = String(this.getMonth() + 1);
                resHeap[i] = (temp.length > 1) ? temp : "0" + temp;
                break;

            case "n": // month without leading zeros; i.e. "1" to "12"
                resHeap[i] = this.getMonth() + 1;
                break;

            case "s": // seconds; i.e. "00" to "59"
                var temp = String(this.getSeconds()); 
                resHeap[i] = (temp.length > 1) ? temp : "0" + temp;
                break;

            case "U": // seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
                // remember that this does not return milisecs! 
                resHeap[i] = Math.floor(this.getTime() / 1000); 
                break;

            case "y": // year, 2 digits; i.e. "99"
                resHeap[i] = String(this.getFullYear()).substring(2);
                break;

            case "Y": // year, 4 digits; i.e. "1999"
                resHeap[i] = this.getFullYear();
                break;

            default:
                resHeap[i] = heap[i];
        }
    }

    return resHeap.join('');
}