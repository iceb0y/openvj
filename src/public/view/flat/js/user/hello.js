(function() {
  var scrollToId;

  scrollToId = function(id) {
    var dom, pos;
    console.log(id);
    dom = mass.query(".hello-screen[data-id=\"" + id + "\"]");
    pos = jQuery(dom).offset().top;
    return jQuery('body').animate({
      scrollTop: pos
    }, 800);
  };

  $ready(function() {
    var docHeight, dom, dom_parallax_container, i, parallax_count, _i, _ref;
    $style.set(mass.query('.hello-screen'), 'height', jQuery(window).height() + 'px');
    docHeight = jQuery('#container').height();
    dom_parallax_container = mass.query('.hello-parallax-container')[0];
    parallax_count = 50;
    for (i = _i = 0, _ref = parallax_count - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
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
    $css.set(document.body, {
      height: 'auto'
    });
    $event.on(mass.query('.role-next'), 'click', function() {
      return scrollToId(jQuery(this).closest('.hello-screen').next().attr('data-id'));
    });
    $event.on(mass.query('.role-skip'), 'click', function() {
      return scrollToId($attr.get(this, 'data-skip'));
    });
    $event.on(mass.query('.role-end'), 'click', function() {
      return window.location = '/';
    });
    jQuery(window).stellar();
    return jQuery('input').iCheck();
  });

}).call(this);

/*
//@ sourceMappingURL=hello.js.map
*/