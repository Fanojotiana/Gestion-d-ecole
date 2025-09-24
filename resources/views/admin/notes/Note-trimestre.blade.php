<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bulletin de notes - {{ $eleve->nom }} {{ $eleve->prenom }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Bulletin de notes - {{ $eleve->nom }} {{ $eleve->prenom }}</h2>
    <h3>Période : {{ $periode }}</h3>

    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th>Note (/20)</th>
                <th>Coefficient</th>
                <th>Total pondéré</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalCoeffs = 0;
                $totalPondere = 0;
            @endphp
            @foreach ($notes as $note)
                @php
                    $totalCoeffs += $note->coefficient;
                    $totalPondere += $note->note * $note->coefficient;
                @endphp
                <tr>
                    <td>{{ $note->matiere->nom }}</td>
                    <td>{{ $note->note }}</td>
                    <td>{{ $note->coefficient }}</td>
                    <td>{{ $note->note * $note->coefficient }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Somme coefficients</th>
                <th>{{ $totalCoeffs }}</th>
            </tr>
            <tr>
                <th colspan="3">Total pondéré</th>
                <th>{{ $totalPondere }}</th>
            </tr>
            <tr>
                <th colspan="3">Moyenne pondérée</th>
                <th>{{ $totalCoeffs ? round($totalPondere / $totalCoeffs, 2) : 0 }}</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>
