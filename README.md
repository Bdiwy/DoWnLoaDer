🎵 DoWnLoaDer - Download YouTube Playlists Easily! 🚀

📌 About

DoWnLoaDer is a simple PHP script that allows you to download entire YouTube playlists using yt-dlp. It automatically creates a folder for the playlist and saves all videos inside it. 🎬📂

🔧 Requirements

Before using this script, ensure you have the following installed:

✅ PHP (CLI mode enabled)

✅ yt-dlp (for downloading YouTube videos)

✅ Windows OS (Tested on Windows)

📥 Installation

Follow these steps to set up the script:

Clone the Repository

git clone https://github.com/bdiwy/DoWnLoaDer.git
cd DoWnLoaDer

Ensure yt-dlp is Installed
Run the following command to check if yt-dlp is installed:

yt-dlp --version

If not installed, download it from yt-dlp GitHub.

🚀 How to Use

Open Command Prompt and navigate to the script folder:

cd path/to/DoWnLoaDer

Run the script with a YouTube playlist URL:

php download_playlist.php "https://www.youtube.com/playlist?list=YOUR_PLAYLIST_ID"

What Happens?

The script fetches the playlist title.

It creates a folder in your Downloads directory with the playlist name.

All videos from the playlist are downloaded into that folder.

📂 Where Are the Videos Saved?

The videos will be saved in:

C:\Users\YourUsername\Downloads\<Playlist_Name>

🔧 Troubleshooting

❌ Error: yt-dlp is not installed

Make sure yt-dlp is installed and added to your system PATH.

❌ Error: Failed to create folder

Run the script with administrator privileges.

💖 Support

If you find this project useful, please ⭐ star the repository on GitHub! 😊