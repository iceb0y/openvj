PARTICLE_MAP = 
[
    [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,1,0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,1,0,1,0,0,0,0,0,0],
    [0,0,0,0,0,0,1,0,0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,1,0,0,1,0,0,0,0,0,0],
    [0,0,0,0,0,1,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,0,0],
    [0,0,0,0,1,1,1,1,1,1,1,1,0,0,1,0,0,0,0,0,0,1,0,0,1,1,1,1,1,1,1,1,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],
    [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0]
]

PARTICLE_INVALID_COLOR = '#E8E8E8'
PARTICLE_COLORS = ['#FF0606', '#FF9900', '#00C627', '#00B6F2', '#F064D8']
PARTICLE_TYPE_CIRCLE = 0
PARTICLE_TYPE_SQURE = 1

WORLD_GRID_DISTANCE = 20
WORLD_MARGIN = 20

MOUSETHRESH = 30

######### RUNTIME VARIABLES #########
PARTICLE_ROWS = PARTICLE_COLS = 0
SCALE = MAX_SCALE = 2
CANVAS_W = CANVAS_H = 0
CANVAS_OFFSET_LEFT = CANVAS_OFFSET_TOP = 0
#####################################
canvas = null
ctx = null
physics = null

mouseParticle = null
lastParticle = null

Particles = []
ValidParticles = []
#####################################

class Particle

    constructor: (@position, @data) ->

        @particle = physics.makeParticle 0.5, 0, 0, 0
        @anchor = physics.makeParticle 1, 0, 0, 0

        if data.type is PARTICLE_TYPE_SQURE
            @particle.position.x = @anchor.position.x = CANVAS_W
            @particle.position.y = @anchor.position.y = CANVAS_H
        else
            @particle.position.x = @anchor.position.x = position.x
            @particle.position.y = @anchor.position.y = position.y

        @anchor.makeFixed()

        @_s = physics.makeSpring @anchor, @particle, 0.03, 0.1, 0
        @_a = physics.makeAttraction @particle, mouseParticle, -5, 1
        
        @alpha = 0
        @alphaSpeed = 0
        @alphaTarget = 0
        
        @rotate = 0
        @rotateSpeed = 0
        @rotateTarget = 0

        @radius = 0
        @radiusSpeed = 0
        @radiusTarget = 0

        @animateOK = false

    update: ->

        @radius += (@radiusTarget - @radius) * @radiusSpeed
        @alpha += (@alphaTarget - @alpha) * @alphaSpeed

        if @data.type is PARTICLE_TYPE_SQURE
            @rotate += (@rotateTarget - @rotate) * @rotateSpeed

    distance: ->

        return @particle.position.distanceSquaredTo mouseParticle.position

    draw: ->

        ctx.save()
        ctx.fillStyle = @data.color
        ctx.globalAlpha = @alpha
        
        if @data.type is PARTICLE_TYPE_SQURE

            radius = @radius * 1.5

            ctx.translate @particle.position.x, @particle.position.y
            ctx.rotate @rotate
            ctx.fillRect -radius / 2, -radius / 2, radius, radius

        else

            ctx.beginPath()
            ctx.arc @particle.position.x, @particle.position.y, @radius, 0, Math.PI * 2, false
            ctx.fill()
        
        ctx.restore()

    over: ->

        document.body.style.cursor = 'pointer';

        @particle.makeFixed()

        @alphaTarget = 0.4
        @alphaSpeed = 0.5

        @radiusTarget = 70
        @radiusSpeed = 0.6

        @rotateTarget = Math.PI / 4 * 2
        @rotateSpeed = 0.2

    out: ->

        document.body.style.cursor = 'default';

        @particle.fixed = false

        @alphaTarget = 1
        @alphaSpeed = 0.01

        @radiusTarget = 3
        @radiusSpeed = 0.1

        @rotateTarget = Math.PI / 4 * 3
        @rotateSpeed = 0.1

init = ->

    physics = new ParticleSystem 0, 0, 0, 0.1

    mouseParticle = physics.makeParticle 200, 0, 0, 0
    mouseParticle.makeFixed()

    canvas = mass.query('#canvas')[0]
    ctx = canvas.getContext '2d'

    CANVAS_OFFSET_TOP = canvas.offsetTop

    PARTICLE_ROWS = PARTICLE_MAP.length
    PARTICLE_COLS = PARTICLE_MAP[0].length

    CANVAS_W = (PARTICLE_COLS - 1) * WORLD_GRID_DISTANCE + WORLD_MARGIN * 2
    CANVAS_H = (PARTICLE_ROWS - 1) * WORLD_GRID_DISTANCE + WORLD_MARGIN * 2

    canvas.width = CANVAS_W * SCALE
    canvas.height = CANVAS_H * SCALE

    # Generate particles
    for line, l in PARTICLE_MAP

        for v, c in line

            do (line, l, v, c) ->

                data = {}
                data.type = v

                if v is PARTICLE_TYPE_SQURE
                    data.color = PARTICLE_COLORS[Math.floor(Math.random() * PARTICLE_COLORS.length)]
                else
                    data.color = PARTICLE_INVALID_COLOR

                p = new Particle({x: WORLD_MARGIN + c * WORLD_GRID_DISTANCE, y: WORLD_MARGIN + l * WORLD_GRID_DISTANCE}, data)
                
                Particles.push p
                ValidParticles.push p if v is PARTICLE_TYPE_SQURE

bind_events = ->

    $event.on [window], 'mousemove', event_onMouseMove
    $event.on [window], 'resize', event_onResize
    $event.on [canvas], 'click', event_onClick

event_onClick = ->

    return if not lastParticle?
    
    for p in Particles
        if p isnt lastParticle
            p._a.constant = -(Math.random() * 3000 + 500)
            p._s.on = false
            p.animateOK = false

    p = lastParticle
    p.animateOK = false
    p.rotateSpeed = 0.1
    p.rotateTarget = Math.PI / 4
    p.radiusSpeed = 0.1
    p.radiusTarget = 0
    
    setTimeout ->
        window.location = '/'
    , 1000

event_onResize = ->

    WINDOW_W = jQuery(window).width()

    SCALE = WINDOW_W * 0.9 / CANVAS_W
    SCALE = MAX_SCALE if SCALE > MAX_SCALE

    canvas.width = CANVAS_W * SCALE
    canvas.height = CANVAS_H * SCALE

    CANVAS_OFFSET_LEFT = canvas.offsetLeft

event_onMouseMove = (e) ->

    mouseParticle.position.x = (e.clientX - CANVAS_OFFSET_LEFT) / SCALE
    mouseParticle.position.y = (e.clientY - CANVAS_OFFSET_TOP) / SCALE

event_onUpdate = ->

    requestAnimationFrame event_onUpdate

    ctx.clearRect 0, 0, canvas.width, canvas.height
    ctx.save()
    ctx.scale SCALE, SCALE

    physics.tick()

    closestDistance = MOUSETHRESH * MOUSETHRESH
    closestParticle = null

    for p in ValidParticles
        d = p.distance()
        if d < closestDistance
            closestDistance = d
            closestParticle = p

    if lastParticle? and lastParticle isnt closestParticle
        lastParticle.out() if lastParticle.animateOK

    if closestParticle? and lastParticle isnt closestParticle
        closestParticle.over() if closestParticle.animateOK

    lastParticle = closestParticle

    if closestParticle?
        closestParticle.update()
        closestParticle.draw()

    for p in Particles
        if p isnt closestParticle
            p.update()
            p.draw()

    ctx.restore()

$ready ->

    init()

    setTimeout ->

        bind_events()
        event_onResize()
        particle_start()
        event_onUpdate()

    , 500

################# Landing animation #################

particle_timer = null
particle_count = 0
particle_offset = 40
particle_pos = [[0,0],[0,0]]
particle_pos_max = [[0,0],[0,0]]

# Calculate the next particle index
# id is stage_id
particle_next_index = (id) ->

    particle_target = particle_pos[id][1] * PARTICLE_COLS + particle_pos[id][0]
    
    if particle_pos[id][0] is 0 or particle_pos[id][1] is PARTICLE_ROWS - 1
        if particle_pos_max[id][0] < PARTICLE_COLS - 1
            particle_pos_max[id][0]++
        else
            particle_pos_max[id][1]++

        particle_pos[id][0] = particle_pos_max[id][0]
        particle_pos[id][1] = particle_pos_max[id][1]
    else
        particle_pos[id][0]--
        particle_pos[id][1]++

    return particle_target

particle_start = ->

    particle_timer = setInterval ->

        ########### Stage 1 ###########

        if particle_count < Particles.length

            idx = particle_next_index 0
            p = Particles[idx]
                
            p.anchor.position.x = p.position.x
            p.anchor.position.y = p.position.y

            p.alphaSpeed = 0.3
            p.alphaTarget = 0.3
            
            p.radiusSpeed = 0.2
            p.radiusTarget = [10, 30][p.data.type]
            
            p.rotateSpeed = 0.2
            p.rotateTarget = Math.PI / 4

        ########### Stage 2 ###########

        if particle_count >= particle_offset
            
            idx = particle_next_index 1
            p = Particles[idx]
            
            if p.data.type is PARTICLE_TYPE_CIRCLE
                p.alphaTarget = 0.7
                p.alphaSpeed = 0.03
            else
                p.alphaTarget = 1
                p.alphaSpeed = 0.08

            p.radiusSpeed = 0.05
            p.radiusTarget = 3
            p.rotateSpeed = 0.03
            p.rotateTarget = Math.PI / 4 * 3
            p.animateOK = true

        ###############################

        particle_count++

        clearInterval particle_timer if (particle_count - particle_offset) >= Particles.length

    , 1