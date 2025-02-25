<script>
import MenuItem from '../../MeusDados/PeriodoAusencia/MenuItem.vue';
import ModalCriarPeriodo from './TecnicoCriarPeriodoAusencia.vue';
import ModalEditarPeriodo from './TecnicoEditarPeriodoAusencia.vue';
import ModalExcluirPeriodo from './TecnicoExcluirPeriodoAusencia.vue';

export default {
    name: 'ListarPeriodosAusencia',
    props: {
        title: {
            type: String,
            default: ''
        },
        periodos_ausencia: {
            type: Array,
            default: () => []
        },
        nome_usuarios: {
            type: Array,
            default: () => []
        },
        id_usuarios: {
            type: Array,
            default: () => []
        },
        nome_professores: {
            type: Array,
            default: () => []
        },
        nome_tecnicos: {
            type: Array,
            default: () => []
        }
    },
    components: {
        item: MenuItem,
        'modal-criar-periodo-ausencia': ModalCriarPeriodo,
        'modal-editar-periodo-ausencia': ModalEditarPeriodo,
        'modal-excluir-periodo-ausencia': ModalExcluirPeriodo
    },
    data: () => ({
        max_pagina: 1,
        pagina: 1,
        itens_por_pagina: 10,
        contador_de_filtro: 0,
        num_max_show: 10,
        dialog_criar_periodo_ausencia: false,
        dialog_editar_periodo_ausencia: false,
        dialog_excluir_periodo_ausencia: false,
        periodoSelecionado: null,
        filter_name: '',
        filter_justificativa: '',
        filter_ultima_alteracao_name: ''
    }),
    methods: {
        async CriarPeriodoAusencia() {
            this.dialog_criar_periodo_ausencia = true;
        },
        async EditarPeriodoAusencia(periodo) {
            this.periodoSelecionado = periodo;
            this.dialog_editar_periodo_ausencia = true;
        },
        async ExcluirPeriodoAusencia(periodo) {
            this.periodoSelecionado = periodo;
            this.dialog_excluir_periodo_ausencia = true;
        },
        formatarData(data) {
            const options = {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
                timeZone: 'UTC',
            };
            return new Date(data).toLocaleDateString('pt-BR', options);
        }
    },
    computed: {
        filteredRows() {
            let contador_de_filtro = 0;
            return this.periodos_ausencia.filter((periodos) => {
                if (this.filter == '') {
                    return periodos;
                }
                const filtered = periodos;
                const name = filtered.name.toLowerCase();
                const justificativa = filtered.justificativa_ausencia.toLowerCase();
                const ultima_alteracao_name = filtered.ultima_alteracao_name.toLowerCase();

                const searchTerm_name = this.filter_name.toLowerCase();
                const searchTerm_justificativa = this.filter_justificativa.toLowerCase();
                const sarchTerm_ultima_alteracao_name = this.filter_ultima_alteracao_name.toLowerCase();

                if (name.includes(searchTerm_name) &&
                    justificativa.includes(searchTerm_justificativa) &&
                    ultima_alteracao_name.includes(sarchTerm_ultima_alteracao_name)
                ) {
                    contador_de_filtro++;
                    periodos.contador_de_filtro = contador_de_filtro;
                    this.pagina = 1;
                    this.max_pagina = Math.ceil(contador_de_filtro / this.num_max_show);
                    return 1;
                }
                periodos.contador_de_filtro = -1;
                return 0;
            });
        }
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
            @click="CriarPeriodoAusencia()">
                Novo Período
        </v-btn>
        <modal-criar-periodo-ausencia
            :visible="dialog_criar_periodo_ausencia"
            :setVisible="(value) => (dialog_criar_periodo_ausencia = value)"
            :nome_professores = "nome_professores"
            :nome_tecnicos = "nome_tecnicos"
        />
        <v-card-text>
            <v-simple-table class="pa-2 ma-2">
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="text-center">Professor/Técnico</th>
                            <th class="text-center">Data Inicial</th>
                            <th class="text-center">Data Final</th>
                            <th class="text-center">Justificativa Ausência</th>
                            <th class="text-center">Última Alteração</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <modal-editar-periodo-ausencia
                            v-if="periodoSelecionado !=  null"
                            :visible = "dialog_editar_periodo_ausencia"
                            :setVisible = "(value) => (dialog_editar_periodo_ausencia = value)"
                            :periodoSelecionado = "periodoSelecionado"
                            :nome_usuarios = "nome_usuarios"
                            :nome_professores = "nome_professores"
                            :nome_tecnicos = "nome_tecnicos"
                        />
                        <modal-excluir-periodo-ausencia
                            :visible = "dialog_excluir_periodo_ausencia"
                            :setVisible = "(value) => (dialog_excluir_periodo_ausencia = value)"
                            :periodoSelecionado = "periodoSelecionado"
                            :nome_usuarios = "nome_usuarios"
                            v-if="periodoSelecionado !=  null"
                        />
                        <tr>
                            <td>
                                <v-text-field
                                    class="pesquisar"
                                    v-model="filter_name"
                                    placeholder="Procurar professor/técnico"
                                    hide-details
                                    filled
                                    rounded
                                    dense
                                ></v-text-field>
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                                <v-text-field
                                    class="pesquisar"
                                    v-model="filter_justificativa"
                                    placeholder="Procurar justificativa"
                                    hide-details
                                    filled
                                    rounded
                                    dense
                                ></v-text-field>
                            </td>
                            <td>
                                <v-text-field
                                    class="pesquisar"
                                    v-model="filter_ultima_alteracao_name"
                                    placeholder="Procurar professor/técnico"
                                    hide-details
                                    filled
                                    rounded
                                    dense
                                ></v-text-field>
                            </td>
                            <td></td>
                        </tr>
                        <tr v-if="!periodos_ausencia ||
                            periodos_ausencia.length === 0"
                            ><td colspan="4" class="text-center">Ainda não há períodos informados.</td>
                        </tr>
                        <tr v-else class="text-center"
                            v-for="periodo in filteredRows" :key="periodo.id">
                            <template
                                v-if="
                                    periodo.contador_de_filtro > (pagina - 1) * num_max_show &&
                                    periodo.contador_de_filtro <= pagina * num_max_show
                            ">
                                <td>{{ periodo.name }}</td>
                                <td>{{ formatarData(periodo.data_inicio) }}</td>
                                <td>{{ formatarData(periodo.data_fim) }}</td>
                                <td>{{ periodo.justificativa_ausencia }}</td>
                                <td>{{ periodo.ultima_alteracao_name }}</td>
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
                                                :text="'Editar'"
                                                :action="() => (EditarPeriodoAusencia(periodo))"
                                            />
                                            <item
                                                icon="delete"
                                                :text="'Excluir'"
                                                :action="() => (ExcluirPeriodoAusencia(periodo))"
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

<style scoped>
    .pesquisar {
        width: 100%;
    }
    .pesquisar >>> input {
        text-align: center
    }
</style>

