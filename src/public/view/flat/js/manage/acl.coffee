frozenHeader = null
frozenDOMs = []

ACL_RULES = window.ACL_RULES
ACL_GROUPS = window.ACL_GROUPS
ACL_PRIVTREE = window.ACL_PRIVTREE
ACL_PRIVTABLE = window.ACL_PRIVTABLE

freezer = null

repeatSpace = (n) ->

    s = ''
    s += '&nbsp;' for i in [1..n]

    s

findInRouteNodes = (tag) ->

    p = tag.split '_'
    ret = []
    ref = ACL_PRIVTREE

    for tag in p
        ret.push ref
        ref = ref[tag]

    ret

initACLRules = ->

    # Clean ACL Rules
    _ACL_RULES = {}
    _ACL_RULES[gid] = {} for gid of ACL_GROUPS

    for tag, value of ACL_PRIVTABLE

        pid = parseInt value.v

        for gid of ACL_GROUPS

            if not ACL_RULES[gid][pid]?
                _ACL_RULES[gid][pid] = {i: true, v: false} 
            else
                _ACL_RULES[gid][pid] = {i: false, v: ACL_RULES[gid][pid]}

    for tag, value of ACL_PRIVTREE
        
        root_ptag = tag
        root_pid = value._v

        break

    # ROOT Node 无继承
    for gid of ACL_GROUPS

        _ACL_RULES[gid][root_pid].root = true
        _ACL_RULES[gid][root_pid].i = false

    ACL_RULES = _ACL_RULES

    # 初始化继承
    for gid of ACL_GROUPS

        updateSubACLRules gid, root_ptag, root_pid

    # Render
    for tag, value of ACL_PRIVTABLE
        
        pid = parseInt value.v

        for gid of ACL_GROUPS

            renderACLRules pid, gid

renderACLRules = (pid, gid) ->

    flag = if ACL_RULES[gid][pid].v then 't' else 'f'
    flag += 'i' if ACL_RULES[gid][pid].i

    jQuery("#priv#{pid}").children("[name=\"g#{gid}\"]").attr 'data-flag', flag

initDOM = ->

    table = $new 'table', class: 'acl-table'

    colgroup = '<colgroup><col class="c1"><col class="c2"><col class="c3">'
    groups = ''
    ls = '<thead class="thead"><tr class="acl-table-headtr"><th class="c1 acl-table-th">#</th><th class="c2 acl-table-th">Tag</th><th class="c3 acl-table-th"><div>Description</div></th>'

    for gid, gname of ACL_GROUPS

        ls += "<th title=\"value(#{gid})\" class=\"cx acl-table-th\">#{gname}</th>"
        groups += "<td class=\"cx\" name=\"g#{gid}\"></td>"
        colgroup += '<col class="cx">'
    
    ls += '</tr></thead><tbody class="tbody"></tbody>'
    colgroup += '</colgroup>'

    table.innerHTML = colgroup + ls

    ##########################################

    $tbody = jQuery(table).find('tbody')
    currentPath = []

    generateTree = ($root, depth, namespace) ->

        for key, node of $root
            continue if key is '_v' or key is '_d'

            currentPath.push key

            if node._v?

                $tbody.append jQuery('<tr id="priv{v}" data-path="{full}" class="acl-table-bodytr"><td class="c1">{v}</td><td class="c2" title="{full}">{node}</td><td class="c3" title="{desc}"><div>{desc}</div></td>{groups}</tr>'.format
                    v:      node._v
                    node:   repeatSpace(depth * 4) + namespace + key
                    desc:   node._d
                    groups: groups
                    full:   currentPath.join '_'
                )

                nd = depth + 1
                ns = ''

            else

                nd = depth
                ns = namespace + key + '_'

            generateTree node, nd, ns

            currentPath.pop()

    generateTree ACL_PRIVTREE, 0, ''

    # Hot tracking
    $tbody.on 'mouseover', 'tr', ->

        # Highlight parent nodes

        $tbody.children('tr').removeClass 'parent'

        tag = jQuery(@).attr 'data-path'
        parentPrivNodes = findInRouteNodes tag

        # $freezeDOMs = []

        for node in parentPrivNodes

            if node._v?

                $dom = jQuery('#priv' + node._v)
                $dom.addClass 'parent'
                # $freezeDOMs.push $dom

        # freezeHeaders $freezeDOMs
        
    $tbody.on 'mousedown', 'td.cx', adjustACLRules
    $tbody.on 'contextmenu', 'td.cx', -> false

    jQuery('#privTable').append table

    # Freeze Table Header
    ###
    clonedDOM = jQuery(table).find('thead>tr').clone()
    frozenHeader = clonedDOM
    rearrangeFixedRows frozenHeader, jQuery(table).find('thead>tr'), jQuery('#freezing .thead')
    ###

adjustACLRules = (e) ->

    $dom = jQuery(@)

    gid = $dom.attr('name').substr 1
    pid = $dom.closest('tr').attr('id').substr 4
    ptag = $dom.closest('tr').attr('data-path')

    switch event.which
        
        when 1  # left

            ACL_RULES[gid][pid] = {v: true, i: false}

        when 3  # right

            ACL_RULES[gid][pid] = {v: false, i: false}

        when 2  # middle

            break if ACL_RULES[gid][pid].root
            ACL_RULES[gid][pid] = {v: queryACLFromParent(gid, ptag), i: true}

    renderACLRules pid, gid
    updateSubACLRules gid, ptag, pid

    false

updateSubACLRules = (gid, ptag, pid) ->

    p = ptag.split '_'

    ref = ACL_PRIVTREE
    ref = ref[tag] for tag in p

    rule = ACL_RULES[gid][pid].v

    for key, node of ref

        continue if key is '_v' or key is '_d'
        
        setSubACLRules node, gid, rule

        # TODO: Bug Fix: not properly inherited.

setSubACLRules = (node, gid, rule) ->
    
    if node._v?

        cp = ACL_RULES[gid][node._v]

        if cp.i is false
            rule = cp.v
        else
            cp.v = rule
            renderACLRules node._v, gid

    for key, subnode of node

        continue if key is '_v' or key is '_d'

        setSubACLRules subnode, gid, rule

queryACLFromParent = (gid, ptag) ->

    p = ptag.split '_'
    p.pop()
    pid = null

    ref = ACL_PRIVTREE

    for tag in p

        ref = ref[tag]
        pid = ref._v if ref._v?

    return false if pid is null

    ACL_RULES[gid][pid].v

rearrangeFixedRows = ($dom, $source, $target) ->

    $children = $dom.children()

    $dom.removeAttr 'id'
    $dom.data 'src', $source

    $source.children('td, th').each (idx) ->

        $children.eq(idx).width jQuery(@).width()

    $target.append $dom


freezeHeaders = ($freezeDOMs) ->

    frozenDOMs = []

    $content = jQuery('#freezing .tbody').empty()

    for $dom, index in $freezeDOMs
        $clonedDOM = $dom.clone()
        frozenDOMs.push $clonedDOM
        rearrangeFixedRows $clonedDOM, $dom, $content

    jQuery(window).scroll()

onWindowScroll = (e) ->

    scrollTop = jQuery(window).scrollTop()

    if frozenHeader.data('src').position().top < scrollTop + jQuery('#freezing').height()
        frozenHeader.show()
    else
        frozenHeader.hide()

    for $dom in frozenDOMs
        if $dom.data('src').position().top < scrollTop + jQuery('#freezing').height()
            $dom.show()
        else
            $dom.hide()

saveACLRule = ->

    acl_rule = {}
    acl = {}

    for gid of ACL_GROUPS

        acl_rule[gid] = {}
        acl[gid] = {}

        for tag, value of ACL_PRIVTABLE

            pid = value.v

            if not ACL_RULES[gid][pid].i
                acl_rule[gid][pid] = ACL_RULES[gid][pid].v

            acl[gid][pid] = ACL_RULES[gid][pid].v

    VJ.ajax

        action:    '/manage/acl'
        data:      {acl: JSON.stringify(acl), acl_rule: JSON.stringify(acl_rule)}
        freezer:   freezer

        onSuccess: (d) ->

            VJ.Dialog.alert 'ACL list saved.', 'ACL'

            setTimeout ->
                window.location.reload()
            , 1500

        onFailure: (d) ->

            VJ.Dialog.alert d.errorMsg, 'ACL Error'

$ready ->

    # jQuery(window).scroll onWindowScroll

    freezer = new VJ.Freezer
        container:  mass.query('.manage-acl')

    initDOM()
    initACLRules()

    jQuery('.role-acl-save').click saveACLRule
