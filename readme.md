# POTA Log Viewer

This PHP script reads and displays the last 10 Parks on the Air (POTA) entries from a WSJT-X log file (`wsjtx_log.adi`) in a styled HTML table.

## Functionality
- **Parses ADI File**: Extracts fields (call, mode, band, QSO date, comment) from an ADI log file using regex.
- **Filters POTA Entries**: Identifies entries with "POTA" in the comment field, collecting up to 10 recent entries.
- **Formats Dates**: Converts QSO dates from `YYYYMMDD` to `MM/DD/YYYY` format.
- **Displays Results**: Renders a responsive HTML table with the extracted POTA entries, including call, mode, band, QSO date, and comment.
- **Handles Empty Logs**: Shows a message if no POTA entries are found.
- **Styling**: Uses CSS for a clean, modern table design with hover effects and mobile responsiveness.

## Requirements
- PHP environment with access to the WSJT-X log file in the user's `%LOCALAPPDATA%\WSJT-X\` directory.
- Web server to serve the HTML output.

## Usage
Place the script in a web server directory, ensure the WSJT-X log file is accessible, and access the script via a browser to view the latest POTA entries.