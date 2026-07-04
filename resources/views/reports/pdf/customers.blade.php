<!DOCTYPE html>
<html>
<head>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table,th,td{
            border:1px solid #000;
        }

        th,td{
            padding:6px;
        }

        h2{
            text-align:center;
        }
    </style>
</head>

<body>

<h2>Customer Report</h2>

<p>
    Generated:
    {{ now()->format('d-m-Y H:i') }}
</p>

<table style="margin-bottom:20px;">
    <tr>
        <td>Total</td>
        <td>{{ $stats['total'] }}</td>
        <td>Todat</td>
        <td>{{ $stats['today'] }}</td>
        <td>This Month</td>
        <td>{{ $stats['this_month'] }}</td>
        <td>This Year</td>
        <td>{{ $stats['this_year'] }}</td>
    </tr>
</table>

<table>

    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Company</th>
        <th>Creator</th>
        <th>Created At</th>
    </tr>
    </thead>

    <tbody>

    @foreach($customers as $customer)

        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ $customer->company }}</td>
            <td>{{ $customer->creator->name  }}</td>
            <td>{{ $customer->created_at}}</td>
        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>