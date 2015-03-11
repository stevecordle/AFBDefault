module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    less: {
      options: {
        paths: ["wp-content/themes/AFBFramework/assets/css"]
      },
      files: {
        "wp-content/themes/AFBFramework/assets/css/style.css": "wp-content/themes/AFBFramework/assets/css/style.less"
      }
    },
    cssmin:{
        target: {
          files: [{
            expand: true,
            cwd: 'wp-content/themes/AFBFramework/assets/css/',
            src: ['*.css', '!*.min.css'],
            dest: 'wp-content/themes/AFBFramework/assets/css/',
            ext: '.min.css'
          }]
        }
    },
    uglify: {
        my_target: {
          files: {
            'wp-content/themes/AFBFramework/assets/js/app.min.js': ['wp-content/themes/AFBFramework/assets/js/lib/bootstrap.min.js', 'wp-content/themes/AFBFramework/assets/js/lib/jquery.mmenu.min.js', 'wp-content/themes/AFBFramework/assets/js/main.js']
          }
        }
    },
    watch: {
        css: {
            files: ['wp-content/themes/AFBFramework/assets/css/custom/*.less', 'wp-content/themes/AFBFramework/assets/css/style.less'],
            tasks: ['less', 'cssmin']
        },
        js:{
            files: 'wp-content/themes/AFBFramework/assets/js/main.js',
            tasks: ['uglify']
        }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');


  grunt.registerTask('default', ['less', 'cssmin', 'uglify']);

};