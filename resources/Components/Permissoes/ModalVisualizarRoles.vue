<script>

export default {
    name: 'ModalVisualizarRoles',
    data(){
       return{
        verificar: null,
        msgErro: null,
        rolesList: [],
        loading: false
       }
    },
    props: {
        visible: {
            type: Boolean,
            default: false,
        },
        setVisible: {
            type: Function,
            default: () => {},
        },
        permissaoSelecionada: {
            type: Object,
            default: () => {},
        },
        roles: {
            type: Array,
            default: () => [],
        }
    },
    watch: {
        permissaoSelecionada: ['receberRoles','clear']
    },
    created() {
        this.receberRoles();
    },
    computed:{
        verificarMessage(){
            return this.verificar === null ? '' : this.verificar;
        }
    },
    methods: {
        async receberRoles() {
            try {
                this.loading = true;
                const response = await axios.get(`/usuariospermissoes/permissoes/visualizar-roles/${this.permissaoSelecionada.id}?timestamp=${new Date().getTime()}`);
                if(response.data.message == 'Success'){
                    this.rolesList = response.data.roles;
                    this.verificar = response.data.roles.length == 0 ? 'Essa role ainda não possui permissões' : null;
                }else{
                    this.msgErro = 'Ouve um erro na consulta'
                }
            } catch (error) {
                console.error(error);
            }finally{
                this.loading = false;
            }
        },
        clear(){
            this.rolesList =[]
            this.verificar = null
        }
    }
};
</script>

<template>
    <v-dialog v-model="visible" persistent max-width="500px">
        <v-card>
            <v-card-title
                style="
                font-weight: bold;
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                "
            >
                <span></span>
                <span>Roles</span>
                <v-icon @click="() => {setVisible(false)}">mdi-close-circle</v-icon>
            </v-card-title>
            <v-simple-table>
                <tbody>
                    <tr
                        v-for="(role, index) in rolesList"
                        :key="index"

                    >
                        <td>{{ role.name }}</td>
                    </tr>
                    <td v-if="verificar != null" class="verificar">{{ verificar }}</td>
                    <td v-if="loading" class="carregando"> Carregando...</td>
                </tbody>
            </v-simple-table>

        </v-card>
    </v-dialog>
</template>

<style>
.carregando{
    display: flex;
    justify-content: center;
    align-items: center;
}
.verificar{
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
