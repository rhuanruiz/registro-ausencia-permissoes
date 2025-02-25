<script>
export default{
    name: 'ModalEditarPermissao',
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        setVisible: {
            type: Function,
            default: () => {}
        },
        permissaoSelecionada: {
        type: Object,
        default: () => {},
        },
    },
    data: () => ({
    loading: false,
    relatorio: null,
    nota: null,
    permissaoName: null,
    erros: [],
    permissao_input: ''
  }),
    methods: {
        async EditarPermissao(){
            try{
                if(this.permissao_input != ''){
                    const response =
                        await axios.put('/usuariospermissoes/permissoes/editar-permissao', {
                            nome: this.permissao_input,
                            id: this.permissaoSelecionada.id
                        });
                    if(response.data.message == 'Success'){
                        window.alert('Dados alterados com sucesso!');
                        window.location.reload(true);
                    }
                }else{
                    this.msg_erro = 'Por favor, digite um novo nome para a permissão.';
                }
            }catch(error){
                if(error.response){
                    if(error.response.data.message){
                        this.msg_erro = error.response.data.message;
                    }else{
                        this.msg_erro = "Desculpe, houve uma falha inesperada no sistema."
                    }
                }
            }
        },
        onAlertClose(){
            this.msg_erro = '';
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
                <span>Editar Permissão</span>
                <v-row class="justify-end">
                        <v-icon @click="() => setVisible(false)" >mdi-close-circle</v-icon>
                </v-row>
            </v-card-title>

            <v-card>
                <v-form>
                    <v-col>

                        <!-- input alteração permissão-->
                        <v-text-field
                        v-model="permissao_input"
                        label="Digite o novo nome">
                        </v-text-field>



                        <v-card-actions>
                            <v-btn
                            variant="tonal"
                            elevation="2"
                            dark color="blue darken-1"
                            @click="EditarPermissao()">
                                Editar
                            </v-btn>
                        </v-card-actions>

                    </v-col>
                </v-form>
            </v-card>

        </v-card>
    </v-dialog>
</template>