module.exports = function(grunt) {

    require('jit-grunt')(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            less: {
                files: ['library/less/*.less'],
                tasks: ['less:dist', 'cssmin:css'],
                options: {
                    reload:  true,
                    atBegin: true
                }
            },
            uglify: {
                files: [
                    'library/js/scripts.js',
                ],
                tasks: ['uglify:js']
            }
        },
        jshint: {
              all: ['gruntfile.js', 'library/js/scripts.js']
        },
        less: {
          dist: {
            files: {
              'library/css/style.css': ['library/less/style.less'],
              // add to grunt file when making custom login screens
              //'library/css/login.css': ['library/less/themes/login.less'],
              'library/css/ie.css': ['library/less/themes/ie.less']
            },
            options: {
              compress: false,
              // LESS source maps
              // To enable, set sourceMap to true and update sourceMapRootpath based on your install
              sourceMap: true,
              sourceMapFilename: 'library/css/style.css.map',
              sourceMapRootpath: 'library/css',
              sourceMapURL: '/'
            }
          }
        },
        cssmin: {
            css: {
                src: 'library/css/style.css',
                dest: 'library/css/style.min.css',
                options: {
                    keepSpecialComments: 0,
                    rebase: false
                }
            }
        },
        imagemin: {
          dynamic: {
            files: [
              {
                expand: true,
                cwd: 'app/content/themes/stripped/images/',
                src: ['**/*.{png,jpg,gif}'],
                dest: 'app/content/themes/stripped/images/'
              }
            ]
          }
        },
        // It is optional to use grunticon. For more info http://www.grunticon.com/
        // grunticon: {
        //   icons: {
        //     files: [{
        //       expand: true,
        //       cwd: "images/svg/",
        //       src: ["*.svg"],
        //       dest: "images/dest/"
        //     }],
        //     options: [{
        //         compressPNG: true,
        //         optimizationLevel: 5,
        //         enhanceSVG: true,
        //         pngfolder: "/images/dest/png/",
        //         // colors: {
        //         //     example: #000,
        //         // }
        //     }]
        //   }
        // },


        // setup modernizr files to your needs:
        modernizr: {
            dist: {
                "devFile" : "library/js/vendor/modernizr/modernizr.js",
                "outputFile": "library/js/modernizr.min.js",
                "extra": {
                    "borderradius":true,
                    "shiv": true,
                    "load": true,
                    "cssclasses": true,
                    "csstransitions":true,
                    "cssanimations":true,
                    "fontface": true,
                    "backgroundsize": true,
                    "opacity": true,
                    "rgba": true,
                    "touch": true,
                    "generatedcontent": true,
                    "svg": true,
                    "inlinesvg": true,
                    "localstorage":true,
                    "csstransforms3d": true
                },
                "extensibility": {
                    "addtest": false,
                    "prefixed": false,
                    "teststyles": true,
                    "testprop": true,
                    "testallprops": true,
                    "hasevents": false,
                    "prefixes": false,
                    "domprefixes": false
                },
                "uglify": true,
                "parseFiles": false
            }
        },
        concat: {
            /*
                If you use more than one JS module add wrap bootstrapJsModules between {}
            */
            js: {
                "src": [
                        //add all necessary bootstrap components
                        "library/js/vendor/bootstrap/transition.js",
                        "library/js/vendor/bootstrap/collapse.js",
                        "library/js/vendor/bootstrap/carousel.js"
                        ],
                "dest":   "library/js/libs/bootstrap.js",
                "nocase": true,
                "nonull": true
           },
           tasks: ['uglify:js']
        },
        uglify: {
            js: {
                files: {
                    'library/js/scripts.min.js': ['library/js/app/scripts.js'],
                    'library/js/bootstrap.min.js': ['library/js/libs/bootstrap.js'],
                    'library/js/classie.min.js': ['library/js/vendor/classie/classie.js']
                }
            }
        },
    });

    grunt.registerTask('default', ['newer:less:dist', 'newer:cssmin:css', 'newer:concat:js', 'newer:uglify:js' , 'newer:watch' ]);
    grunt.registerTask('init', ['less:dist', 'modernizr:dist' ,'cssmin:css', 'concat:js', 'uglify:js', 'imagemin']);
};
