<?php

namespace JordanLeven\Plugins\ReleaseNotes;

/*
Plugin Name: Release Notes
Plugin URI: 
Description: Release Notes is a lightweight plugin used to generate easy to read release notes from a .json file located the root of your theme.
Version: 1.0.0
Author: Jordan Leven
Author URI: github.com/jordanleven
Text Domain:  release-notes
*/

add_action("wp_dashboard_setup", function(){

    $current_wordpress_theme      =  wp_get_theme();
    
    $current_wordpress_theme_name =  $current_wordpress_theme['Name'];
    
    wp_add_dashboard_widget("release-notes", "$current_wordpress_theme_name Release Notes", 'JordanLeven\\Plugins\\ReleaseNotes\\show_current_version');

});

add_action( 'admin_menu', function(){

    add_submenu_page(
        'index.php',
        'Release notes',
        'Release notes',
        'read',
        'release_notes',
        'JordanLeven\\Plugins\\ReleaseNotes\\see_release_notes'
    );

});

/**
* Main function used to show the current version of the themes on the Dashboard.
*
* @return   void
*/
function show_current_version(){

    $current_wordpress_theme              =  wp_get_theme();
    
    $current_wordpress_theme_name         =  $current_wordpress_theme['Name'];
    
    $current_wordpress_theme_version_info =  get_version_info($current_wordpress_theme['Version']);
    
    $current_wordpress_theme_version      =  $current_wordpress_theme_version_info['version'];
    
    $current_wordpress_theme_build        =  $current_wordpress_theme_version_info['build'];
    
    if ($current_wordpress_theme_build){

        echo "<p>You are currently on version $current_wordpress_theme_version, build $current_wordpress_theme_build of $current_wordpress_theme_name.</p>";

    }

    else {

        echo "<p>You are currently on version $current_wordpress_theme_version of $current_wordpress_theme_name.</p>";

    }

    echo "<div class=\"button button-primary\" style=\"margin-top:10px\"><a href=\"" . admin_url("index.php?page=release_notes") . "\" style=\"color:white\">See Release Notes</a></div>";

}

/**
* Function use to get the current version info from the current WordPress theme.
*
* @param     string    $version_number    The version number that is retrieved from style.css
*
* @return    array                       The version info (including build numbers)
*/
function get_version_info($version_number){

    // Start by getting the prerelease string (if it exists)
    $prerelease_version    = strpos($version_number, "-") > 0 ? substr($version_number, strpos($version_number, "-") + 1) + 1: null;
    
    // Now, get the release version
    $release_version       = $prerelease_version ? strstr($version_number, '-', true) : $version_number;
    
    // Now that we have the version, get the number of integers in the version number
    $release_version_array = explode(".", $release_version);

    // Version numbers go to the hundredths place if a major version and the thousandths place if 
    // a non-major version.
    $last_item_in_version = end($release_version_array);


    // If we were supplied a thousandths string and the last item was just a zero, don't return it
    if (count($release_version_array) == 3 && $last_item_in_version == 0){

        array_pop($release_version_array);

    }

    // Declare our return;
    $return_array = array();
    
    $return_array['version'] = implode(".", $release_version_array);
    
    $return_array['build']   = $prerelease_version;

    return $return_array;
}

/**
* Main function used to generate the Release Notes view.
*
* @return    void
*/
function see_release_notes(){

    echo "<style>";
    echo ".nav-tabs > li > a:hover {border-color: #eeeeee #eeeeee #dddddd;background-color: #ece9e9;}";
    echo ".nav-tabs > li > a {color:#555555 !important;font-weight:bold;}";
    echo "</style>";

    wp_enqueue_script("release-notes-bootstrap", plugins_url('/release-notes/library/dist/frameworks/bootstrap/') . "bootstrap.3.3.5.min.js", array("jquery"));

    wp_enqueue_style("release-notes-bootstrap", plugins_url('/release-notes/library/dist/frameworks/bootstrap/') . "bootstrap.3.3.4.min.css");

    $current_wordpress_theme              = wp_get_theme();
    
    $current_wordpress_theme_name         = $current_wordpress_theme['Name'];

    $current_wordpress_slug               = get_option('stylesheet');;
    
    $current_wordpress_theme_description  = $current_wordpress_theme['Description'];
    
    $current_wordpress_theme_version_info = get_version_info($current_wordpress_theme['Version']);
    
    $current_wordpress_theme_version      = $current_wordpress_theme_version_info['version'];
    
    $current_wordpress_theme_build        = $current_wordpress_theme_version_info['build'];
    
    $current_wordpress_theme_author       = $current_wordpress_theme['Author'];
    
    $prerelease_string                    = $current_wordpress_theme_build ? "<span class=\"btn btn-primary btn-xs disabled\" style=\"margin-bottom:3px;opacity:1;\">Prerelease - build $current_wordpress_theme_build</span>" : "";

    $theme_release_notes_array = array();

    array_push($theme_release_notes_array, array(

        "theme_name"             => $current_wordpress_theme_name,

        "theme_slug"             => $current_wordpress_slug,

        "release_notes_location" => get_stylesheet_directory() . "/release-notes.json",

    )
);

    if (is_child_theme()){

        // If this is a child theme, get the core info
        $current_wordpress_parent_theme             = wp_get_theme(get_template());

        $current_wordpress_parent_theme_name        =  $current_wordpress_parent_theme['Name'];

        $current_wordpress_parent_theme_slug        =  $current_wordpress_parent_theme['Template'];

        $current_wordpress_parent_theme_description =  $current_wordpress_parent_theme['Description'];

        $current_wordpress_parent_theme_author      =  $current_wordpress_parent_theme['Author'];

        array_push($theme_release_notes_array, array(

            "release_notes_location" => get_template_directory() . "/release-notes.json",
            
            "theme_slug"             => $current_wordpress_parent_theme_slug,

            "theme_name"             => $current_wordpress_parent_theme_name,

        )
    );
    }

    // Create the array we'll use to render all notes
    $all_release_notes = array();

    for ($i=0; $i < count($theme_release_notes_array); $i++) { 

        $these_notes            = $theme_release_notes_array[$i];
        
        $release_notes_location = $these_notes['release_notes_location'];
        
        $release_notes_name     = $these_notes['theme_name'];

        $release_notes_slug     = $these_notes['theme_slug'];

        if (!file_exists($release_notes_location)){

            add_admin_alert("No release notes found at <code>$release_notes_location</code>.", "warning");
        }

        // Check if is JSON
        else if (!json_decode(file_get_contents($release_notes_location))){

            add_admin_alert("Invalid JSON found at <code>$release_notes_location</code>.");

        }

        else {

            $release_notes_content = get_object_vars(json_decode(file_get_contents($release_notes_location)));

            foreach ($release_notes_content as $release_version => $release_notes) {

                if (!array_key_exists($release_version, $all_release_notes)){

                    $all_release_notes[$release_version] = array();

                }

                $all_release_notes[$release_version][$release_notes_slug]['release_theme']    = $release_notes_name;
                
                $all_release_notes[$release_version][$release_notes_slug]['release_data'] = $release_notes;

            }

        }

    }

    echo "<div class=\"wrap\">";

    echo "<h2>Release Notes for $current_wordpress_theme_name $current_wordpress_theme_version $prerelease_string</h2>";

    echo "<h4 style=\"margin:10px 0;\">$current_wordpress_theme_description</h4>";

    render_release_notes($all_release_notes);

    echo "</div>";

}

/**
* Function to render all of the consolidated release notes.
*
* @return    void
*/
function render_release_notes($release_notes_content){

    // Sort the notes by key
    krsort($release_notes_content);

    $accordion_id = "accordion-" . strtolower($this_release_note_theme_slug);

    $accordion_id = "accordion-test";

    echo "<div class=\"panel-group\" id=\"$accordion_id\" role=\"tablist\" aria-multiselectable=\"true\" style=\"margin-top:5px;\">";

    $current_wordpress_theme              =  wp_get_theme();

    $current_wordpress_theme_version_info =  get_version_info($current_wordpress_theme['Version']);

    $current_wordpress_theme_version      =  $current_wordpress_theme_version_info['version'];

    $current_wordpress_theme_build        =  $current_wordpress_theme_version_info['build'];

    $i = 0;

    foreach ($release_notes_content as $release_version_number => $release_version_notes) {

        $release_notes_version      = get_version_info($release_version_number)['version'];

        echo "<div class=\"panel panel-default\">";

        echo "<div class=\"panel-heading\" role=\"tab\" id=\"heading$i\">";

        echo "<h4 class=\"panel-title\">";

        if ($i == 0){

            echo "<a role=\"button\" data-toggle=\"collapse\" data-parent=\"#$accordion_id\" href=\"#collapse_$accordion_id-$i\" aria-expanded=\"true\" aria-controls=\"collapse_$accordion_id-$i\">";

        }
        else {

            echo "<a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\" data-parent=\"#$accordion_id\" href=\"#collapse_$accordion_id-$i\" aria-expanded=\"false\" aria-controls=\"collapse_$accordion_id-$i\">";

        }

        echo "Version " . $release_notes_version;

        // If this is a prerelease and the prerelease version is the same as this version, then don't publish a release date
        if ($release_notes_version  == $current_wordpress_theme_version && $current_wordpress_theme_build){

            echo "<span style=\"float:right;font-size: 12px;font-weight: normal;\">";

            echo "<span class=\"badge\" style=\"margin:0 5px;border:1px solid #f27930;color:#f27930;background-color: transparent;text-transform: uppercase;\">Upcoming release</span>";

            echo "</span>";

        }

        echo "</a>";

        echo "</h4>";

        echo "</div>";

        if ($i == 0){

            echo "<div id=\"collapse_$accordion_id-$i\" class=\"panel-collapse collapse in\" role=\"tabpanel\" aria-labelledby=\"heading_$i\">";

        }

        else {

            echo "<div id=\"collapse_$accordion_id-$i\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading_$i\">";

        }

        echo "<div class=\"panel-body\" style=\"padding: 10px 10px 5px;\">";

        $j = 0;

        echo "<div class=\"row\">";

        foreach ($release_version_notes as $release_theme => $release_value) {

            $release_theme_friendly = $release_value['release_theme'];

            $release_date               = isset($release_value['release_data']->release_date) ? date("M j, Y", strtotime($release_value['release_data']->release_date)) : null;

            $release_description        = isset($release_value['release_data']->release_description) ? $release_value['release_data']->release_description : null;

            $release_notes              = $release_value['release_data']->release_notes;

            echo "<div class=\"col-xxs-12 col-sm-6\">";

            echo "<h2 style=\"margin-bottom:5px\">$release_theme_friendly</h2>";

            echo "<p style=\"margin: 0;font-style: italic;\">$release_date</p>";

            if ($release_description){

                echo "<p>" . $release_description . "</p>";

            }

            echo "<dl style=\"padding:0 10px\">";

            for ($k=0; $k<count($release_notes); $k++){

                echo "<dt>";

                $this_release_note = $release_notes[$k];

                $this_release_note_title       = $this_release_note->note_title;

                $this_release_note_description = isset($this_release_note->note_description) ? $this_release_note->note_description : null;

                $note_bullets                  = isset($this_release_note->note_bullets) ? $this_release_note->note_bullets : null;

                echo "<p style=\"margin:0;\">";

                echo "<b>$this_release_note_title</b>";

                echo "</p>";

                echo "</dt>";

                echo "<dd style=\"margin-bottom:10px\">";

                if ($this_release_note_description){

                    echo "<span class=\"description text-muted\">" . $this_release_note_description . "</span>";
                }

                if ($note_bullets){

                    echo "<ul style=\"list-style:square;padding-left:40px;margin-bottom:20px;margin-top:5px;\">";

                    for ($l=0; $l<count($note_bullets); $l++){

                        $this_bullet = $note_bullets[$l];

                        echo "<li style=\"margin-bottom:3px\">$this_bullet</li>";

                    }

                    echo "</ul>";
                }
                echo "</dd>";


            }

            echo "</dl>";

            $j++;

            echo "</div>";

        }

        echo "</div>";

        echo "</div>";

        echo "</div>";

        echo "</div>";

        $i++;

    }

}

/**
* A function used to add an admin alert to the top of the page.
*
* @param    string    $message       The message to use
* @param    string    $alert_type    The type (class) of alert
*/
function add_admin_alert($message, $alert_type = "error"){

    echo "<div class=\"notice notice-$alert_type\">";
    echo "<p>$message</p>";
    echo "</div>";
}

?>