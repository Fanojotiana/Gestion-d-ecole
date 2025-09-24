<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Emploi du temps - Semaine {{ $semaine }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }
    </style>
</head>

<body>
    <h2>Emploi du temps - Semaine {{ $semaine }}</h2>

    <table>
        <thead>
            <tr>
                <th>Jour</th>
                <th>Heure début</th>
                <th>Heure fin</th>
                <th>Classe</th>
                <th>Matière</th>
                <th>Enseignant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($emplois as $emploi)
                <tr>
                    <td>{{ ucfirst($emploi->jour) }}</td>
                    <td>{{ $emploi->heure_debut }}</td>
                    <td>{{ $emploi->heure_fin }}</td>
                    <td>{{ optional($emploi->classe)->nom }}</td>
                    <td>{{ optional($emploi->matiere)->nom }}</td>
                    <td>{{ optional($emploi->enseignant)->nom }} {{ optional($emploi->enseignant)->prenom }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
