# Release Notes WordPress Plugin
>v 1.0

Release Notes is a WordPress plugin that help you keep track of features that have been added over time to your themes.

## Features
* Support for both parent and child themes
* Release notes are visible under the WordPress Dashboard

## How it works
It's simple! Just add a <code>release-notes.json</code> file under your site root. If your site is a child theme, it will also attempt to grab a <code>release-notes.json</code> file from the parent. The format of the file is the following:

```
{
    <!-- The version number -->
    "1.0.0":{ 

        <!-- A description of the release -->
        "release_description" : "Version 1.1 of Release Notes has some new features.",

        <!-- The date of the release -->
        "release_date" : "2017-09-01 00:00:00",

        <!-- Each individual note for the release. This will be formatted into a descriptive list -->
        "release_notes" :[
            {
                <!-- A good title for this note-->
                "note_title"       : "All da features",

                <!-- An optional description-->
                "note_description" : null,

                <!-- Bullets for the note. This will be formatted into an unordered list-->
                "note_bullets"     :[
                    "<mark>Mark it up!</mark>"
                ]
            }
        ]
    }
}
```

When you have multiple version, it may look like this:

```
{
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
}

```

## Style.css
Your <code>style.css</code> file's version line should match up with this file.

## Prereleases
The name of the page where release notes are will show a pre-release flag if there is a build number associated with the version of the theme in <code>style.css</code> in the form of the version number followed by a hyphen and build number. So, as an example, version 1.0.0-0 represents the first build of version 1.0.0 while version 1.0.0-1 represents of the second build of version 1.0.0.

## Best Pratices
Typically, I'll make the release note version the final version of the theme I'm aiming for (1.0, 1.1, 1.0.1, etc.) and simply add to each note during my spring. As I'm making changes, I'll update the version of the theme in the <code>style.css</code> with the sprint number. So, for instance, if I'm working on version 1.0.0, The release note will be for version 1.0.0 and the version in <code>style.css</code> will be 1.0.0-0 (the first sprint). After I finish my first sprint, it'll go to 1.0.0-1, and so on. When the theme is moved to production, <code>style.css</code> will remove the build number and just be 1.0.0.  

## License

This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or distribute this software, either in source code form or as a compiled binary, for any purpose, commercial or non-commercial, and by any means.

In jurisdictions that recognize copyright laws, the author or authors of this software dedicate any and all copyright interest in the software to the public domain. We make this dedication for the benefit of the public at large and to the detriment of our heirs and successors. We intend this dedication to be an overt act of relinquishment in perpetuity of all present and future rights to this software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
