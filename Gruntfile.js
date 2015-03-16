module.exports = function(grunt) {

  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          "wp-content/themes/AFBFramework/assets/css/style.min.css": "wp-content/themes/AFBFramework/assets/css/style.less" // destination file and source file
        }
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
            tasks: ['less']
        },
        js:{
            files: 'wp-content/themes/AFBFramework/assets/js/main.js',
            tasks: ['uglify']
        }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');


  grunt.registerTask('default', ['less', 'uglify']);

};