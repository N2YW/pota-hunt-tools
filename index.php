<?php
// Function to parse an ADI line and extract required fields
function parse_adi_line($line) {
    $fields = [];
    preg_match_all('/<(\w+):(\d+):?.*?>([^<]+)/', $line, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $fields[strtolower($match[1])] = $match[3];
    }
    return $fields;
}

// Function to format date from YYYYMMDD to MM/DD/YYYY
function format_date($date) {
    if (strlen($date) == 8) {
        return substr($date, 4, 2) . '/' . substr($date, 6, 2) . '/' . substr($date, 0, 4);
    }
    return $date;
}

// Read the ADI file
$file_path = 'C:\Users\Brandon\AppData\Local\WSJT-X\wsjtx_log.adi'; // Replace with your ADI file path
$pota_entries = [];
$lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Process the file from the end to find the last 5 POTA entries
for ($i = count($lines) - 1; $i >= 0 && count($pota_entries) < 10; $i--) {
    $line = trim($lines[$i]);
    if (strpos($line, '<eor>') !== false) {
        $fields = parse_adi_line($line);
        if (isset($fields['comment']) && stripos($fields['comment'], 'pota') !== false) {
            $pota_entries[] = [
                'call' => $fields['call'] ?? '',
                'mode' => $fields['mode'] ?? '',
                'band' => $fields['band'] ?? '',
                'qso_date' => isset($fields['qso_date']) ? format_date($fields['qso_date']) : '',
                'comment' => $fields['comment'] ?? ''
            ];
        }
    }
}
// Reverse to display in chronological order (most recent last)
$pota_entries = array_reverse($pota_entries);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last 5 POTA Entries</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color:rgb(37, 37, 37);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color:rgb(77, 76, 76);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e0f7fa;
        }
        @media screen and (max-width: 600px) {
            th, td {
                padding: 8px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Latest POTA Entries</h1>
        <?php if (empty($pota_entries)): ?>
            <p style="text-align: center; color: #777;">No POTA entries found in the log file.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Call</th>
                        <th>Mode</th>
                        <th>Band</th>
                        <th>QSO Date</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pota_entries as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry['call']); ?></td>
                            <td><?php echo htmlspecialchars($entry['mode']); ?></td>
                            <td><?php echo htmlspecialchars($entry['band']); ?></td>
                            <td><?php echo htmlspecialchars($entry['qso_date']); ?></td>
                            <td><?php echo htmlspecialchars($entry['comment']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>