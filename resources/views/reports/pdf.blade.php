<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KPI Report - {{ ucfirst($reportType) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2563eb;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            background: #f9fafb;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #2563eb;
            border-bottom: 1px solid #2563eb;
            padding-bottom: 5px;
        }
        .kpi-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        .kpi-item {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KPI Performance Report</h1>
        <p><strong>Report Type:</strong> {{ ucfirst($reportType) }}</p>
        <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        <p><strong>Generated:</strong> {{ now()->format('M d, Y h:i A') }}</p>
    </div>

    @if($reportType === 'summary' && is_array($data))
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Measurements</div>
            <div class="stat-value">{{ $data['total_measurements'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $data['total_users'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Average Score</div>
            <div class="stat-value">{{ $data['avg_score'] }}/6</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Average %</div>
            <div class="stat-value">{{ $data['avg_percentage'] }}%</div>
        </div>
    </div>

    <div class="section-title">KPI Breakdown</div>
    <div class="kpi-grid">
        @foreach($data['kpi_breakdown'] as $kpi => $count)
        <div class="kpi-item">
            <strong>{{ ucwords(str_replace('_', ' ', $kpi)) }}</strong><br>
            {{ $count }}
        </div>
        @endforeach
    </div>

    <div class="stats-grid" style="margin-top: 20px;">
        <div class="stat-box" style="width: 50%; background: #dcfce7;">
            <div class="stat-label">Good Logs</div>
            <div class="stat-value" style="color: #16a34a;">{{ $data['good_logs'] }}</div>
        </div>
        <div class="stat-box" style="width: 50%; background: #fee2e2;">
            <div class="stat-label">Bad Logs</div>
            <div class="stat-value" style="color: #dc2626;">{{ $data['bad_logs'] }}</div>
        </div>
    </div>
    @endif

    @if($reportType === 'detailed' && $data)
    <div class="section-title">Detailed Measurements</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Department</th>
                <th>Score</th>
                <th>Percentage</th>
                <th>Logs</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $measurement)
            <tr>
                <td>{{ $measurement->measurement_date->format('M d, Y') }}</td>
                <td>{{ $measurement->user->name }}</td>
                <td>{{ $measurement->user->department->name ?? 'N/A' }}</td>
                <td><strong>{{ $measurement->total_score }}/6</strong></td>
                <td>{{ round($measurement->percentage, 1) }}%</td>
                <td>{{ $measurement->logs->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($reportType === 'individual' && $data)
    <div class="section-title">Individual Performance - {{ $data['user']->name }}</div>
    <p><strong>Position:</strong> {{ $data['user']->position->name ?? 'N/A' }} | <strong>Department:</strong> {{ $data['user']->department->name ?? 'N/A' }}</p>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Measurements</div>
            <div class="stat-value">{{ $data['total_measurements'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Avg Score</div>
            <div class="stat-value">{{ $data['avg_score'] }}/6</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Good Logs</div>
            <div class="stat-value" style="color: #16a34a;">{{ $data['good_logs'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Bad Logs</div>
            <div class="stat-value" style="color: #dc2626;">{{ $data['bad_logs'] }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Score</th>
                <th>Ready</th>
                <th>Counter</th>
                <th>Clean</th>
                <th>Stock</th>
                <th>Order</th>
                <th>Follow-up</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['measurements'] as $m)
            <tr>
                <td>{{ $m->measurement_date->format('M d') }}</td>
                <td><strong>{{ $m->total_score }}/6</strong></td>
                <td>{{ $m->ready_to_sale ? '✓' : '✗' }}</td>
                <td>{{ $m->counter_check ? '✓' : '✗' }}</td>
                <td>{{ $m->cleanliness ? '✓' : '✗' }}</td>
                <td>{{ $m->stock_check ? '✓' : '✗' }}</td>
                <td>{{ $m->order_handling ? '✓' : '✗' }}</td>
                <td>{{ $m->customer_followup ? '✓' : '✗' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($reportType === 'department' && $data)
    <div class="section-title">Department Report - {{ $data['department']->name }}</div>
    <p><strong>Total Users:</strong> {{ $data['total_users'] }}</p>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Position</th>
                <th>Measurements</th>
                <th>Avg Score</th>
                <th>Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['users'] as $index => $userData)
            <tr>
                <td><strong>#{{ $index + 1 }}</strong></td>
                <td>{{ $userData['user']->name }}</td>
                <td>{{ $userData['user']->position->name ?? 'N/A' }}</td>
                <td>{{ $userData['measurements_count'] }}</td>
                <td><strong>{{ $userData['avg_score'] }}/6</strong></td>
                <td>{{ round($userData['avg_percentage'], 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Sales Administration System Management | Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated report. No signature required.</p>
    </div>
</body>
</html>
