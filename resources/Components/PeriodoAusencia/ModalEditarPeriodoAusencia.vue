<script>
export default{
    name: 'ModalEditarPeriodoAusencia',
    data(){
        return{
            menuDataInicio: false,
            menuDataFim: false,
            msg_erro: '',
            msg_sucesso: '',
            justificativasAusencia: [
                'Férias',
                'Afastamento Capacitação',
                'Afastamento Outro Autorizado',
                'Licença Médica',
                'Licença Maternidade',
                'Licença Paternidade',
                'Licença Interesse Particular',
                'Cessão Para Outra Instituição'
            ],
            periodo: {
                data_inicio: this.periodoSelecionado.data_inicio,
                data_fim: this.periodoSelecionado.data_fim,
                justificativa_ausencia: this.periodoSelecionado.justificativa_ausencia
            }
        }
    },
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        setVisible: {
            type: Function,
            default: () => {}
        },
        periodoSelecionado: {
            type: Object,
            default: () => {}
        }
    },
    methods: {
        async editarPeriodo(){
            try{
                if(
                    this.periodo.data_inicio != '' &&
                    this.periodo.data_fim != '' &&
                    this.periodo.justificativa_ausencia != ''
                ) {
                    if(this.periodo.data_inicio > this.periodo.data_fim) {
                        this.msg_erro = 'A data de término deve ser posterior a data de início.';
                    } else {
                        const dados = {
                            data_inicio: this.periodo.data_inicio,
                            data_fim: this.periodo.data_fim,
                            justificativa_ausencia: this.periodo.justificativa_ausencia
                        };

                        const response = await axios.put(`/meusdados/editarPeriodoAusencia/${this.periodoSelecionado.id}`, dados);

                        if (response.data.message == 'Success') {
                            this.msg_sucesso = 'Período Ausência alterado com sucesso!'
                            setTimeout(() => {
                                this.msg_sucesso = '';
                                window.location.reload(true);
                            }, 2000);
                        }
                    }
                } else {
                    this.msg_erro = "Todos os campos devem ser preenchidos.";
                }
            }catch(error) {
                if (error.response) {
                    if (error.response.status == 403) {
                        this.msg_erro = "Não é possível editar um período ausência em andamento/encerrado."
                    } else {
                        this.msg_erro = "Desculpe, houve uma falha inesperada no sistema."
                    }
                }
            }
        },
        onAlertClose() {
            this.msg_erro = '';
            this.msg_sucesso = '';
        }
    }
}
</script>

<template>
    <v-dialog v-model="visible" persistent max-width="500px">
        <v-card>
            <v-card-title
            style="
            font-weight: bold,
            display: flex,
            flex-direction: row,
            justify-content: space-between"
            >
                <span>Editar Período de Ausência</span>
                <v-row class="justify-end">
                        <v-icon @click="() => setVisible(false)" >mdi-close-circle</v-icon>
                </v-row>
            </v-card-title>
            <v-card>
                <v-form>
                    <v-col>
                        <v-menu v-model="menuDataInicio" :close-on-content-click="true" transition="scale-transition" min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="periodo.data_inicio"
                                    label="Data Inicial"
                                    type="date"
                                    prepend-icon="mdi-calendar"
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                        </v-menu>
                    </v-col>
                    <v-col>
                        <v-menu v-model="menuDataFim" :close-on-content-click="true" transition="scale-transition" min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="periodo.data_fim"
                                    label="Data Final"
                                    type="date"
                                    prepend-icon="mdi-calendar"
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                        </v-menu>
                    </v-col>
                    <v-col>
                        <v-select
                            v-model="periodo.justificativa_ausencia"
                            :items="justificativasAusencia"
                            label="Selecione a Justificativa de Ausência:"
                            label-position="on-top"
                            prepend-icon="mdi-briefcase-off"
                        ></v-select>
                    </v-col>
                    <v-card v-if="msg_erro != ''">
                        <v-alert
                            dismissible
                            elevation="2"
                            type="error"
                            @input="onAlertClose()"
                        >{{ this.msg_erro }}
                        </v-alert>
                    </v-card>
                    <v-card v-if="msg_sucesso !== ''">
                        <v-alert
                            elevation="2"
                            type="success"
                            @input="onAlertClose()"
                        >{{ this.msg_sucesso }}
                        </v-alert>
                    </v-card>
                    <v-card-actions class="d-flex justify-center">
                        <v-btn variant="tonal" color="red" dark @click="() => setVisible(false)"> Cancelar </v-btn>
                        <v-btn variant="tonal" color="blue darken-1" dark @click="editarPeriodo()"> Enviar </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-card>
    </v-dialog>
</template>