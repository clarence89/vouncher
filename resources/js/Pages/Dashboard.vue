<template>
  <app-layout>
    <template v-slot="sideBarVal">
      <dashboard-c
        v-if="
          sideBarVal.profiled == 1 &&
          $page.props.role.some(function (il) {
            return il.name === 'super-admin';
          })
        "
      ></dashboard-c>
      <voucher-c
        v-if="
          sideBarVal.profiled == 2 ||
          (!$page.props.role.some(function (il) {
            return il.name === 'super-admin';
          }) &&
            !$page.props.permission.some(function (il) {
              return il.name === 'moderate_group';
            }))
        "
      ></voucher-c>
      <group-c
        v-if="
          sideBarVal.profiled == 4 ||
          (!$page.props.role.some(function (il) {
            return il.name === 'super-admin';
          }) &&
            $page.props.permission.some(function (il) {
              return il.name === 'moderate_group';
            }))
        "
      ></group-c>
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
