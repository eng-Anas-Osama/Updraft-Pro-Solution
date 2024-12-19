<?php
add_filter( 'updraftplus_google_drive_folder', 'custom_updraftplus_google_drive_folder', 10, 2 );

function custom_updraftplus_google_drive_folder( $folder_path, $backup_type ) {
    // Get the site name safely and provide a fallback
    $site_name = get_bloginfo( 'name' ) ?: 'default_site';
    
    // Sanitize and create the folder name
    $custom_folder_name = sanitize_title( $site_name ) . '_Backups';
    
    // Optionally, include the backup type for further organization
    $folder_path = trailingslashit( $custom_folder_name ) . $backup_type;

    // Return the customized folder path
    return $folder_path;
}
?>