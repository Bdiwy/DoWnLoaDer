<?php

$green = "\033[32m";
$reset = "\033[0m";

$logo = <<<EOD
    ____      _       __      __                ____           
   / __ \____| |     / /___  / /   ____  ____ _/ __ \___  _____
  / / / / __ \ | /| / / __ \/ /   / __ \/ __ `/ / / / _ \/ ___/
 / /_/ / /_/ / |/ |/ / / / / /___/ /_/ / /_/ / /_/ /  __/ /    
/_____/\____/|__/|__/_/ /_/_____/\____/\__,_/_____/\___/_/     
EOD;

echo $green . $logo . $reset . "\n";

echo $green . "             createdByBdiwy" . $reset . "\n";

// Function to sanitize the filename
function sanitizeFilename($filename)
{
    $filename = preg_replace('/[^A-Za-z0-9_\- ]/', '_', $filename);
    return trim(str_replace(' ', '_', $filename), '_');
}

// Get the downloads folder path
$downloads_folder = getenv("USERPROFILE") . "\\Downloads";

if ($argc < 2) {
    die("Usage: php download_playlist.php <playlist_url>\n");
}

$playlist_url = escapeshellarg($argv[1]);

// Check if yt-dlp is installed
exec("yt-dlp --version 2>&1", $output, $yt_dlp_installed);
if ($yt_dlp_installed !== 0) {
    die("Error: yt-dlp is not installed. Please install it first.\n");
}

// Get the playlist name
$cmd_get_name = "yt-dlp --flat-playlist --print '%(playlist_title)s' $playlist_url 2>&1";
$playlist_name_output = shell_exec($cmd_get_name);
$playlist_name_lines = explode("\n", trim($playlist_name_output));
$playlist_name = end($playlist_name_lines);

if (!$playlist_name || str_contains($playlist_name, 'WARNING') || str_contains($playlist_name, 'ERROR')) {
    die("Error: Failed to fetch playlist name. Retrying...\n");
}

// If failed, use alternate method
if (empty(trim($playlist_name))) {
    $cmd_get_name_alt = "yt-dlp --print-json $playlist_url 2>&1";
    $playlist_json_output = shell_exec($cmd_get_name_alt);
    $playlist_data = json_decode($playlist_json_output, true);
    $playlist_name = $playlist_data['title'] ?? 'Unknown_Playlist';
}

// Sanitize the playlist name and create the folder
$playlist_folder = substr(sanitizeFilename($playlist_name), 0, 50);
$playlist_path = $downloads_folder . "\\" . $playlist_folder;

if (!is_dir($playlist_path) && !mkdir($playlist_path, 0777, true)) {
    die("Error: Failed to create folder '$playlist_path'.\n");
}

echo "Downloading: $playlist_name\nSaving to: $playlist_path\n";

// Spinner function to show a rotating symbol
function showSpinner()
{
    $spinner = ['|', '/', '-', '\\'];
    $i = 0;
    while (true) {
        echo "\r" . $spinner[$i % 4] . " Please wait... downloading";
        usleep(200000); // Sleep for 200ms
        $i++;
    }
}

// Start the download process and show the spinner
$cmd_download = "yt-dlp --no-cache-dir -v --ignore-errors --retries 5 -o \"$playlist_path/%(title)s.%(ext)s\" $playlist_url 2>&1";
$process = proc_open($cmd_download, [
    1 => ['pipe', 'w'], // stdout is a pipe
    2 => ['pipe', 'w'], // stderr is a pipe
], $pipes);

if (is_resource($process)) {
    // Show spinner while downloading
    $spinner_pid = shell_exec('php -r "while(true){echo \'.\'; sleep(1);}";'); 

    $download_output = stream_get_contents($pipes[1]);
    $download_error = stream_get_contents($pipes[2]);
    
    // Close the process and pipes
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);

    // Stop the spinner after download
    echo "\rDownload completed.\n";

    // Log the output and error messages
    $file_log = $playlist_path . "\\download_errors.log";
    file_put_contents($file_log, $download_output, FILE_APPEND);

    echo $download_error ? "Error: Download failed. Check logs at '$file_log'.\n" : "Download completed.\n";
} else {
    echo "Failed to start the download process.\n";
}
