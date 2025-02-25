<script>
export default{
    name: 'ModalCriarPermissão',
    data(){
        return{
            permission_input: '',
            msg_erro: ''
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
        }
    },
    methods: {
        async criarPermissao(){
            try{
                if(this.permission_input!= ''){
                    const response = await axios.post('/usuariospermissoes/permissoes/criar-permissao', {nome: this.permission_input});
                    console.log(response.data.message)

                    if(response.data.message == 'Success'){
                        window.alert('Permissão criada com sucesso!');
                        window.location.reload(true);
                    }
                }else{
                    this.msg_erro = "Por favor, digite um nome para a nova permissão.";
                }
            }
            catch(error){
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
            font-weight: bold;
            display: flex;
            flex-direction: row;
            justify-content: space-between;"
            >
                <span>Adicionar Permissão</span>
                <v-icon @click="() => setVisible(false)" >mdi-close-circle</v-icon>
            </v-card-title>
            <v-card>
                <v-form>
                    <v-col>
                        <!-- input digita permissão -->
                        <v-text-field
                        v-model="permission_input"
                        label="Nova Permissão"
                        @keydown.enter="(event) => {
                            event.preventDefault()
                            criarPermissao()
                        }">
                        </v-text-field>

                        <!--casos de erro-->
                        <v-card v-if="msg_erro != ''">
                            <v-alert
                            dismissible
                            elevation="2"
                            type="error"
                            @input="onAlertClose()"
                            >
                            {{ this.msg_erro }}
                            </v-alert>
                        </v-card>


                        <v-card-actions>
                            <v-btn
                            variant="tonal"
                            color="blue darken-1"
                            dark
                            @click="criarPermissao()">
                                Criar
                            </v-btn>
                        </v-card-actions>
                    </v-col>
                </v-form>
            </v-card>
        </v-card>
    </v-dialog>
</template>
