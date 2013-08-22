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

PARTICLE_COLORS = ['#FF4D4D', '#FF4D4D', '#FFBF00', '#00D900', '#26C9FF', '#FF73FF']

PARTICLE_TYPE_SQURE = 0
PARTICLE_TYPE_CIRCLE = 1

PARTICLE_ROWS = 0
PARTICLE_COLS = 0

WORLD_GRID_DISTANCE = 20
WORLD_MARGIN = 20

SCALE = MAX_SCALE = 2
CANVAS_W =  CANVAS_H = 0

MOUSETHRESH = 30

canvas = null
ctx = null
physics = null

mouseParticle = null

MOUSEOVER_PARTICLE = null

Particles = []
ValidParticles = []

class Particle

    constructor: (@position, @data) ->

        # @anchor = physics.makeParticle 1, 0, 0, 0
        # @anchor.makeFixed()
        # @restorePosition()

        @particle = physics.makeParticle 1, 0, 0, 0
        @particle.position.x = @position.x
        @particle.position.y = @position.y

        @alpha = 1
        @alphaSpeed = 0
        @targetAlpha = 1
        
        @rotate = 0
        @rotateSpeed = 0
        @targetRotate = 0

        @radius = 0
        @radiusSpeed = 0
        @targetRadius = 0

        @animateOK = false

    restorePosition: ->

        # @anchor.position.x = @position.x
        # @anchor.position.y = @position.y

    update: ->

        @radius += (@targetRadius - @radius) * @radiusSpeed

        if @data.type is PARTICLE_TYPE_SQURE
            @alpha += (@targetAlpha - @alpha) * @alphaSpeed
            @rotate += (@targetRotate - @rotate) * @rotateSpeed

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

        @targetAlpha = 0.3
        @alphaSpeed = 0.5

        @targetRadius = 70
        @radiusSpeed = 0.2

        @targetRotate = Math.PI / 4 * 2
        @rotateSpeed = 0.2

    out: ->

        @targetAlpha = 1
        @alphaSpeed = 0.01

        @targetRadius = 4
        @radiusSpeed = 0.1

        @targetRotate = Math.PI / 4 * 3
        @rotateSpeed = 0.1

init = ->

    physics = new ParticleSystem 0, 0, 0, 0

    mouseParticle = physics.makeParticle 200, 0, 0, 0
    mouseParticle.makeFixed()

    canvas = mass.query('#canvas')[0]
    ctx = canvas.getContext '2d'

    $event.on [canvas], 'mousemove', event_onMouseMove
    $event.on [window], 'resize', event_onResize

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
                data.type = [PARTICLE_TYPE_CIRCLE, PARTICLE_TYPE_SQURE][v]

                if v is 1
                    data.color = PARTICLE_COLORS[Math.floor(Math.random() * PARTICLE_COLORS.length)]
                else
                    data.color = '#000'

                p = new Particle({x: WORLD_MARGIN + c * WORLD_GRID_DISTANCE, y: WORLD_MARGIN + l * WORLD_GRID_DISTANCE}, data)
                
                if v isnt 1
                    p.alpha = 0.05

                Particles.push p
                ValidParticles.push p if v is 1


event_onResize = (e) ->

    w = jQuery(window).width() * 0.9

    SCALE = w / CANVAS_W
    SCALE = MAX_SCALE if SCALE > MAX_SCALE

    canvas.width = CANVAS_W * SCALE
    canvas.height = CANVAS_H * SCALE

event_onMouseMove = (e) ->

    mouseParticle.position.x = (e.offsetX) / SCALE
    mouseParticle.position.y = (e.offsetY) / SCALE

event_onUpdate = ->

    requestAnimationFrame event_onUpdate

    ctx.clearRect 0, 0, canvas.width, canvas.height
    ctx.save()
    ctx.scale SCALE, SCALE

    closestDistance = MOUSETHRESH * MOUSETHRESH
    closestParticle = null

    for p in ValidParticles
        d = p.distance()
        if d < closestDistance
            closestDistance = d
            closestParticle = p

    if closestParticle?
        closestParticle.over() if closestParticle.animateOK

    if MOUSEOVER_PARTICLE? and MOUSEOVER_PARTICLE isnt closestParticle
        MOUSEOVER_PARTICLE.out() if MOUSEOVER_PARTICLE.animateOK

    MOUSEOVER_PARTICLE = closestParticle

    if closestParticle?
        closestParticle.update()
        closestParticle.draw()

    for p in Particles
        if p isnt closestParticle
            p.update()
            p.draw()

    ctx.restore()

$ready ->

    setTimeout ->

        init()
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

        if particle_count < Particles.length

            idx = particle_next_index 0
            Particles[idx].radiusSpeed = 0.2
            Particles[idx].targetRadius = 8
            Particles[idx].rotateSpeed = 0.2
            Particles[idx].targetRotate = Math.PI / 4

        if particle_count >= particle_offset
            idx = particle_next_index 1
            Particles[idx].radiusSpeed = 0.05
            Particles[idx].targetRadius = 4
            Particles[idx].rotateSpeed = 0.03
            Particles[idx].targetRotate = Math.PI / 4 * 3
            Particles[idx].animateOK = true

        particle_count++

        clearInterval particle_timer if (particle_count - particle_offset) >= Particles.length

    , 1