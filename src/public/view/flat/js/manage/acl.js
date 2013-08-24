(function() {
  var ACL_GROUPS, ACL_PRIVTABLE, ACL_PRIVTREE, ACL_RULES, adjustACLRules, findInRouteNodes, freezeHeaders, frozenDOMs, frozenHeader, initACLRules, initDOM, onWindowScroll, queryACLFromParent, rearrangeFixedRows, renderACLRules, repeatSpace, setSubACLRules, updateSubACLRules;

  frozenHeader = null;

  frozenDOMs = [];

  ACL_RULES = window.ACL_RULES;

  ACL_GROUPS = window.ACL_GROUPS;

  ACL_PRIVTREE = window.ACL_PRIVTREE;

  ACL_PRIVTABLE = window.ACL_PRIVTABLE;

  repeatSpace = function(n) {
    var i, s, _i;
    s = '';
    for (i = _i = 1; 1 <= n ? _i <= n : _i >= n; i = 1 <= n ? ++_i : --_i) {
      s += '&nbsp;';
    }
    return s;
  };

  findInRouteNodes = function(tag) {
    var p, ref, ret, _i, _len;
    p = tag.split('_');
    ret = [];
    ref = ACL_PRIVTREE;
    for (_i = 0, _len = p.length; _i < _len; _i++) {
      tag = p[_i];
      ret.push(ref);
      ref = ref[tag];
    }
    return ret;
  };

  initACLRules = function() {
    var gid, pid, root_pid, root_ptag, tag, value, _ACL_RULES, _results;
    _ACL_RULES = {};
    for (gid in ACL_GROUPS) {
      _ACL_RULES[gid] = {};
    }
    for (tag in ACL_PRIVTABLE) {
      value = ACL_PRIVTABLE[tag];
      pid = parseInt(value.v);
      for (gid in ACL_GROUPS) {
        if (ACL_RULES[gid][pid] == null) {
          _ACL_RULES[gid][pid] = {
            i: true,
            v: false
          };
        } else {
          _ACL_RULES[gid][pid] = {
            i: false,
            v: ACL_RULES[gid][pid]
          };
        }
      }
    }
    for (tag in ACL_PRIVTREE) {
      value = ACL_PRIVTREE[tag];
      root_ptag = tag;
      root_pid = value._v;
      break;
    }
    for (gid in ACL_GROUPS) {
      _ACL_RULES[gid][root_pid].root = true;
      _ACL_RULES[gid][root_pid].i = false;
    }
    ACL_RULES = _ACL_RULES;
    for (gid in ACL_GROUPS) {
      updateSubACLRules(gid, root_ptag, root_pid);
    }
    _results = [];
    for (tag in ACL_PRIVTABLE) {
      value = ACL_PRIVTABLE[tag];
      pid = parseInt(value.v);
      _results.push((function() {
        var _results1;
        _results1 = [];
        for (gid in ACL_GROUPS) {
          _results1.push(renderACLRules(pid, gid));
        }
        return _results1;
      })());
    }
    return _results;
  };

  renderACLRules = function(pid, gid) {
    var flag;
    flag = ACL_RULES[gid][pid].v ? 't' : 'f';
    if (ACL_RULES[gid][pid].i) {
      flag += 'i';
    }
    return jQuery("#priv" + pid).children("[name=\"g" + gid + "\"]").attr('data-flag', flag);
  };

  initDOM = function() {
    var $tbody, colgroup, currentPath, generateTree, gid, gname, groups, ls, table;
    table = $new('table');
    colgroup = '<colgroup><col class="c1"><col class="c2"><col class="c3">';
    groups = '';
    ls = '<thead class="thead"><tr><th class="c1">#</th><th class="c2">Tag</th><th class="c3"><div>Description</div></th>';
    for (gid in ACL_GROUPS) {
      gname = ACL_GROUPS[gid];
      ls += "<th title=\"value(" + gid + ")\" class=\"cx\">" + gname + "</th>";
      groups += "<td class=\"cx\" name=\"g" + gid + "\"></td>";
      colgroup += '<col class="cx">';
    }
    ls += '</tr></thead><tbody class="tbody"></tbody>';
    colgroup += '</colgroup>';
    table.innerHTML = colgroup + ls;
    $tbody = jQuery(table).find('tbody');
    currentPath = [];
    generateTree = function($root, depth, namespace) {
      var key, nd, node, ns, _results;
      _results = [];
      for (key in $root) {
        node = $root[key];
        if (key === '_v' || key === '_d') {
          continue;
        }
        currentPath.push(key);
        if (node._v != null) {
          $tbody.append(jQuery('<tr id="priv{v}" data-path="{full}"><td class="c1">{v}</td><td class="c2" title="{full}">{node}</td><td class="c3" title="{desc}"><div>{desc}</div></td>{groups}</tr>'.format({
            v: node._v,
            node: repeatSpace(depth * 4) + namespace + key,
            desc: node._d,
            groups: groups,
            full: currentPath.join('_')
          })));
          nd = depth + 1;
          ns = '';
        } else {
          nd = depth;
          ns = namespace + key + '_';
        }
        generateTree(node, nd, ns);
        _results.push(currentPath.pop());
      }
      return _results;
    };
    generateTree(ACL_PRIVTREE, 0, '');
    $tbody.on('mouseover', 'tr', function() {
      var parentPrivNodes, tag;
      $tbody.children('tr').removeClass('parent');
      tag = jQuery(this).attr('data-path');
      return parentPrivNodes = findInRouteNodes(tag);
      /*
      $freezeDOMs = []
      
      for node in parentPrivNodes
      
          if node._v?
      
              $dom = jQuery('#priv' + node._v)
              $dom.addClass 'parent'
              $freezeDOMs.push $dom
      
      freezeHeaders $freezeDOMs
      */

    });
    $tbody.on('mousedown', 'td.cx', adjustACLRules);
    $tbody.on('contextmenu', 'td.cx', function() {
      return false;
    });
    return jQuery('#privTable').append(table);
    /*
    clonedDOM = jQuery(table).find('thead>tr').clone()
    frozenHeader = clonedDOM
    rearrangeFixedRows frozenHeader, jQuery(table).find('thead>tr'), jQuery('#freezing .thead')
    */

  };

  adjustACLRules = function(e) {
    var $dom, gid, pid, ptag;
    $dom = jQuery(this);
    gid = $dom.attr('name').substr(1);
    pid = $dom.closest('tr').attr('id').substr(4);
    ptag = $dom.closest('tr').attr('data-path');
    switch (event.which) {
      case 1:
        ACL_RULES[gid][pid] = {
          v: true,
          i: false
        };
        break;
      case 3:
        ACL_RULES[gid][pid] = {
          v: false,
          i: false
        };
        break;
      case 2:
        if (ACL_RULES[gid][pid].root) {
          break;
        }
        ACL_RULES[gid][pid] = {
          v: queryACLFromParent(gid, ptag),
          i: true
        };
        console.log(ACL_RULES[gid][pid]);
    }
    renderACLRules(pid, gid);
    updateSubACLRules(gid, ptag, pid);
    return false;
  };

  updateSubACLRules = function(gid, ptag, pid) {
    var key, node, p, ref, rule, tag, _i, _len, _results;
    p = ptag.split('_');
    ref = ACL_PRIVTREE;
    for (_i = 0, _len = p.length; _i < _len; _i++) {
      tag = p[_i];
      ref = ref[tag];
    }
    rule = ACL_RULES[gid][pid].v;
    _results = [];
    for (key in ref) {
      node = ref[key];
      if (key === '_v' || key === '_d') {
        continue;
      }
      _results.push(setSubACLRules(node, gid, rule));
    }
    return _results;
  };

  setSubACLRules = function(node, gid, rule) {
    var cp, key, subnode, _results;
    if (node._v != null) {
      cp = ACL_RULES[gid][node._v];
      if (cp.i === false) {
        rule = cp.v;
      } else {
        cp.v = rule;
        renderACLRules(node._v, gid);
      }
    }
    _results = [];
    for (key in node) {
      subnode = node[key];
      if (key === '_v' || key === '_d') {
        continue;
      }
      _results.push(setSubACLRules(subnode, gid, rule));
    }
    return _results;
  };

  queryACLFromParent = function(gid, ptag) {
    var p, pid, ref, tag, _i, _len;
    p = ptag.split('_');
    p.pop();
    pid = null;
    ref = ACL_PRIVTREE;
    for (_i = 0, _len = p.length; _i < _len; _i++) {
      tag = p[_i];
      ref = ref[tag];
      if (ref._v != null) {
        pid = ref._v;
      }
    }
    if (pid === null) {
      return false;
    }
    return ACL_RULES[gid][pid].v;
  };

  rearrangeFixedRows = function($dom, $source, $target) {
    var $children;
    $children = $dom.children();
    $dom.removeAttr('id');
    $dom.data('src', $source);
    $source.children('td, th').each(function(idx) {
      return $children.eq(idx).width(jQuery(this).width());
    });
    return $target.append($dom);
  };

  freezeHeaders = function($freezeDOMs) {
    var $clonedDOM, $content, $dom, index, _i, _len;
    frozenDOMs = [];
    $content = jQuery('#freezing .tbody').empty();
    for (index = _i = 0, _len = $freezeDOMs.length; _i < _len; index = ++_i) {
      $dom = $freezeDOMs[index];
      $clonedDOM = $dom.clone();
      frozenDOMs.push($clonedDOM);
      rearrangeFixedRows($clonedDOM, $dom, $content);
    }
    return jQuery(window).scroll();
  };

  onWindowScroll = function(e) {
    var $dom, scrollTop, _i, _len, _results;
    scrollTop = jQuery(window).scrollTop();
    if (frozenHeader.data('src').position().top < scrollTop + jQuery('#freezing').height()) {
      frozenHeader.show();
    } else {
      frozenHeader.hide();
    }
    _results = [];
    for (_i = 0, _len = frozenDOMs.length; _i < _len; _i++) {
      $dom = frozenDOMs[_i];
      if ($dom.data('src').position().top < scrollTop + jQuery('#freezing').height()) {
        _results.push($dom.show());
      } else {
        _results.push($dom.hide());
      }
    }
    return _results;
  };

  $ready(function() {
    initACLRules();
    return initDOM();
  });

}).call(this);

/*
//@ sourceMappingURL=acl.js.map
*/