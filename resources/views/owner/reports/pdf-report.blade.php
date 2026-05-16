<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salon Report - {{ $branch_name }}</title>
    <style>
        @page {
            margin: 20mm;
            font-family: DejaVu Sans, sans-serif;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 22px;
            border-bottom: 3px solid #F58C98;
            padding-bottom: 14px;
        }
        .header h1 {
            color: #3F342D;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            color: #7A6A63;
            margin: 5px 0 0 0;
        }
        .section {
            border-bottom: 1px solid #F7E9EB;
            padding-bottom: 18px;
            margin-bottom: 24px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #3F342D;
            font-size: 14px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #FCEDEF;
            page-break-after: avoid;
        }
        .section-desc {
            font-size: 11px;
            color: #8F7B7F;
            margin-top: -6px;
            margin-bottom: 14px;
            line-height: 1.5;
        }
        .section:last-child {
            border-bottom: none;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: #FCEDEF;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border-left: 4px solid #F58C98;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #F58C98;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #7A6A63;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
            margin-top: 18px;
        }
        th {
            background: #F8EDEE;
            color: #3F342D;
            font-size: 11px;
            font-weight: 700;
            padding: 14px 18px;
            text-align: center;
            vertical-align: middle;
        }
        th:first-child {
            border-radius: 12px 0 0 12px;
        }
        th:last-child {
            border-radius: 0 12px 12px 0;
        }
        td {
            background: #FFF;
            padding: 12px 14px;
            font-size: 12px;
            color: #4A3B35;
            border-top: 1px solid #F5EAEA;
            border-bottom: 1px solid #F5EAEA;
            text-align: center;
            vertical-align: middle;
        }
        td:first-child {
            border-left: 1px solid #F5EAEA;
            border-radius: 12px 0 0 12px;
            text-align: left;
            padding-left: 22px;
            font-weight: 600;
        }
        td:last-child {
            border-right: 1px solid #F5EAEA;
            border-radius: 0 12px 12px 0;
            text-align: center;
        }
        .employee-table td:nth-child(2) {
            text-align: left;
            padding-left: 18px;
        }
        .amount {
            text-align: center;
            letter-spacing: 0.3px;
            font-weight: bold;
            color: #F58C98;
        }
        .chart-note {
            background: #F7EFEF;
            padding: 15px;
            border-radius: 8px;
            font-size: 11px;
            color: #7A6A63;
            margin-top: 15px;
        }
        .no-data {
            text-align: center;
            color: #7A6A63;
            font-style: italic;
            padding: 40px;
        }
        .summary-row {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0 20px;
        }
        .summary-row td {
            width: 33.3%;
            background: #FDF7F8;
            border: 1px solid #F3E3E5;
            padding: 12px 10px;
            text-align: center;
        }
        .summary-label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            color: #A3878C;
            margin-bottom: 10px;
            letter-spacing: .6px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #3F342D;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Salon Muslimah Dina</h1>
        <p>Business Performance Report</p>
        <p><strong>{{ $branch_name }}</strong> | {{ $date_range['label'] }}</p>
        <p>Generated: {{ now()->format('d M Y') }}</p>
    </div>

    @foreach($reports as $report)
        @if($report === 'Financial' && isset($financial_data))
            <div class="section">
                <h2>💳 Financial Summary</h2>

                <p class="section-desc">
                    Overview of salon revenue performance and transaction activity during the selected period.
                </p>

                <table class="summary-row">
                    <tr>
                        <td>
                            <span class="summary-label">Total Revenue</span>
                            <span class="summary-value">
                                Rp {{ number_format($financial_data['total_revenue'] ?? 0, 0, ',', '.') }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-label">Transactions</span>
                            <span class="summary-value">
                                {{ $financial_data['total_transactions'] ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-label">Average Transaction</span>
                            <span class="summary-value">
                                Rp {{ number_format($financial_data['avg_transaction'] ?? 0, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                </table>
                
                @if($financial_data['daily_breakdown']->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Revenue</th>
                                <th>Transactions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($financial_data['daily_breakdown'] as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                                    <td class="amount">Rp {{ number_format($day->revenue, 0, ',', '.') }}</td>
                                    <td>{{ $day->transactions ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">No financial data available for this period</div>
                @endif
            </div>
        @endif

        @if($report === 'Services' && isset($services_data))
            <div class="section">
                <h2>✂️ Top Services</h2>

                <p class="section-desc">
                    Most booked services based on customer demand and booking frequency.
                </p>

                @if($services_data->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Bookings</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = $services_data->sum('total'); @endphp
                            @foreach($services_data as $service)
                                <tr>
                                    <td>{{ $service->nama_layanan }}</td>
                                    <td>{{ $service->total }}</td>
                                    <td>{{ $total > 0 ? round(($service->total / $total) * 100, 1) . '%' : '0%' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">No service data available for this period</div>
                @endif
            </div>
        @endif

        @if($report === 'Employees' && isset($employees_data))
            <div class="section">
                <h2>👨‍💼 Employee Performance</h2>

                <p class="section-desc">
                    Employee workload, service activity, and branch assignment during this reporting period.
                </p>

                @if($employees_data->count() > 0)
                    <table class="employee-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Branch</th>
                                <th>Role</th>
                                <th>Bookings</th>
                                <th>Services</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees_data as $employee)
                                <tr>
                                    <td>{{ $employee->nama_pegawai }}</td>
                                    <td>{{ $employee->nama_cabang ?? '-' }}</td>
                                    <td>{{ ucfirst($employee->role) }}</td>
                                    <td>{{ $employee->total_booking }}</td>
                                    <td>{{ $employee->total_services }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">No employee data available for this period</div>
                @endif
            </div>
        @endif

        @if($report === 'Customers' && isset($customers_data))
            <div class="section">
                <h2>👥 Customer Analytics</h2>

                <p class="section-desc">
                    Customer growth, retention, and loyalty trends during this reporting period.
                </p>

                <table class="summary-row">
                    <tr>

                        <td>
                            <span class="summary-label">Total Customers</span>
                            <span class="summary-value">
                                {{ $customers_data['total_customers'] ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-label">New Customers</span>
                            <span class="summary-value">
                                {{ $customers_data['new_customers'] ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-label">Repeat Customers</span>
                            <span class="summary-value">
                                {{ $customers_data['repeat_customers'] ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <span class="summary-label">Retention Rate</span>
                            <span class="summary-value">
                                {{
                                    ($customers_data['total_customers'] ?? 0) > 0
                                    ? round(
                                        ($customers_data['repeat_customers']
                                        / $customers_data['total_customers']) * 100
                                    )
                                    : 0
                                }}%
                            </span>
                        </td>

                    </tr>
                </table>
            </div>
        @endif
    @endforeach

    <div style="margin-top: 50px; text-align: center; color: #7A6A63; font-size: 10px;">
        <p>Generated by Salon Muslimah Dina Management System</p>
        <p>{{ now()->format('d M Y') }}</p>
    </div>
</body>
</html>