<?php

namespace App\Repositories\UsuariosPermissoes;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;

class PermissoesRepository
{
    public function ReceberPermissoes()
    {
        return Permission::all();
    }

    public function ReceberRoles($id)
    {
        $permissao = Permission::find($id);
        return $permissao->roles;
    }

    public function DefinirRole($idRole, $idPermissao)
    {
        try {
            DB::beginTransaction();
            $permissao = Permission::find($idPermissao);
            $permissao->roles()->attach($idRole);
            DB::commit();
            return $permissao->roles;
        }
        catch (Throwable $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
    }

    public function CriarPermissao($nome)
    {
        try {
            DB::beginTransaction();
            $novaPermissao = new Permission;
            $novaPermissao->name = $nome;
            $novaPermissao->save();
            DB::commit();
            return $novaPermissao;
        }
        catch (Throwable $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
    }

    public function EditarPermissao($nome, $id)
    {
        try {
            DB::beginTransaction();
            $novaPermissao = Permission::find($id);
            $novaPermissao->update([
                'name'=> $nome
            ]);
            DB::commit();
            return $novaPermissao;
        }
        catch (Throwable $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
    }

    public function DeletarPermissao($id)
    {
        try {
            DB::beginTransaction();
            $permissao = Permission::find($id);
            $permissao->delete();
            DB::commit();
            return "OK";
        }
        catch (Throwable $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
    }

    public function BuscarPorId($id)
    {
        return Permission::where('id', $id)->get();
    }

    public function BuscarPorNome($nome)
    {
        return Permission::where('name', $nome)->get();
    }
}
