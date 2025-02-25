<script>
export default {
  name: 'ModalDeletarpermissao',
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
  },
  data: () => ({
    loading: false,
    relatorio: null,
    nota: null,
    permissaoName: null,
    erros: [],
  }),
  methods: {
    async Deletarpermissao() {
      try {
        // eslint-disable-next-line no-undef
        const response =  await axios.delete(`/usuariospermissoes/permissoes/deletar-permissao/${this.permissaoSelecionada.id}`);
        console.log(response.data.message)

        if(response.data.message == 'Success'){
            window.alert('Permissão excluída com sucesso!');
            window.location.reload(true);
        }
        this.setVisible(false);
        window.location.reload(true);
      } catch (error) {
        console.error(error.response);
      }
    },
  },
  components: {

  },
};
</script>

<template>
    <v-dialog v-model="visible" persistent max-width="500px">
        <v-card>
            <v-alert prominent type="info">
                <v-row align="center">
                <v-col class="grow">
                    Você tem certeza que deseja deletar essa permissão?
                </v-col>
                </v-row>
            </v-alert>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="() => setVisible(false)">
                    Cancelar
                </v-btn>

                <v-btn
                    color="blue darken-1"
                    text
                    @click="Deletarpermissao()"
                >
                    Sim
                </v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>