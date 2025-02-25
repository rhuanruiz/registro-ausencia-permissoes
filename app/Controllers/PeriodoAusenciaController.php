<?php

namespace App\Http\Controllers\meus_dados;

use App\Http\Controllers\Controller;
use App\Models\PeriodoAusencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriodoAusenciaController extends Controller
{
    public function ReceberPeriodosAusencia(Request $request)
    {
        $user = Auth::user();

        $periodos_ausencia = DB::table('periodo_ausencia')
                        ->where('user_id', $user->id)
                        ->orderBy('data_inicio', 'desc')
                        ->get();

        return view('meusDados/periodoAusencia', [
            'title' => 'Informar Período de Ausência',
            'periodos_ausencia' => $periodos_ausencia
        ]);
    }

    public function CriarPeriodoAusencia(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'justificativa_ausencia' => 'required|in:Férias,Afastamento Capacitação,Afastamento Outro Autorizado,Licença Médica,Licença Maternidade,Licença Paternidade,Licença Interesse Particular,Cessão Para Outra Instituição',
        ]);

        $periodoAusencia = PeriodoAusencia::create([
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'justificativa_ausencia' => $request->input('justificativa_ausencia'),
            'user_id' => $user->id,
            'ultima_alteracao' => $user->id
        ]);

        if ($periodoAusencia) {
            return response()->json([
                'message' => 'Success',
                'periodo_ausencia' => $periodoAusencia
            ], 200);
        }
    }

    public function EditarPeriodoAusencia(Request $request, String $id)
    {
        $user = Auth::user();

        $periodoAusencia = PeriodoAusencia::find($id);
        $data = date('Y-m-d', time());

        if (!$periodoAusencia) {
            return response()->json([
                'message' => 'Este período ausência não existe.'
            ], 400);
        } else if ($data >= $periodoAusencia->data_inicio) {
            return response()->json([
                'message' => 'Período ausência em andamento.'
            ], 403);
        } else {
            $request->validate([
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after_or_equal:data_inicio',
                'justificativa_ausencia' => 'required|in:Férias,Afastamento Capacitação,Afastamento Outro Autorizado,Licença Médica,Licença Maternidade,Licença Paternidade,Licença Interesse Particular,Cessão Para Outra Instituição',
            ]);

            $periodoAusencia->update([
                'data_inicio'=> $request['data_inicio'],
                'data_fim' => $request['data_fim'],
                'justificativa_ausencia' => $request['justificativa_ausencia'],
                'ultima_alteracao' => $user->id
            ]);

            return response()->json([
                'message' => 'Success',
                'periodo_ausencia' => $periodoAusencia
            ], 200);
        }
    }

    public function ExcluirPeriodoAusencia(Request $request, $id)
    {
        $user = Auth::user();
        $periodoAusencia = PeriodoAusencia::find($id);
        $data = date('Y-m-d', time());

        if (!$periodoAusencia) {
            return response()->json([
                'message' => 'Este período ausência não existe.'
            ], 400);
        } else if ($data >= $periodoAusencia->data_inicio) {
            return response()->json([
                'message' => 'Período ausência em andamento.'
            ], 403);
        } else if ($data >= $periodoAusencia->data_fim) {
            return response()->json([
                'message' => 'Período ausência encerrado.'
            ], 403);
        } else {
            $periodoAusencia->delete();
            return response()->json([
                'message' => 'Success'
            ], 200);
        }
    }
}
