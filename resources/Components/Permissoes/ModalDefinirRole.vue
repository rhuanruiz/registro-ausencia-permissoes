<script>
export default{
    name: 'ModalDefinirRole',
    data(){
        return{
            role: '',
            msg_erro: '',
            msg_sucesso: ''
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
        roles: {
            type: Array,
            default: () => []
        },
        permissaoSelecionada: {
            type: Object,
            default: () => {}
        }

    },
    methods: {
        async definirRole(){
            try{
                if( 
                    this.role != '' 
                ) {
                    const dados = {
                        role: this.role,
                        idPermissao: this.permissaoSelecionada.id
                    };
                    
                    const response = await axios.post('/usuariospermissoes/permissoes/definir-role', dados);

                    if (response.data.message == 'Success') {
                        this.msg_sucesso = 'Role definida com sucesso!'
                        setTimeout(() => {
                            this.msg_sucesso = '';
                            window.location.reload(true);
                        }, 2000);
                    }
                } else {
                    this.msg_erro = "A role deve ser selecionada.";
                }
            }catch(error) {
                if (error.response) {
                    this.msg_erro = "Desculpe, houve uma falha inesperada no sistema."
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
            font-weight: bold;
            display: flex;
            flex-direction: row;
            justify-content: space-between;"
            >
                <span>Definir Role</span>
                <v-icon @click="() => setVisible(false)" >mdi-close-circle</v-icon>
            </v-card-title>
            <v-card>
                <v-form>
                    <v-col>
                        <v-select
                            v-model="role"
                            :items="roles" 
                            label="Selecione uma role:"
                            label-position="on-top"
                            prepend-icon="fa mdi mdi-mail"
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
                        <v-btn variant="tonal" color="blue darken-1" dark @click="definirRole()"> Enviar </v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>      
        </v-card> 
    </v-dialog>
</template>