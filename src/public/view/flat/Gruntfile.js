module.exports = function (grunt)
{

     grunt.initConfig(
     {

          pkg: grunt.file.readJSON('package.json'),

          coffee:
          {
               theme_flat:
               {
                    options:
                    {
                         sourceMap: true
                    },
                    expand:   true,
                    cwd:      'js/',
                    src:      ['**/*.coffee'],
                    dest:     'js/',
                    ext:      '.js'
               }
          },

          uglify:
          {
               theme_flat:
               {
                    expand:   true,
                    cwd:      'js/',
                    src:      ['**/*.js'],
                    dest:     'js/',
                    ext:      '.js'
               }
          },

          stylus:
          {
               theme_flat:
               {
                    expand:   true,
                    cwd:      'css/',
                    src:      ['*.styl'],
                    dest:     'css/',
                    ext:      '.css'
               }
          },

          autoprefixer:
          {
               theme_flat:
               {
                    expand:   true,
                    cwd:      'css/',
                    src:      ['*.css'],
                    dest:     'css/',
                    ext:      '.css'
               }
          },

          cssmin:
          {
               theme_flat:
               {
                    expand:   true,
                    cwd:      'css/',
                    src:      ['*.css'],
                    dest:     'css/',
                    ext:      '.css'
               }
          },

          watch:
          {
               js:
               {
                    files: ['js/**/*.js'],
                    tasks: ['coffee']
               },

               css:
               {
                    files: ['css/**/*.styl'],
                    tasks: ['stylus', 'autoprefixer']
               }
          }

     });

     grunt.loadNpmTasks('grunt-contrib-watch');
     grunt.loadNpmTasks('grunt-contrib-coffee');
     grunt.loadNpmTasks('grunt-contrib-stylus');
     grunt.loadNpmTasks('grunt-contrib-uglify');
     grunt.loadNpmTasks('grunt-contrib-cssmin');
     grunt.loadNpmTasks('grunt-autoprefixer');

     grunt.registerTask('default', ['coffee', 'stylus', 'autoprefixer', 'watch']);
     grunt.registerTask('production', ['coffee', 'uglify', 'stylus', 'autoprefixer', 'cssmin']);

};