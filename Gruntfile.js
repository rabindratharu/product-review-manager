module.exports = function (grunt) {
    'use strict';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        copy: {
            main: {
                options: {
                    mode: true,
                },
                src: [
                    '**',
                    '!style - Copy.css',
                    '!resources/**',
                    '!assets/src/**',
                    '!node_modules/**',
                    '!vendor/**',
                    '!cypress/**',
                    '!css/sourcemap/**',
                    '!.git/**',
                    '!bin/**',
                    '!.gitlab-ci.yml',
                    '!tests/**',
                    '!phpunit.xml.dist',
                    '!*.sh',
                    '!*.map',
                    '!.gitignore',
                    '!.babelrc',
                    '!phpunit.xml',
                    '!README.md',
                    '!codesniffer.ruleset.xml',
                    '!phpcs.xml.dist',
                    '!phpcs.xml',
                    '!CONTRIBUTING.md',
                    '!phpcs.ruleset.xml',
                    '!editorconfig.txt',
                    /**
                     * Are you developer? Then add below files.
                     */
                    '!Gruntfile.js',
                    '!postcss.config.js',
                    '!tailwind.config.js',
                    '!babel.config.js',
                    '!cypress.config.js',
                    '!webpack.config.js',
                    '!package.json',
                    '!package-lock.json',
                    '!composer.json',
                    '!composer.lock',
                    '!yarn.lock',
                    '!sass/**',
                    '!*.zip',
                ],
                dest: 'product-review-manager/',
            },
        },

        compress: {
            main: {
                options: {
                    archive: 'product-review-manager.zip',
                    mode: 'zip',
                },
                files: [
                    {
                        src: ['./product-review-manager/**'],
                    },
                ],
            },
        },

        clean: {
            main: ['product-review-manager'],
            zip: ['product-review-manager.zip'],
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/',
                    mainFile: 'product-review-manager.php',
                    potFilename: 'languages/product-review-manager.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true,
                        'pot-creation-date': new Date().toISOString(), // Ensure this is correctly defined
                        'language-team': 'Your Team <team@example.com>', // Optional: Add language team
                        'report-msgid-bugs-to': 'https://example.com/support', // Optional: Add bug report URL
                    },
                    type: 'wp-theme',
                    updateTimestamp: true,
                },
            },
        },

        wp_readme_to_markdown: {
            your_target: {
                files: {
                    'README.md': 'readme.txt',
                },
            },
        },

        addtextdomain: {
            options: {
                textdomain: 'product-review-manager',
            },
            target: {
                files: {
                    src: [
                        '*.php',
                        '**/*.php',
                        '!node_modules/**',
                        '!php-tests/**',
                        '!bin/**',
                    ],
                },
            },
        },

        /**
         * Check textdomain
         */
        checktextdomain: {
            standard: {
                options: {
                    text_domain: 'product-review-manager', //Specify allowed domain(s)
                    keywords: [
                        //List keyword specifications
                        '__:1,2d',
                        '_e:1,2d',
                        '_x:1,2c,3d',
                        'esc_html__:1,2d',
                        'esc_html_e:1,2d',
                        'esc_html_x:1,2c,3d',
                        'esc_attr__:1,2d',
                        'esc_attr_e:1,2d',
                        'esc_attr_x:1,2c,3d',
                        '_ex:1,2c,3d',
                        '_n:1,2,4d',
                        '_nx:1,2,4c,5d',
                        '_n_noop:1,2,3d',
                        '_nx_noop:1,2,3c,4d',
                    ],
                },
                files: [
                    {
                        src: [
                            '**/*.php', //all php
                            '!node_modules/**',
                        ],
                        expand: true,
                    },
                ],
            },
        },
    });

    /**
     * Load Grunt Tasks
     */
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-checktextdomain');

    /* Read File Generation task */
    grunt.loadNpmTasks('grunt-wp-readme-to-markdown');

    // Generate Read me file
    grunt.registerTask('readme', ['wp_readme_to_markdown']);

    // i18n
    grunt.registerTask('i18n', ['checktextdomain', 'addtextdomain', 'makepot']);

    // Generate Release package
    grunt.registerTask('release', [
        'clean:zip',
        'copy',
        'compress',
        'clean:main',
    ]);

    grunt.util.linefeed = '\n';
};
