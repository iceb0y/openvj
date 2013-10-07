(function() {
  var scrollToId;

  scrollToId = function(id) {
    var dom, pos;
    dom = $_query(".hello-screen[data-id=\"" + id + "\"]");
    pos = dom[0].offsetTop;
    return jQuery('body').animate({
      scrollTop: pos
    }, 800);
  };

  window.onInitEnd.push(function() {
    var docHeight, dom, dom_parallax_container, i, parallax_count, _i;
    $style.set($_query('.hello-screen'), 'height', jQuery(window).height() + 'px');
    docHeight = $_query('#container')[0].offsetHeight;
    dom_parallax_container = $_query('.hello-parallax-container')[0];
    parallax_count = 50;
    for (i = _i = 0; 0 <= parallax_count ? _i < parallax_count : _i > parallax_count; i = 0 <= parallax_count ? ++_i : --_i) {
      dom = $new('div', {
        "class": 'hello-parallax hello-parallax-' + ((i % 13) + 1).toString(),
        'data-stellar-ratio': Math.random() * 0.8 + 0.2
      });
      $css.set(dom, {
        opacity: (Math.random() * 0.5 + 0.2).toString(),
        left: (Math.floor(Math.random() * 150 - 25)).toString() + '%',
        top: (docHeight * 1 / parallax_count * (i + 1)) + 'px'
      });
      $append(dom_parallax_container, dom);
    }
    $event.on($_query('.role-next'), 'click', function() {
      return scrollToId(jQuery(this).closest('.hello-screen').next().attr('data-id'));
    });
    $event.on($_query('.role-skip'), 'click', function() {
      return scrollToId($attr.get(this, 'data-skip'));
    });
    $event.on($_query('.role-end'), 'click', function() {
      return window.location = CONFIG.basePrefix + '/';
    });
    jQuery(window).stellar();
    return jQuery('input').iCheck();
  });

}).call(this);

/*
//@ sourceMappingURL=hello.js.map
*/