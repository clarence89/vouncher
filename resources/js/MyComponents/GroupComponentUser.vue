<template>
  <div v-if="users.total">
    <jet-bar-table
      class="table-auto"
      :headers="['User Name', 'View User', 'Remove User']"
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
          class="items-center justify-center cursor-pointer"
          v-on:click="viewCodes(user.id)"
        >
          <jet-bar-icon
            v-if="
              $page.props.role.some(function (il) {
                return il.name === 'super-admin';
              }) ||
              $page.props.permission.some(function (il) {
                return il.name === 'moderate_group';
              })
            "
            type="eye"
            fill
        /></jet-bar-table-data>
        <jet-bar-table-data
          class="items-center justify-center cursor-pointer"
          v-on:click="
            (confirmingUserDeletion = true),
              (selectedUser = user.id),
              (group_named = group_name)
          "
        >
          <jet-bar-icon
            v-if="
              $page.props.role.some(function (il) {
                return il.name === 'super-admin';
              }) ||
              $page.props.permission.some(function (il) {
                return il.name === 'moderate_group';
              })
            "
            type="trash"
            fill
        /></jet-bar-table-data>
      </tr>
    </jet-bar-table>
    <jet-bar-paginate :items="users" @nextLink="paginate1($event)" />
    <jet-confirmation-modal
      :show="confirmingUserDeletion"
      @close="confirmingUserDeletion = false"
    >
      <template #title> Remove User </template>

      <template #content>
        Are you sure you want to remove the user from the group?
      </template>
      <template #footer>
        <jet-secondary-button @click="confirmingUserDeletion = false">
          Cancel
        </jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          @click="removeUser(), (confirmingUserDeletion = false)"
        >
          Remove User
        </jet-danger-button>
      </template>
    </jet-confirmation-modal>
    <jet-dialog-modal :show="confirmingUserView" @close="confirmingUserView = false">
      <template #title> View User </template>

      <template #content>
        <jet-bar-table :headers="['User Voucher Code']">
          <tr class="hover:bg-gray-50" v-for="voucher in user_codes" :key="voucher.id">
            <jet-bar-table-data>
              <p class="font-semibold text-black">
                {{ voucher.voucher_code }}
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
  </div>
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
  props: ["users", "group_name"],
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
  data() {
    return {
      viewer: null,
      selectedUser: null,
      group_named: null,
      user_codes: [],
      confirmingUserDeletion: false,
      confirmingUserView: false,
    };
  },
  mounted() {},
  methods: {
    removeUser(data) {
      axios
        .post(route("get.remove_user_group"), {
          user_id: this.selectedUser,
          group_name: this.group_named,
        })
        .then((res) => {
          this.$emit("refreshComponent");
        })
        .catch((e) => {});
    },
    viewCodes(data) {
      axios
        .get(route("get.usercodes", data))
        .then((res) => {
          if (res.data.length == 0) {
            this.$emit("novoucher");
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
