=== Release Notes ===
Contributors: jordanleven
Tags: release, notes, themes, versions
Requires at least: 2.7
Tested up to: 5.2
Stable tag: 1.0
License: GPLv2 or later

Release Notes is a WordPress plugin that help you keep track of features that have been added over time to your themes.


== Description ==

For developers who are deploying multiple themes, it's useful to have a full version history that details new features, improvements, and bug fixes. With Release Notes, this is simpler than ever.

Using the provided .json template and your own style.css main stylesheet, you can organize your theme deployments in releases (major, minor, and bug fixes).

**Features**

- Works with single installations and Multisite
- Works with parent themes and child themes
- Parses releases notes into easy-to-read sections that show users what features are new with each new release

**Feature Requests and Bug Reports**

Please report any feature requests you have or bugs you encounter [under the Support tab](https://wordpress.org/support/plugin/release-notes). This is a new plugin and I'm hoping to add more user-requested features to make this useful to developers.

**Grunt Integration**

Are you a Grunt user? Check out [release-notes-to-readme on npmJS](https://www.npmjs.com/package/grunt-release-notes-to-readme). This package can be used to parse our your release notes into a nice ReadMe.md format that can be included in your theme.

== Installation ==

Upload the Release Notes plugin to your site, and then simply activate it. You'll now see the current theme information on your Dashboard and as a submenu under Dashboard. 

== Screenshots ==
1. See the current version of your site in the main Dashboard.
2. The release notes for the most current version will show by default.
3. Click on any release to see notes for that version.


== Frequently Asked Questions ==

= 1. What format do my release notes need to be in? =

Use the provided `release-notes-sample.json` file for inspiration. They should fall under the format of an associative array, where the key for each release notes is the full version (x.x.x).

`{
    "1.1.0":{
        "release_description" : "Version 1.1 of Release Notes has some new features.",
        "release_date" : "2017-09-01 00:00:00",
        "release_notes" :[
            {
                "note_title"       : "All da features",
                "note_description" : null,
                "note_bullets"     :[
                    "<mark>Mark it up!</mark>"
                ]
            }
        ]
    },
    "1.0.0":{
        "release_description" : "Version 1.0 of Release Notes is a major release that includes a fun new way to view release notes for themes.",
        "release_date" : "2017-08-01 00:00:00",
        "release_notes" :[
            {
                "note_title"       : "Initial commit",
                "note_description" : "You can optionally include descriptions. Otherwise, omit it or make its value <code>null</code>",
                "note_bullets"     :[
                    "Supports <b>text</b> <i>styles</i>",
                    "And other stuff"
                ]
            },

            {
                "note_title"       : "More features!",
                "note_description" : null,
                "note_bullets"     :[
                    "Do what ya want"
                ]
            }
        ]
    }
}`

Note that `release_description` and `note_description` are optional fields.

= 2. Where is the current version of the site being pulled in from? =

The current version of your site will be pulled using what's in `style.css`. Ideally, the most recent version in the release notes will match what's the current version is style.css

= 3. It's producing an error that says my notes are invalid =

Make sure your file is valid! Use a JSON validator like [JSON Lint](https://jsonlint.com) to validate your notes.

= 4. It says it cannot find my release notes =

Make sure your file is called `release-notes.json` and is located in the root directory of your theme.

== Changelog ==

= 0.1 =
* Initial release

== Upgrade Notice ==

= 0.1 =
Initial release