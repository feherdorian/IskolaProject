<?php

namespace App\Http\Controllers;

use App\Models\Diak;
use App\Models\Osztaly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QeriesController extends Controller
{
    public function queryOsztalynevsorok()
    {
        //natív SQL
        $rows = DB::select(
            'SELECT  osztalyNev, nev FROM diaks d
                INNER JOIN osztalies o ON d.osztalyId = o.id
                ORDER BY osztalyNev, nev'
        );

        //DB facade
        // $rows = DB::table('diaks as d')
        //     ->join('osztalies as o', 'd.osztalyId', '=', 'o.id')
        //     ->select('osztalyNev', 'nev')
        //     ->orderBy('osztalyNev', 'asc')
        //     ->orderBy('nev', 'asc')
        //     ->get();

        //Eloquent
        // $rows = Diak::with('osztalies')
        //     // ->orderBy('osztalies.osztalyNev', 'asc')
        //     ->orderBy('nev', 'asc')
        //     ->get();

        //$rows = Osztaly::with('diakok');


        $data = [
            'message' => 'ok',
            'data' => $rows
        ];

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function queryOsztalynevIdvel()
    {
        //natív SQL
        $rows = DB::select(
            'SELECT osztalyNev, id FROM osztalies;'
        );


        $data = [
            'message' => 'ok',
            'data' => $rows
        ];

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function queryOsztalynevsorObj()
    {
        $query =
            "SELECT o.osztalyNev, GROUP_CONCAT(nev ORDER BY nev SEPARATOR ', ') AS nevek  FROM diaks d
            INNER JOIN osztalies o ON d.osztalyId = o.id
            GROUP BY osztalyNev";
        $rows = DB::select($query);

        $rows = array_map(function ($osztaly) {
            $nevek = explode(', ', $osztaly->nevek);
            return [
                'osztalyNev' => $osztaly->osztalyNev,
                'nevek' => $nevek
            ];
        }, $rows);

        $data = [
            'message' => 'ok',
            'data' => $rows
        ];

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function queryOsztalynevsorLimit(int $oldal, int $limit)
    {
        $offset = ($oldal - 1) * $limit;

        $query = 'SELECT  osztalyNev, nev from diaks d
            inner join osztalies o on d.osztalyId = o.id
            order by osztalyNev, nev
            limit ? offset ?
            ';
        $rows = DB::select($query, [$limit, $offset]);
        $data = [
            'message' => 'ok',
            'data' => $rows
        ];

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function queryHanyOldalVan(int $limit)
    {
        $query = 'SELECT  count(*) db from diaks d
            inner join osztalies o on d.osztalyId = o.id
            ';
        $rows = DB::select($query);
        $db = $rows[0]->db;
        $oldalszam = ceil($db / $limit);
        $data = [
            'message' => 'ok',
            'data' => [
                'oldalszam' => $oldalszam
            ]
        ];

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }


    public function queryOsztalytasrsak(string $nev)
    {
        $query = '
            select nev, osztalyId	from diaks
            where osztalyId =
                (
                select osztalyId
                    from diaks
                    where nev = ?
                ) and nev <> ?
            order by nev
            ';
        $rows = DB::select($query, [$nev, $nev]);
        $data = [
            'message' => 'ok',
            'data' => $rows
        ];

        return response()->json($data, options: JSON_UNESCAPED_UNICODE);
    }

    public function queryDiakKeres($nev)
    {
        $query = '
            SELECT d.id, d.osztalyId, d.nev, d.neme, d.szuletett, d.helyseg, d.osztondij, d.atlag, o.osztalyNev
            FROM diaks d
            INNER JOIN osztalies o ON d.osztalyId = o.id
            WHERE d.nev LIKE ?
            ORDER BY d.id';

        $rows = DB::select($query, ['%' . $nev . '%']);

        $data = [
            'message' => 'ok',
            'data' => $rows,
            'query' => $query
        ];

        return response()->json($data, JSON_UNESCAPED_UNICODE);
    }

    public function queryDiakKeresEmpty()
    {
        $query = '
            SELECT d.id, d.osztalyId, d.nev, d.neme, d.szuletett, d.helyseg, d.osztondij, d.atlag, o.osztalyNev
            FROM diaks d
            INNER JOIN osztalies o ON d.osztalyId = o.id
            ORDER BY d.id';

        $rows = DB::select($query);

        $data = [
            'message' => 'ok',
            'data' => $rows,
            'query' => $query
        ];

        return response()->json($data, JSON_UNESCAPED_UNICODE);
    }

    public function diakSportjai($id)
    {
        // Használj idézőjeleket a SQL lekérdezéshez
        $query = '
        SELECT GROUP_CONCAT(ss.sportNev SEPARATOR ", ")  as valami
        FROM sportolas s
        INNER JOIN diaks d ON s.diakokId = d.id
        INNER JOIN sports ss ON ss.id = s.sportokId
        WHERE d.id = ?';
    
        // A paraméterek átadása tömbben
        $rows = DB::select($query, [$id]);
    
        // Eredmény és válasz
        $data = [
            'message' => 'ok',
            'data' => $rows,
            'query' => $query
        ];
    
        return response()->json($data, JSON_UNESCAPED_UNICODE);
    }
}