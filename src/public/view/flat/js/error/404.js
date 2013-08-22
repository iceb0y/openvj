(function() {
  var CANVAS_H, CANVAS_W, MAX_SCALE, MOUSEOVER_PARTICLE, MOUSETHRESH, PARTICLE_COLORS, PARTICLE_COLS, PARTICLE_MAP, PARTICLE_ROWS, PARTICLE_TYPE_CIRCLE, PARTICLE_TYPE_SQURE, Particle, Particles, SCALE, ValidParticles, WORLD_GRID_DISTANCE, WORLD_MARGIN, canvas, ctx, event_onMouseMove, event_onResize, event_onUpdate, init, mouseParticle, particle_count, particle_next_index, particle_offset, particle_pos, particle_pos_max, particle_start, particle_timer, physics;

  PARTICLE_MAP = [[0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]];

  PARTICLE_COLORS = ['#FF4D4D', '#FF4D4D', '#FFBF00', '#00D900', '#26C9FF', '#FF73FF'];

  PARTICLE_TYPE_SQURE = 0;

  PARTICLE_TYPE_CIRCLE = 1;

  PARTICLE_ROWS = 0;

  PARTICLE_COLS = 0;

  WORLD_GRID_DISTANCE = 20;

  WORLD_MARGIN = 20;

  SCALE = MAX_SCALE = 2;

  CANVAS_W = CANVAS_H = 0;

  MOUSETHRESH = 30;

  canvas = null;

  ctx = null;

  physics = null;

  mouseParticle = null;

  MOUSEOVER_PARTICLE = null;

  Particles = [];

  ValidParticles = [];

  Particle = (function() {
    function Particle(position, data) {
      this.position = position;
      this.data = data;
      this.particle = physics.makeParticle(1, 0, 0, 0);
      this.particle.position.x = this.position.x;
      this.particle.position.y = this.position.y;
      this.alpha = 1;
      this.alphaSpeed = 0;
      this.targetAlpha = 1;
      this.rotate = 0;
      this.rotateSpeed = 0;
      this.targetRotate = 0;
      this.radius = 0;
      this.radiusSpeed = 0;
      this.targetRadius = 0;
      this.animateOK = false;
    }

    Particle.prototype.restorePosition = function() {};

    Particle.prototype.update = function() {
      this.radius += (this.targetRadius - this.radius) * this.radiusSpeed;
      if (this.data.type === PARTICLE_TYPE_SQURE) {
        this.alpha += (this.targetAlpha - this.alpha) * this.alphaSpeed;
        return this.rotate += (this.targetRotate - this.rotate) * this.rotateSpeed;
      }
    };

    Particle.prototype.distance = function() {
      return this.particle.position.distanceSquaredTo(mouseParticle.position);
    };

    Particle.prototype.draw = function() {
      var radius;
      ctx.save();
      ctx.fillStyle = this.data.color;
      ctx.globalAlpha = this.alpha;
      if (this.data.type === PARTICLE_TYPE_SQURE) {
        radius = this.radius * 1.5;
        ctx.translate(this.particle.position.x, this.particle.position.y);
        ctx.rotate(this.rotate);
        ctx.fillRect(-radius / 2, -radius / 2, radius, radius);
      } else {
        ctx.beginPath();
        ctx.arc(this.particle.position.x, this.particle.position.y, this.radius, 0, Math.PI * 2, false);
        ctx.fill();
      }
      return ctx.restore();
    };

    Particle.prototype.over = function() {
      this.targetAlpha = 0.3;
      this.alphaSpeed = 0.5;
      this.targetRadius = 70;
      this.radiusSpeed = 0.2;
      this.targetRotate = Math.PI / 4 * 2;
      return this.rotateSpeed = 0.2;
    };

    Particle.prototype.out = function() {
      this.targetAlpha = 1;
      this.alphaSpeed = 0.01;
      this.targetRadius = 4;
      this.radiusSpeed = 0.1;
      this.targetRotate = Math.PI / 4 * 3;
      return this.rotateSpeed = 0.1;
    };

    return Particle;

  })();

  init = function() {
    var c, l, line, v, _i, _len, _results;
    physics = new ParticleSystem(0, 0, 0, 0);
    mouseParticle = physics.makeParticle(200, 0, 0, 0);
    mouseParticle.makeFixed();
    canvas = mass.query('#canvas')[0];
    ctx = canvas.getContext('2d');
    $event.on([canvas], 'mousemove', event_onMouseMove);
    $event.on([window], 'resize', event_onResize);
    PARTICLE_ROWS = PARTICLE_MAP.length;
    PARTICLE_COLS = PARTICLE_MAP[0].length;
    CANVAS_W = (PARTICLE_COLS - 1) * WORLD_GRID_DISTANCE + WORLD_MARGIN * 2;
    CANVAS_H = (PARTICLE_ROWS - 1) * WORLD_GRID_DISTANCE + WORLD_MARGIN * 2;
    canvas.width = CANVAS_W * SCALE;
    canvas.height = CANVAS_H * SCALE;
    _results = [];
    for (l = _i = 0, _len = PARTICLE_MAP.length; _i < _len; l = ++_i) {
      line = PARTICLE_MAP[l];
      _results.push((function() {
        var _j, _len1, _results1;
        _results1 = [];
        for (c = _j = 0, _len1 = line.length; _j < _len1; c = ++_j) {
          v = line[c];
          _results1.push((function(line, l, v, c) {
            var data, p;
            data = {};
            data.type = [PARTICLE_TYPE_CIRCLE, PARTICLE_TYPE_SQURE][v];
            if (v === 1) {
              data.color = PARTICLE_COLORS[Math.floor(Math.random() * PARTICLE_COLORS.length)];
            } else {
              data.color = '#000';
            }
            p = new Particle({
              x: WORLD_MARGIN + c * WORLD_GRID_DISTANCE,
              y: WORLD_MARGIN + l * WORLD_GRID_DISTANCE
            }, data);
            if (v !== 1) {
              p.alpha = 0.05;
            }
            Particles.push(p);
            if (v === 1) {
              return ValidParticles.push(p);
            }
          })(line, l, v, c));
        }
        return _results1;
      })());
    }
    return _results;
  };

  event_onResize = function(e) {
    var w;
    w = jQuery(window).width() * 0.9;
    SCALE = w / CANVAS_W;
    if (SCALE > MAX_SCALE) {
      SCALE = MAX_SCALE;
    }
    canvas.width = CANVAS_W * SCALE;
    return canvas.height = CANVAS_H * SCALE;
  };

  event_onMouseMove = function(e) {
    mouseParticle.position.x = e.offsetX / SCALE;
    return mouseParticle.position.y = e.offsetY / SCALE;
  };

  event_onUpdate = function() {
    var closestDistance, closestParticle, d, p, _i, _j, _len, _len1;
    requestAnimationFrame(event_onUpdate);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.save();
    ctx.scale(SCALE, SCALE);
    closestDistance = MOUSETHRESH * MOUSETHRESH;
    closestParticle = null;
    for (_i = 0, _len = ValidParticles.length; _i < _len; _i++) {
      p = ValidParticles[_i];
      d = p.distance();
      if (d < closestDistance) {
        closestDistance = d;
        closestParticle = p;
      }
    }
    if (closestParticle != null) {
      if (closestParticle.animateOK) {
        closestParticle.over();
      }
    }
    if ((MOUSEOVER_PARTICLE != null) && MOUSEOVER_PARTICLE !== closestParticle) {
      if (MOUSEOVER_PARTICLE.animateOK) {
        MOUSEOVER_PARTICLE.out();
      }
    }
    MOUSEOVER_PARTICLE = closestParticle;
    if (closestParticle != null) {
      closestParticle.update();
      closestParticle.draw();
    }
    for (_j = 0, _len1 = Particles.length; _j < _len1; _j++) {
      p = Particles[_j];
      if (p !== closestParticle) {
        p.update();
        p.draw();
      }
    }
    return ctx.restore();
  };

  $ready(function() {
    return setTimeout(function() {
      init();
      event_onResize();
      particle_start();
      return event_onUpdate();
    }, 500);
  });

  particle_timer = null;

  particle_count = 0;

  particle_offset = 40;

  particle_pos = [[0, 0], [0, 0]];

  particle_pos_max = [[0, 0], [0, 0]];

  particle_next_index = function(id) {
    var particle_target;
    particle_target = particle_pos[id][1] * PARTICLE_COLS + particle_pos[id][0];
    if (particle_pos[id][0] === 0 || particle_pos[id][1] === PARTICLE_ROWS - 1) {
      if (particle_pos_max[id][0] < PARTICLE_COLS - 1) {
        particle_pos_max[id][0]++;
      } else {
        particle_pos_max[id][1]++;
      }
      particle_pos[id][0] = particle_pos_max[id][0];
      particle_pos[id][1] = particle_pos_max[id][1];
    } else {
      particle_pos[id][0]--;
      particle_pos[id][1]++;
    }
    return particle_target;
  };

  particle_start = function() {
    return particle_timer = setInterval(function() {
      var idx;
      if (particle_count < Particles.length) {
        idx = particle_next_index(0);
        Particles[idx].radiusSpeed = 0.2;
        Particles[idx].targetRadius = 8;
        Particles[idx].rotateSpeed = 0.2;
        Particles[idx].targetRotate = Math.PI / 4;
      }
      if (particle_count >= particle_offset) {
        idx = particle_next_index(1);
        Particles[idx].radiusSpeed = 0.05;
        Particles[idx].targetRadius = 4;
        Particles[idx].rotateSpeed = 0.03;
        Particles[idx].targetRotate = Math.PI / 4 * 3;
        Particles[idx].animateOK = true;
      }
      particle_count++;
      if ((particle_count - particle_offset) >= Particles.length) {
        return clearInterval(particle_timer);
      }
    }, 1);
  };

}).call(this);

/*
//@ sourceMappingURL=404.js.map
*/