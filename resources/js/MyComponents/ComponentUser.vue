<template>
  <div v-if="users.total">
    <jet-bar-table
      class="table-auto"
      :headers="
        selected == 'users'
          ? ['User Name', 'View User']
          : ['User Name', 'Groups Moderated']
      "
    >
      <tr class="hover:bg-gray-50" v-for="user in users.data" :key="user.id">
        <jet-bar-table-data>
          <p
            v-if="user.permissions_count && user.roles_count"
            class="font-semibold text-black"
          >
            {{ user.name }} (Moderator)
          </p>
          <p v-else class="font-semibold text-black">
            {{ user.name }}
          </p>
        </jet-bar-table-data>
        <jet-bar-table-data
          v-if="selected == 'users'"
          class="items-center justify-center cursor-pointer"
          v-on:click="viewCodes(user.id)"
        >
          <jet-bar-icon
            v-if="
              ($page.props.role.some(function (il) {
                return il.name === 'super-admin';
              }) &&
                selected == 'users') ||
              ($page.props.permission.some(function (il) {
                return il.name === 'moderate_group';
              }) &&
                selected == 'users')
            "
            type="eye"
            fill
        /></jet-bar-table-data>
        <jet-bar-table-data
          v-if="selected == 'moderators'"
          class="items-center justify-center cursor-pointer"
          v-on:click="viewGroups(user.id)"
        >
          <jet-bar-icon
            v-if="
              ($page.props.role.some(function (il) {
                return il.name === 'super-admin';
              }) &&
                selected == 'moderators') ||
              ($page.props.permission.some(function (il) {
                return il.name === 'moderate_group';
              }) &&
                selected == 'moderators')
            "
            type="eye"
            fill
        /></jet-bar-table-data>
      </tr>
    </jet-bar-table>
    <jet-bar-paginate :items="users" @nextLink="paginate1($event)" />
  </div>
  <jet-dialog-modal :show="confirmingUserView" @close="confirmingUserView = false">
    <template #title> View User </template>

    <template #content>
      <jet-bar-table
        :headers="selected == 'users' ? ['User Voucher Code'] : ['Moderators Group']"
      >
        <tr class="hover:bg-gray-50" v-for="voucher in user_codes" :key="voucher.name">
          <jet-bar-table-data>
            <p class="font-semibold text-black">
              {{ selected == "users" ? voucher.voucher_code : voucher.name }}
            </p></jet-bar-table-data
          >
        </tr>
      </jet-bar-table>
    </template>
    <template #footer>
      <jet-secondary-button @click="confirmingUserView = false">
        Close
      </jet-secondary-button>
    </template>
  </jet-dialog-modal>
</template>

<script>
import JetBarContainer from "@/Components/JetBarContainer";
import JetBarAlert from "@/Components/JetBarAlert";
import JetBarStatsContainer from "@/Components/JetBarStatsContainer";
import JetBarStatCard from "@/Components/JetBarStatCard";
import JetBarTable from "@/Components/JetBarTable";
import JetBarTableData from "@/Components/JetBarTableData";
import JetBarBadge from "@/Components/JetBarBadge";
import JetBarIcon from "@/Components/JetBarIcon";
import JetBarPaginate from "@/Components/JetBarSimplePagination";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import JetDialogModal from "@/Jetstream/DialogModal";
import JButton from "@/Jetstream/Button";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import axios from "axios";

export default {
  components: {
    JetBarContainer,
    JetBarAlert,
    JetBarStatsContainer,
    JetBarStatCard,
    JetBarTable,
    JetBarTableData,
    JetBarBadge,
    JetBarIcon,
    JButton,
    JetBarPaginate,
    JetConfirmationModal,
    JetDangerButton,
    JetSecondaryButton,
    JetDialogModal,
  },
  emits: ["paginate", "novoucher"],
  props: ["users", "selected"],
  data() {
    return {
      selectedUser: null,
      user_codes: [],
      confirmingUserView: false,
    };
  },
  mounted() {},
  methods: {
    viewGroups(data) {
      axios
        .get(route("admin.usermoderated", data))
        .then((res) => {
          if (res.data.length == 0) {
            this.$emit("novoucher", null);
          } else {
            this.confirmingUserView = true;
            this.user_codes = res.data;
          }
        })
        .catch((e) => console.log(e));
    },
    viewCodes(data) {
      axios
        .get(route("get.usercodes", data))
        .then((res) => {
          if (res.data.length == 0) {
            this.$emit("novoucher", null);
          } else {
            this.confirmingUserView = true;
            this.user_codes = res.data;
          }
        })
        .catch((e) => console.log(e));
    },
    paginate1(href) {
      this.$emit("paginate", href);
    },
  },
};
</script>
