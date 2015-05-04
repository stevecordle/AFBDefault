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
            'wp-content/themes/AFBFramework/assets/js/app.min.js': ['vendor/twbs/bootstrap/dist/js/bootstrap.js', 'wp-content/themes/AFBFramework/assets/js/lib/jquery.mmenu.min.js', 'wp-content/themes/AFBFramework/assets/js/main.js']
          }
        }
    },
    imagemin: {
        png: {
            options: {
                optimizationLevel: 7
            },
            files: [{
                expand: true,
                cwd: 'wp-content/themes/AFBFramework/assets/img/orig/',
                src: ['*.png'],
                dest: 'wp-content/themes/AFBFramework/assets/img/',
                ext: '.png'
            }]
        },
        jpg: {
            options: {
                progressive: true
            },
            files: [{
                expand: true,
                cwd: 'wp-content/themes/AFBFramework/assets/img/orig/',
                src: ['*.jpg'],
                dest: 'wp-content/themes/AFBFramework/assets/img/',
                ext: '.jpg'
            }]
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
        },
        img:{
            files: ['wp-content/themes/AFBFramework/assets/img/orig/*'],
            tasks: ['newer:imagemin']
        }
    }
  });

  grunt.loadNpmTasks('grunt-newer');
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['less', 'uglify', 'imagemin']);

};