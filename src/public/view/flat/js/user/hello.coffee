scrollToId = (id) ->

    dom = $_query ".hello-screen[data-id=\"#{id}\"]"
    pos = dom[0].offsetTop
    jQuery('body').animate {scrollTop: pos}, 800

window.onInitEnd.push ->

    $style.set $_query('.hello-screen'), 'height', jQuery(window).height() + 'px'

    docHeight = $_query('#container')[0].offsetHeight 
    dom_parallax_container = $_query('.hello-parallax-container')[0]
    parallax_count = 50

    for i in [0...parallax_count]

        dom = $new 'div',
            class: 'hello-parallax hello-parallax-' + ((i % 13)+1).toString()
            'data-stellar-ratio': Math.random() * 0.8 + 0.2

        $css.set dom,

            opacity:  (Math.random() * 0.5 + 0.2).toString()
            left:     (Math.floor(Math.random() * 150 - 25)).toString() + '%'
            top:      (docHeight * 1 / parallax_count * (i+1)) + 'px'

        $append dom_parallax_container, dom

    $event.on $_query('.role-next'), 'click', ->

        scrollToId jQuery(@).closest('.hello-screen').next().attr('data-id')

    $event.on $_query('.role-skip'), 'click', ->

        scrollToId $attr.get(@, 'data-skip')

    $event.on $_query('.role-end'), 'click', ->

        window.location = CONFIG.basePrefix + '/'

    jQuery(window).stellar()
    jQuery('input').iCheck()