<template>
    <v-list-item link @click="action">
      <v-icon style="margin-right: 10px">mdi-{{icon}}</v-icon>
      <v-list-item-title>{{text}}</v-list-item-title>
    </v-list-item>
  </template>
  <script>
  /**
   * Componente que centraliza itens de menu dropdown
   * @displayName Item de menu
   */
  export default {
    name: 'MenuItem',
    props: {
      /**
       * Item para aparecer no item
       */
      icon: {
        type: String,
        default: '',
      },
      /**
       * Texto para exibir no item
       */
      text: {
        type: String,
        default: '',
      },
      /**
       * Ação do item
       */
      action: {
        type: Function,
        default: () => {},
      },
    },
  };
  </script>
  