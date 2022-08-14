<template>
  <app-layout>
    <template v-slot="sideBarVal">
      <dashboard-c v-if="sideBarVal.profiled == 1"></dashboard-c>
      <voucher-c v-if="sideBarVal.profiled == 2"></voucher-c>
      <!--   <add-group-c v-if="sideBarVal.profiled == 3"></add-group-c> -->
      <group-c v-if="sideBarVal.profiled == 4"></group-c>
    </template>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import DashboardC from "@/MyComponents/DashboardComponent";
import VoucherC from "@/MyComponents/VoucherComponent";
import GroupC from "@/MyComponents/GroupComponent";
export default {
  components: {
    AppLayout,
    DashboardC,
    VoucherC,
    GroupC,
  },
  props: ["profiled"],
};
</script>
