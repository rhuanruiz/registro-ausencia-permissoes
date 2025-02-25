<?php

namespace App\Http\Controllers\UsuariosPermissoes;

use App\Http\Controllers\Controller;
use App\Repositories\UsuariosPermissoes\PermissoesRepository;
use Illuminate\Http\Request;

class PermissoesController extends Controller
{
    private $permissoesRepository;

    public function __construct(
        PermissoesRepository $permissoesRepository
    )
    {
        $this->permissoesRepository = $permissoesRepository;
    }

    public function ReceberPermissoes()
    {
        $permissoes = $this->permissoesRepository->ReceberPermissoes();

        return view('UsuariosPermissoes/ListarPermissoes', [
            'title' => 'Permissões',
            'permissoes' => $permissoes
        ]);
    }

    public function CriarPermissao(Request $request)
    {
        $permissao = $this->permissoesRepository->BuscarPorNome($request['nome']);

        if ($permissao->isEmpty()) {
            $novaPermissao = $this->permissoesRepository->CriarPermissao($request['nome']);
            if ($novaPermissao) {
                return response()->json([
                    'message' => 'Success',
                    'nova permissao' => $novaPermissao
                ], 200);
            }
        } else {
            return response()->json([
                'message' => 'Esta permissão já existe.'
            ], 400);
        }
    }

    public function EditarPermissao(Request $request)
    {
        $permissao = $this->permissoesRepository->BuscarPorId($request['id']);
        if ($permissao->isEmpty()) {
            return response()->json([
                'message' => 'Esta permissão não existe.'
            ], 400);
        }

        $novaPermissao = $this->permissoesRepository->EditarPermissao($request['nome'], $request['id']);
        if ($novaPermissao) {
            return response()->json([
                'message' => 'Success'
            ], 200);
        }
    }

    public function DeletarPermissao($id)
    {
        $permissao = $this->permissoesRepository->BuscarPorId($id);
        if ($permissao->isEmpty()) {
            return response()->json([
                'message' => 'Esta permissão não existe.'
            ], 400);
        }

        $permissaoDeletada = $this->permissoesRepository->DeletarPermissao($id);
        if ($permissaoDeletada) {
            return response()->json([
                'message' => 'Success'
            ], 200);
        }
    }

    public function ReceberRoles($id)
    {
        $permissaoId = $this->permissoesRepository->BuscarPorId($id);
        if ($permissaoId->isEmpty()) {
            return response()->json([
                'message' => 'Esta permissão não existe.'
            ], 400);
        }

        $roles = $this->permissoesRepository->ReceberRoles($id);
        return response()->json([
            'roles' => $roles,
            'message' => 'Success'
        ], 200);
    }

    public function DefinirRole(Request $request)
    {
        $roles = $this->permissoesRepository->DefinirRole($request['idRole'], $request['idPermissao']);
        return response()->json([
            'roles' => $roles,
            'message' => 'Success'
        ], 200);
    }
}
