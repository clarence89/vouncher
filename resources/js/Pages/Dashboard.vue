<template>
  <app-layout>
    <template v-slot="sideBarVal">
    {{sideBarVal}}
      <dashboard-c v-if="sideBarVal.profiled == 1"></dashboard-c>
    </template>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import DashboardC from "@/MyComponents/DashboardComponent";
export default {
  components: {
    AppLayout,
    DashboardC

  },
  props: ["profiled"],
};
</script>
