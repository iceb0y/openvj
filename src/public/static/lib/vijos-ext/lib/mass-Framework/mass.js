//Modified from Mass-Framework

(function(global, DOC){
    
    var $ = window.mass = function(){};

    var NsVal = $;
    NsVal.uuid = 1;

    var W3C = DOC.dispatchEvent; //IE9开始支持W3C的事件模型与getComputedStyle取样式值
    var html = DOC.documentElement; //HTML元素
    var head = DOC.head || DOC.getElementsByTagName("head")[0]; //HEAD元素
    var factorys = []; //储存需要绑定ID与factory对应关系的模块（标准浏览器下，先parse的script节点会先onload）
    var mass = 1; //当前框架的版本号
    var all = "mass,lang,class,flow,data,support,query,node,attr,css,event,ajax,fx";
    var moduleClass = "mass" + (new Date - 0);
    var hasOwn = Object.prototype.hasOwnProperty;
    var class2type = {
        "[object HTMLDocument]": "Document",
        "[object HTMLCollection]": "NodeList",
        "[object StaticNodeList]": "NodeList",
        "[object DOMWindow]": "Window",
        "[object global]": "Window",
        "null": "Null",
        "NaN": "NaN",
        "undefined": "Undefined"
    };
    var serialize = class2type.toString;
    
    /**
     * 糅杂，为一个对象添加更多成员
     * @param {Object} receiver 接受者
     * @param {Object} supplier 提供者
     * @return  {Object} 目标对象
     * @api public
     */
    function mix(receiver, supplier) {
        var args = [].slice.call(arguments),
                i = 1,
                key, //如果最后参数是布尔，判定是否覆写同名属性
                ride = typeof args[args.length - 1] === "boolean" ? args.pop() : true;
        if (args.length === 1) { //处理$.mix(hash)的情形
            receiver = !this.window ? this : {};
            i = 0;
        }
        while ((supplier = args[i++])) {
            for (key in supplier) { //允许对象糅杂，用户保证都是对象
                if (hasOwn.call(supplier, key) && (ride || !(key in receiver))) {
                    receiver[key] = supplier[key];
                }
            }
        }
        return receiver;
    }

    //为此版本的命名空间对象添加成员
    mix($, {
        html: html,
        head: head,
        mix: mix,
        rword: /[^, ]+/g,
        rmapper: /(\w+)_(\w+)/g,
        mass: mass,
        hasOwn: function(obj, key) {
            return hasOwn.call(obj, key);
        },
        //大家都爱用类库的名字储存版本号，我也跟风了
        "@bind": W3C ? "addEventListener" : "attachEvent",
        /**
         * 数组化
         * @param {ArrayLike} nodes 要处理的类数组对象
         * @param {Number} start 可选。要抽取的片断的起始下标。如果是负数，从后面取起
         * @param {Number} end  可选。规定从何处结束选取
         * @return {Array}
         * @api public
         */
        slice: W3C ? function(nodes, start, end) {
            return factorys.slice.call(nodes, start, end);
        } : function(nodes, start, end) {
            var ret = [],
                    n = nodes.length;
            if (end === void 0 || typeof end === "number" && isFinite(end)) {
                start = parseInt(start, 10) || 0;
                end = end == void 0 ? n : parseInt(end, 10);
                if (start < 0) {
                    start += n;
                }
                if (end > n) {
                    end = n;
                }
                if (end < 0) {
                    end += n;
                }
                for (var i = start; i < end; ++i) {
                    ret[i - start] = nodes[i];
                }
            }
            return ret;
        },
        /**
         * 用于建立一个从元素到数据的关联，应用于事件绑定，元素去重
         * @param {Any} obj
         * @return {Number} 一个UUID
         */
        getUid: W3C ? function(obj) { //IE9+,标准浏览器
            return obj.uniqueNumber || (obj.uniqueNumber = NsVal.uuid++);
        } : function(obj) {
            if (obj.nodeType !== 1) { //如果是普通对象，文档对象，window对象
                return obj.uniqueNumber || (obj.uniqueNumber = NsVal.uuid++);
            } //注：旧式IE的XML元素不能通过el.xxx = yyy 设置自定义属性
            var uid = obj.getAttribute("uniqueNumber");
            if (!uid) {
                uid = NsVal.uuid++;
                obj.setAttribute("uniqueNumber", uid);
            }
            return +uid; //确保返回数字
        },
        /**
         * 绑定事件(简化版)
         * @param {Node|Document|window} el 触发者
         * @param {String} type 事件类型
         * @param {Function} fn 回调
         * @param {Boolean} phase ? 是否捕获，默认false
         * @return {Function} fn 刚才绑定的回调
         */
        bind: W3C ? function(el, type, fn, phase) {
            el.addEventListener(type, fn, !!phase);
            return fn;
        } : function(el, type, fn) {
            el.attachEvent && el.attachEvent("on" + type, fn);
            return fn;
        },
        /**
         * 卸载事件(简化版)
         * @param {Node|Document|window} el 触发者
         * @param {String} type 事件类型
         * @param {Function} fn 回调
         * @param {Boolean} phase ? 是否捕获，默认false
         */
        unbind: W3C ? function(el, type, fn, phase) {
            el.removeEventListener(type, fn || $.noop, !!phase);
        } : function(el, type, fn) {
            if (el.detachEvent) {
                el.detachEvent("on" + type, fn || $.noop);
            }
        },
        /**
         * 用于取得数据的类型（一个参数的情况下）或判定数据的类型（两个参数的情况下）
         * @param {Any} obj 要检测的东西
         * @param {String} str ? 要比较的类型
         * @return {String|Boolean}
         * @api public
         */
        type: function(obj, str) {
            var result = class2type[(obj == null || obj !== obj) ? obj : serialize.call(obj)] || obj.nodeName || "#";
            if (result.charAt(0) === "#") { //兼容旧式浏览器与处理个别情况,如window.opera
                //利用IE678 window == document为true,document == window竟然为false的神奇特性
                if (obj == obj.document && obj.document != obj) {
                    result = "Window"; //返回构造器名字
                } else if (obj.nodeType === 9) {
                    result = "Document"; //返回构造器名字
                } else if (obj.callee) {
                    result = "Arguments"; //返回构造器名字
                } else if (isFinite(obj.length) && obj.item) {
                    result = "NodeList"; //处理节点集合
                } else {
                    result = serialize.call(obj).slice(8, -1);
                }
            }
            if (str) {
                return str === result;
            }
            return result;
        },
        /**
         *  将调试信息打印到控制台或页面
         *  $.log(str, page, level )
         *  @param {Any} str 用于打印的信息，不是字符串将转换为字符串
         *  @param {Boolean} page ? 是否打印到页面
         *  @param {Number} level ? 通过它来过滤显示到控制台的日志数量。
         *          0为最少，只显示最致命的错误；7，则连普通的调试消息也打印出来。
         *          显示算法为 level <= $.config.level。
         *          这个$.config.level默认为9。下面是level各代表的含义。
         *          0 EMERGENCY 致命错误,框架崩溃
         *          1 ALERT 需要立即采取措施进行修复
         *          2 CRITICAL 危急错误
         *          3 ERROR 异常
         *          4 WARNING 警告
         *          5 NOTICE 通知用户已经进行到方法
         *          6 INFO 更一般化的通知
         *          7 DEBUG 调试消息
         *  @return {String}
         *  @api public
         */
        log: function(str, page, level) {
            for (var i = 1, show = true; i < arguments.length; i++) {
                level = arguments[i];
                if (typeof level === "number") {
                    show = level <= $.config.level;
                } else if (level === true) {
                    page = true;
                }
            }
            if (show) {
                if (page === true) {
                    $.require("ready", function() {
                        var div = DOC.createElement("pre");
                        div.className = "mass_sys_log";
                        div.innerHTML = str + ""; //确保为字符串
                        DOC.body.appendChild(div);
                    });
                } else if (window.opera) {
                    opera.postError(str)
                    //http://www.cnblogs.com/zoho/archive/2013/01/31/2886651.html
                    //http://www.dotblogs.com.tw/littlebtc/archive/2009/04/06/ie8-ajax-2-debug.aspx
                } else if (global.console && console.info && console.log) {
                    console.log(str);
                }

            }
            return str;
        },
        /**
         * 生成键值统一的对象，用于高速化判定
         * @param {Array|String} array 如果是字符串，请用","或空格分开
         * @param {Number} val ? 默认为1
         * @return {Object}
         */
        oneObject: function(array, val) {
            if (typeof array === "string") {
                array = array.match($.rword) || [];
            }
            var result = {},
                    value = val !== void 0 ? val : 1;
            for (var i = 0, n = array.length; i < n; i++) {
                result[array[i]] = value;
            }
            return result;
        },
        //一个空函数
        noop: function() {
        },
        /**
         * 抛出错误,方便调试
         * @param {String} str
         * @param {Error}  e ? 具体的错误对象构造器
         * EvalError: 错误发生在eval()中
         * SyntaxError: 语法错误,错误发生在eval()中,因为其它点发生SyntaxError会无法通过解释器
         * RangeError: 数值超出范围
         * ReferenceError: 引用不可用
         * TypeError: 变量类型不是预期的
         * URIError: 错误发生在encodeURI()或decodeURI()中
         */
        error: function(str, e) {
            throw new (e || Error)(str);
        }
    });

    "Boolean,Number,String,Function,Array,Date,RegExp,Window,Document,Arguments,NodeList".replace($.rword, function(name) {
        class2type["[object " + name + "]"] = name;
    });

})(self, self.document); //为了方便在VS系列实现智能提示,把这里的this改成self或window
/**
 changelog:
 2011.7.11
 @开头的为私有的系统变量，防止人们直接调用,
 dom.check改为dom["@emitter"]
 dom.namespace改为dom["mass"]
 去掉无用的dom.modules
 优化exports方法
 2011.8.4
 强化dom.log，让IE6也能打印日志
 重构fixOperaError与resolveCallbacks
 将provide方法合并到require中去
 2011.8.7
 重构define,require,resolve
 添加"@modules"属性到dom命名空间上
 增强domReady传参的判定
 2011.8.18 应对HTML5 History API带来的“改变URL不刷新页面”技术，让URL改变时让namespace也跟着改变！
 2011.8.20 去掉dom.K,添加更简单dom.noop，用一个简单的异步列队重写dom.ready与错误堆栈dom.stack
 2011.9.5  强化dom.type
 2011.9.19 强化dom.mix
 2011.9.24 简化dom.bind 添加dom.unbind
 2011.9.28 dom.bind 添加返回值
 2011.9.30 更改是否在顶层窗口的判定  global.frameElement == null --> self.eval === top.eval
 2011.10.1
 更改dom.uuid为dom["@uuid"],dom.basePath为dom["@path"]，以示它们是系统变量
 修复dom.require BUG 如果所有依赖模块之前都加载执行过，则直接执行回调函数
 移除dom.ready 只提供dom(function(){})这种简捷形式
 2011.10.4 强化对IE window的判定, 修复dom.require BUG dn === cn --> dn === cn && !callback._name
 2011.10.9
 简化fixOperaError中伪dom命名空间对象
 优化截取隐藏命名空间的正则， /(\W|(#.+))/g --〉  /(#.+|\\W)/g
 2011.10.13 dom["@emitter"] -> dom["@target"]
 2011.10.16 移除XMLHttpRequest的判定，回调函数将根据依赖列表生成参数，实现更彻底的模块机制
 2011.10.20 添加error方法，重构log方法
 2011.11.6  重构uuid的相关设施
 2011.11.11 多版本共存
 2011.12.19 增加define方法
 2011.12.22 加载用iframe内增加$变量,用作过渡.
 2012.1.15  更换$为命名空间
 2012.1.29  升级到v15
 2012.1.30 修正_checkFail中的BUG，更名_resolveCallbacks为_checkDeps
 2012.2.3 $.define的第二个参数可以为boolean, 允许文件合并后，在标准浏览器跳过补丁模块
 2012.2.23 修复内部对象泄漏，导致与外部$变量冲突的BUG
 2012.4.5 升级UUID系统，以便页面出现多个版本共存时，让它们共享一个计数器。
 2012.4.25  升级到v16
 简化_checkFail方法，如果出现死链接，直接打印模块名便是，不用再放入错误栈中了。
 简化deferred列队，统一先进先出。
 改进$.mix方法，允许只存在一个参数，直接将属性添加到$命名空间上。
 内部方法assemble更名为setup，并强化调试机制，每加入一个新模块， 都会遍历命名空间与原型上的方法，重写它们，添加try catch逻辑。
 2012.5.6更新rdebug,不处理大写开头的自定义"类"
 2012.6.5 对IE的事件API做更严格的判定,更改"@target"为"@bind"
 2012.6.10 精简require方法 处理opera11.64的情况
 2012.6.13 添加异步列队到命名空间,精简domReady
 2012.6.14 精简innerDefine,更改一些术语
 2012.6.25 domReady后移除绑定事件
 2012.7.23 动态指定mass Framewoke的命名空间与是否调试
 2012.8.26 升级到v17
 2012.8.27 将$.log.level改到$.config.level中去
 2012.8.28 将最后一行的this改成self
 2012.9.12 升级到v18 添加本地储存的支持
 2012.11.21 升级到v19 去掉CMD支持与$.debug的实现,增加循环依赖的判定
 2012.12.5 升级到v20，参考requireJS的实现，去掉iframe检测，暴露define与require
 2012.12.16 精简loadCSS 让getCurrentScript更加安全
 2012.12.18 升级v21 处理opera readyState BUG 与IE6下的节点插入顺序
 2012.12.26 升级v22 移除本地储存，以后用插件形式实现，新增一个HTML5 m标签的支持
 2013.1.22 处理动态插入script节点的BUG, 对让getCurrentScript进行加强
 2013.4.1 升级支v23 支持动态添加加载器，正确取得加载器所在的节点的路径
 2013.4.3 升级支v24 支持不按AMD规范编写的JS文件加载
 
 http://stackoverflow.com/questions/326596/how-do-i-wrap-a-function-in-javascript
 https://github.com/eriwen/javascript-stacktrace
 不知道什么时候开始，"不要重新发明轮子"这个谚语被传成了"不要重新造轮子"，于是一些人，连造轮子都不肯了。
 重新发明东西并不会给我带来论文发表，但是它却给我带来了更重要的东西，这就是独立的思考能力。
 一旦一个东西被你“想”出来，而不是从别人那里 “学”过来，那么你就知道这个想法是如何产生的。
 这比起直接学会这个想法要有用很多，因为你知道这里面所有的细节和犯过的错误。而最重要的，
 其实是由此得 到的直觉。如果直接去看别人的书或者论文，你就很难得到这种直觉，因为一般人写论文都会把直觉埋藏在一堆符号公式之下，
 让你看不到背后的真实想法。如果得到了直觉，下一次遇到类似的问题，你就有可能很快的利用已有的直觉来解决新的问题。
 Javascript 文件的同步加载与异步加载 http://www.cnblogs.com/ecalf/archive/2012/12/12/2813962.html
 http://sourceforge.net/apps/trac/pies/wiki/TypeSystem/zh
 http://tableclothjs.com/ 一个很好看的表格插件
 http://layouts.ironmyers.com/
 http://warpech.github.com/jquery-handsontable/
 http://baidu.365rili.com/wnl.html?bd_user=1392943581&bd_sig=23820f7a2e2f2625c8945633c15089dd&canvas_pos=search&keyword=%E5%86%9C%E5%8E%86
 http://unscriptable.com/2011/10/02/closures-for-dummies-or-why-iife-closure/
 http://unscriptable.com/2011/09/30/amd-versus-cjs-whats-the-best-format/
 http://news.cnblogs.com/n/157042/
 http://www.cnblogs.com/beiyuu/archive/2011/07/18/iframe-tech-performance.html iframe异步加载技术及性能
 http://www.cnblogs.com/lhb25/archive/2012/09/11/resources-that-complement-twitter-bootstrap.html
 http://www.cnblogs.com/rainman/archive/2011/06/22/2086069.html
 http://www.infoq.com/cn/articles/how-to-create-great-js-module 优秀的JavaScript模块是怎样炼成的
 http://y.duowan.com/resources/js/jsFrame/demo/index.html
 https://github.com/etaoux/brix
 */