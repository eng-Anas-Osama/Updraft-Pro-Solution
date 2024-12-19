<?php 
    add_action('updraftplus_action_backup_remote_complete', 'remove_old_google_drive_backups', 10, 2);

    function remove_old_google_drive_backups($backup_type, $backup_set) {
        if ($backup_type !== 'google_drive') {
            return; // Only handle Google Drive backups
        }
    
        // Load UpdraftPlus Google Drive handler
        if (!class_exists('UpdraftPlus_RemoteStorage_google_drive')) {
            return;
        }
    
        $google_drive = new UpdraftPlus_RemoteStorage_google_drive();
    
        // Retrieve folder path where backups are stored
        $site_name = get_bloginfo('name') ?: 'default_site';
        $custom_folder_name = sanitize_title($site_name) . '_Backups';
        $folder_path = trailingslashit($custom_folder_name);
    
        // Fetch files in the backup folder
        $files = $google_drive->listfiles($folder_path);
    
        if (is_array($files) && !empty($files)) {
            $max_retained_backups = 1; // Adjust as needed
            usort($files, function ($a, $b) {
                return $a['timestamp'] <=> $b['timestamp'];
            });
    
            // Delete older files exceeding the retention limit
            foreach (array_slice($files, 0, max(0, count($files) - $max_retained_backups)) as $file) {
                $google_drive->deletefile($file['id']);
            }
        }
    }
    
?>