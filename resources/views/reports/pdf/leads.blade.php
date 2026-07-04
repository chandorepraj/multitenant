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

<h2>Lead Report</h2>

<p>
    Generated:
    {{ now()->format('d-m-Y H:i') }}
</p>

<table style="margin-bottom:20px;">
    <tr>
        <td>Total</td>
        <td>{{ $stats['Total'] }}</td>
        <td>New</td>
        <td>{{ $stats['New'] }}</td>
        <td>Qualified</td>
        <td>{{ $stats['Qualified'] }}</td>
        <td>Contacted</td>
        <td>{{ $stats['Contacted'] }}</td>
    </tr>

    <tr>
        <td>Converted</td>
        <td>{{ $stats['Converted'] }}</td>
         <td>Won</td>
        <td>{{ $stats['Won'] }}</td>
        <td>Lost</td>
        <td>{{ $stats['Lost'] }}</td>
        <td></td>
        <td></td>
    </tr>
</table>

<table>

    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Source</th>
        <th>Status</th>
        <th>Creator</th>
    </tr>
    </thead>

    <tbody>

    @foreach($leads as $lead)

        <tr>
            <td>{{ $lead->name }}</td>
            <td>{{ $lead->email }}</td>
            <td>{{ $lead->phone }}</td>
            <td>{{ $lead->source }}</td>
            <td>{{ $lead->status }}</td>
            <td>{{ $lead->creator->name }}</td>
        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>