<?php

namespace App\Http\Controllers\Corpo_administrativo;

use App\Http\Controllers\Controller;
use App\Models\PeriodoAusencia;
use App\Models\User;
use App\Models\Professor;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistroAusenciaController extends Controller
{

    public function index(Request $request)
    {
        $dep = \config('app.departamento');

        $nome_professores = Professor::where('departamento_codigo', '=', $dep)
                        ->where('tipo', '=', 'E')
                        ->join('users', 'professores.user_id', '=', 'users.id')
                        ->pluck('users.name');
        $nome_tecnicos = Tecnico::where('departamento_codigo', '=', $dep)
                        ->where('tipo', '=', 'EF')
                        ->join('users', 'tecnicos.user_id', '=', 'users.id')
                        ->pluck('users.name');
        $nome_usuarios = $nome_professores->concat($nome_tecnicos);

        $idProfessores = Professor::where('departamento_codigo', '=', $dep)
                        ->where('tipo', '=', 'E')
                        ->join('users', 'professores.user_id', '=', 'users.id')
                        ->pluck('users.id');
        $idTecnicos = Tecnico::where('departamento_codigo', '=', $dep)
                        ->where('tipo', '=', 'EF')
                        ->join('users', 'tecnicos.user_id', '=', 'users.id')
                        ->pluck('users.id');
        $id_usuarios = $idProfessores->concat($idTecnicos);

        $periodos_ausencia = DB::table('periodo_ausencia')
                        ->whereIn('user_id', $id_usuarios)
                        ->join('users as u1', 'periodo_ausencia.user_id', '=', 'u1.id')
                        ->join('users as u2', 'periodo_ausencia.ultima_alteracao', '=', 'u2.id')
                        ->select('periodo_ausencia.*', 'u1.name as name', 'u2.name as ultima_alteracao_name')
                        ->orderBy('data_inicio', 'desc')
                        ->get();

        return view('corpo_administrativo/registrosausencia', [
            'title' => 'Registros de Ausência',
            'periodos_ausencia' => $periodos_ausencia,
            'nome_usuarios' => $nome_usuarios,
            'id_usuarios' => $id_usuarios,
            'nome_professores' => $nome_professores,
            'nome_tecnicos' => $nome_tecnicos
        ]);
    }

    public function CriarPeriodoAusencia(Request $request)
    {
        $user_atual = Auth::user();
        $user = User::where('name', $request->nome_usuario)->get();

        if (!$user) {
            return response()->json([
                'message' => 'Esse usuário não existe.'
            ], 404);
        }

        $request->validate([
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'justificativa_ausencia' => 'required|in:Férias,Afastamento Capacitação,Afastamento Outro Autorizado,Licença Médica,Licença Maternidade,Licença Paternidade,Licença Interesse Particular,Cessão Para Outra Instituição'
        ]);

        $periodoAusencia = PeriodoAusencia::create([
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
            'justificativa_ausencia' => $request->input('justificativa_ausencia'),
            'user_id' => $user[0]->id,
            'ultima_alteracao' => $user_atual->id
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
        $data = date('Y-m-d', time());

        $user_atual = Auth::user();
        $user = User::where('name', $request->nome_usuario)->get();
        if (!$user) {
            return response()->json([
                'message' => 'Esse usuário não existe.'
            ], 404);
        }

        $periodoAusencia = PeriodoAusencia::find($id);
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
                'justificativa_ausencia' => 'required|in:Férias,Afastamento Capacitação,Afastamento Outro Autorizado,Licença Médica,Licença Maternidade,Licença Paternidade,Licença Interesse Particular,Cessão Para Outra Instituição'
            ]);

            $periodoAusencia->update([
                'data_inicio'=> $request['data_inicio'],
                'data_fim' => $request['data_fim'],
                'justificativa_ausencia' => $request['justificativa_ausencia'],
                'user_id' => $user[0]->id,
                'ultima_alteracao' => $user_atual->id
            ]);
            return response()->json([
                'message' => 'Success',
                'periodo_ausencia' => $periodoAusencia
            ], 200);
        }
    }

    public function ExcluirPeriodoAusencia(Request $request, $id)
    {
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
