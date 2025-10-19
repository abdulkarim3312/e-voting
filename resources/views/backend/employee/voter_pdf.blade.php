<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; }
        h3,h4,span { margin:0; }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        th,td { border:1px solid #000; padding:4px; text-align:center; vertical-align:middle; }
        th { background:#eee; }
        img { width:50px; height:50px; object-fit:cover; border:1px solid #ccc; }
    </style>
</head>
<body>
    <h3>বাংলাদেশ ইলেকশন কমিশন অফিসার্স এসোসিয়েশন</h3>
    <h4>খসড়া ভোটার তালিকা (জ্যেষ্ঠতার ভিত্তিতে নয়)</h4>
    <span>নির্বাচন কমিশন সচিবালয়</span>

    <table>
        <thead>
            <tr>
                <th>নং</th>
                <th>সদস্য নং</th>
                <th>নাম ও এনআইডি</th>
                <th>জেলা</th>
                <th>পদবী</th>
                <th>কর্মস্থল</th>
                <th>মোবাইল</th>
                <th>ছবি</th>
            </tr>
        </thead>
        <tbody>
            @foreach($voters as $i => $vot)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $i+1 }}</td>
                <td>{{ $vot->name }}<br>{{ $vot->nid }}</td>
                <td>{{ $vot->district_name }}</td>
                <td>{{ $vot->designation_name }}</td>
                <td>{{ $vot->working_place }}</td>
                <td>{{ $vot->phone }}</td>
                <td>
                    @if($vot->photo && file_exists(public_path($vot->photo)))
                        <img src="{{ public_path($vot->photo) }}" alt="">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
