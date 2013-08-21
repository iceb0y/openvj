module.exports = function (grunt)
{

     var vjlib_prefix = '../../src/public/static/lib/vijos-ext/';
     var theme_flat_prefix = '../../src/public/view/flat/';

     grunt.initConfig(
     {
          pkg: grunt.file.readJSON('package.json'),

          concat:
          {
               vjext:
               {
                    src: 
                    [
                         vjlib_prefix + 'lib/util/proto.js',
                         vjlib_prefix + 'lib/ecma5/ecma5.js',
                         vjlib_prefix + 'lib/mass-Framework/mass.js',
                         vjlib_prefix + 'lib/mass-Framework/Icarus.js',
                         vjlib_prefix + 'lib/qatrix/qatrix-1.1.js',
                         vjlib_prefix + 'lib/json/json2.js',
                         vjlib_prefix + 'lib/moment/moment.min.js',
                         vjlib_prefix + 'lib/moment/lang/zh-cn.js',
                         vjlib_prefix + 'lib/textillate/assets/jquery.fittext.js',
                         vjlib_prefix + 'lib/textillate/assets/jquery.lettering.js',
                         vjlib_prefix + 'lib/textillate/jquery.textillate.js',
                         vjlib_prefix + 'lib/select2/select2.js',
                         vjlib_prefix + 'lib/select2/select2_locale_zh-CN.js',
                         vjlib_prefix + 'lib/icheck/jquery.icheck.js',
                         vjlib_prefix + 'lib/tipsy/jquery.tipsy.js'
                    ],
                    dest: vjlib_prefix + 'vijos-ext.js'
               }
          },

          coffee:
          {
               vjlib:
               {
                    options: {join: true},
                    src:
                    [
                         vjlib_prefix + 'src/*.coffee'
                    ],
                    dest: vjlib_prefix + 'vijos.js'
               },
               theme_flat:
               {
                    expand:   true,
                    cwd:      theme_flat_prefix + 'js/',
                    src:      ['**/*.coffee'],
                    dest:     theme_flat_prefix + 'js/',
                    ext:      '.js'
               }
          },

          stylus:
          {
               theme_flat:
               {
                    expand:   true,
                    cwd:      theme_flat_prefix + 'css/',
                    src:      ['*.styl'],
                    dest:     theme_flat_prefix + 'css/',
                    ext:      '.css'
               }
          },

          autoprefixer:
          {
               theme_flat:
               {
                    expand:   true,
                    cwd:      theme_flat_prefix + 'css/',
                    src:      ['*.css'],
                    dest:     theme_flat_prefix + 'css/',
                    ext:      '.css'
               }
          }

     });

     grunt.loadNpmTasks('grunt-contrib-watch');
     grunt.loadNpmTasks('grunt-contrib-concat');
     grunt.loadNpmTasks('grunt-contrib-coffee');
     grunt.loadNpmTasks('grunt-contrib-stylus');
     grunt.loadNpmTasks('grunt-contrib-uglify');
     grunt.loadNpmTasks('grunt-contrib-cssmin');
     grunt.loadNpmTasks('grunt-autoprefixer');

     grunt.registerTask('default', ['concat', 'coffee', 'stylus', 'autoprefixer']);

};