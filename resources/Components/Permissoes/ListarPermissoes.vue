<script>
import MenuItem from '../MenuItem.vue';
import ModalCriarPermissao from './ModalCriarPermissao.vue';
import ModalEditarPermissao from './ModalEditarPermissao.vue';
import ModalDeletarPermissao from './ModalDeletarPermissao.vue';
import ModalVisualizarRoles from './ModalVisualizarRoles.vue';
import ModalDefinirRole from './ModalDefinirRole.vue';

export default {
    name: 'ListarPermissoes',
    props: {
        title: {
            type: String,
            default: '',
        },
        permissoes: {
            type: Array,
            default: () => [],
        },
        roles: {
            type: Array,
            default: () => []
        }
    },
    components: {
        item: MenuItem,
        'modal-criar-permissao': ModalCriarPermissao,
        'modal-editar-permissao': ModalEditarPermissao,
        'modal-deletar-permissao': ModalDeletarPermissao,
        'modal-visualizar-roles': ModalVisualizarRoles,
        'modal-definir-role': ModalDefinirRole
    },
    data: () => ({
        max_pagina: 1,
        pagina: 1,
        itens_por_pagina: 10,
        contador_de_filtro: 0,
        num_max_show: 20,
        dialog_criar_permissao: false,
        dialog_editar_permissao: false,
        dialog_deletar_permissao: false,
        dialog_visualizar_roles: false,
        dialog_definir_role: false,
        permissaoSelecionada: null,
        roleSelecionada: null,
        filter_name: ''
    }),
    methods: {
        async CriarPermissao() {
            this.dialog_criar_permissao = true;
        },
        async EditarPermissao(permissao) {
            this.permissaoSelecionada = permissao;
            this.dialog_editar_permissao = true;
        },
        async DeletarPermissao(permissao) {
            this.permissaoSelecionada = permissao;
            this.dialog_deletar_permissao = true;
        },
        async VisualizarRoles(permissao) {
            this.permissaoSelecionada = permissao;
            this.dialog_visualizar_roles = true;
        },
        async DefinirRole(permissao) {
            this.permissaoSelecionada = permissao;
            this.dialog_definir_role = true;
        }
    },
    computed: {
        filteredRows() {
            let contador_de_filtro = 0;
            return this.permissoes.filter((permissoes) => {
                if (this.filter == '') {
                    return permissoes;
                }

                const filtered = permissoes;

                const name = filtered.name.toLowerCase();
                const searchTerm_name = this.filter_name.toLowerCase();
                if (name.includes(searchTerm_name)) {
                    contador_de_filtro++;
                    permissoes.contador_de_filtro = contador_de_filtro;
                    this.pagina = 1;
                    this.max_pagina = Math.ceil(contador_de_filtro / this.num_max_show);

                    return 1;
                }
                permissoes.contador_de_filtro = -1;
                return 0;
            });
        },
    }
}
</script>

<template>
    <v-app>
        <header-vue :name="title" />
        <v-card style="margin: 0 16px">
        <v-btn class="white--text"
            style="margin: 16px"
            elevation="2"
            color=#3490dc
            @click="CriarPermissao()">
                Adicionar Permissão
        </v-btn>
        <modal-criar-permissao
            :visible="dialog_criar_permissao"
            :setVisible="(value) => (dialog_criar_permissao = value)"
        />
        <v-card-text>
            <div class="pesquisar">
                <v-text-field
                    v-model="filter_name"
                    placeholder="Procurar Permissão"
                    hide-details
                    filled
                    rounded
                    dense
                ></v-text-field>

            </div>
            <v-simple-table class="pa-2 ma-2">
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th>Permissões</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <modal-editar-permissao
                            :visible="dialog_editar_permissao"
                            :setVisible="(value) => (dialog_editar_permissao = value)"
                            :permissaoSelecionada="permissaoSelecionada"
                            v-if="permissaoSelecionada !=  null"
                        />
                        <modal-deletar-permissao
                            :visible="dialog_deletar_permissao"
                            :setVisible="(value) => (dialog_deletar_permissao = value)"
                            :permissaoSelecionada="permissaoSelecionada"
                            v-if="permissaoSelecionada !=  null"
                        />
                        <modal-visualizar-roles
                            :visible="dialog_visualizar_roles"
                            :setVisible="(value) => (dialog_visualizar_roles = value)"
                            :permissaoSelecionada="permissaoSelecionada"
                            v-if="permissaoSelecionada !=  null"
                        />
                        <modal-definir-role
                            :visible="dialog_definir_role"
                            :setVisible="(value) => (dialog_definir_role = value)"
                            :permissaoSelecionada="permissaoSelecionada"
                            :roles="roles"
                            v-if="permissaoSelecionada !=  null"
                        />
                        <tr
                            v-for="permissao in filteredRows"
                            :key="permissao.id"
                            ><template
                                v-if="
                                    permissao.contador_de_filtro > (pagina - 1) * num_max_show &&
                                    permissao.contador_de_filtro <= pagina * num_max_show
                            "><td>{{ permissao.name }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <v-menu  offset-x left>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn light icon v-bind="attrs" v-on="on">
                                            <v-icon>mdi-dots-vertical</v-icon>
                                        </v-btn>
                                    </template>
                                    <v-list>
                                        <item
                                            icon="pencil"
                                            :text="'Editar Permissão'"
                                            :action="() => (EditarPermissao(permissao))"
                                        />
                                        <item
                                            icon="delete"
                                            :text="'Deletar Permissão'"
                                            :action="() => (DeletarPermissao(permissao))"
                                        />
                                        <item
                                            icon="eye"
                                            :text="'Visualizar Roles'"
                                            :action="() => (VisualizarRoles(permissao))"
                                        />
                                        <item
                                            icon="fa mdi mdi-mail"
                                            :text="'Definir Role'"
                                            :action="() => (DefinirRole(permissao))"
                                        />
                                    </v-list>
                                </v-menu>
                            </td>
                            </template>
                         </tr>
                    </tbody>
                </template>
            </v-simple-table>
            <div class="text-center">
                <v-pagination
                    v-model="pagina"
                    :length="max_pagina"
                    circle
                    prev-icon="mdi-menu-left"
                    next-icon="mdi-menu-right"
                ></v-pagination>
            </div>
        </v-card-text>
        </v-card>
    </v-app>
</template>

<style>
.pesquisar {
    width: 30%;
}
</style>
