(function() {
  var freezer, init_step1, init_step2;

  freezer = null;

  init_step1 = function() {
    setTimeout(function() {
      return $_query('.role-reg-email')[0].focus();
    }, 100);
    $event.on($_query('.role-reg-email-confirm'), 'click', function() {
      var target_mail;
      target_mail = $_query('.role-reg-email')[0].value;
      if (!/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/.test(target_mail)) {
        $text($_query('.reg-hint'), 'Invalid email address ∑(O_O；)');
        return;
      }
      return VJ.ajax({
        action: '/user/register',
        data: {
          email: target_mail
        },
        freezer: freezer,
        onSuccess: function(d) {
          $text($_query('.role-email'), target_mail);
          return $fadeout($_query('.reg-step1'), 100, function() {
            $style.set($_query('.reg-step1'), 'display', 'none');
            $style.set($_query('.reg-step1-result'), 'display', 'block');
            return setTimeout(function() {
              return $fadein($_query('.reg-step1-result'), 100);
            });
          });
        },
        onFailure: function(d) {
          return $text($_query('.reg-hint'), d.errorMsg);
        },
        onError: function(errorText) {
          return VJ.Dialog.alert(errorText, 'Error');
        }
      });
    });
    $event.on($_query('.role-reg-email'), 'keypress', function(event) {
      $empty($_query('.reg-hint'));
      if (event.which === 13) {
        return jQuery('.role-reg-email-confirm').click();
      }
    });
    return $event.on($_query('.role-resend'), 'click', function() {
      return $fadeout($_query('.reg-step1-result'), 100, function() {
        $style.set($_query('.reg-step1-result'), 'display', 'none');
        $style.set($_query('.reg-step1'), 'display', 'block');
        return setTimeout(function() {
          $fadein($_query('.reg-step1'), 100);
          return $_query('.role-reg-email')[0].select();
        });
      });
    });
  };

  init_step2 = function() {
    var dom_password;
    setTimeout(function() {
      return $_query('.role-reg-nickname')[0].focus();
    }, 100);
    jQuery('.textbox').tipsy({
      title: 'data-tip',
      gravity: 'e',
      trigger: 'focus',
      offset: 120,
      className: 'tipsy-reg'
    });
    jQuery('.role-reg-agree').tipsy({
      title: 'data-tip',
      gravity: 'e',
      trigger: 'manual',
      offset: 10,
      className: 'tipsy-reg'
    });
    jQuery('input').iCheck();
    jQuery('.role-reg-agree').on('ifChecked', function() {
      return jQuery(this).tipsy('hide');
    });
    dom_password = $_query('.role-reg-password');
    $event.on(dom_password, 'focus', function() {
      return this.type = 'text';
    });
    $event.on(dom_password, 'blur', function() {
      return this.type = 'password';
    });
    $event.on(dom_password, 'keyup', function() {
      if (this.value.match(/[^\x00-\xff]/g)) {
        return this.value = this.value.replace(/[^\x00-\xff]/g, '');
      }
    });
    return $event.on($_query('.role-reg-submit'), 'click', function() {
      var dom;
      dom = $_query('.role-reg-nickname')[0];
      if (!dom.value.match(/^[^ ^\t]{1,15}$/)) {
        dom.select();
        return false;
      }
      dom = $_query('.role-reg-username')[0];
      if (!dom.value.match(/^[^ ^\t]{3,30}$/)) {
        dom.select();
        return false;
      }
      dom = $_query('.role-reg-password')[0];
      if (!dom.value.match(/^.{5,30}$/)) {
        dom.select();
        return false;
      }
      dom = $_query('.role-reg-agree')[0];
      if (!dom.checked) {
        jQuery('.role-reg-agree').tipsy('show');
        return false;
      }
      return VJ.ajax({
        action: '/user/register',
        data: {
          email: REG_PARAM.mail,
          code: REG_PARAM.code,
          nick: $_query('.role-reg-nickname')[0].value,
          user: $_query('.role-reg-username')[0].value,
          pass: $_query('.role-reg-password')[0].value,
          gender: $_query('[name="reg-gender"]:checked')[0].value,
          agreement: 'accept'
        },
        freezer: freezer,
        onSuccess: function(d) {
          return window.location = CONFIG.basePrefix + '/user/hello';
        },
        onFailure: function(d) {
          return VJ.Dialog.alert(d.errorMsg, 'Error');
        },
        onError: function(errorText) {
          return VJ.Dialog.alert(errorText, 'Error');
        }
      });
    });
  };

  window.onInitEnd.push(function() {
    freezer = new VJ.Freezer({
      container: $_query('.reg-step'),
      dark: true
    });
    $fadein($_query('.reg-step'), 1000);
    if (REG_STEP === 1) {
      return init_step1();
    } else if (REG_STEP === 2) {
      return init_step2();
    }
  });

}).call(this);

/*
//@ sourceMappingURL=reg.js.map
*/