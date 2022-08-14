<template>
  <div class="max-w-7xl mx-auto pt-3 px-4 sm:px-6 lg:px-8">
    <!-- Title -->
    <h1
      class="text-lg font-semibold tracking-widest text-gray-900 uppercase dark-mode:text-white"
    >
      Manage Groups
    </h1>
    <!-- End Title -->
  </div>
  <jet-bar-container>
    <jet-bar-alert
      v-on:click="removeAlertData()"
      v-show="alert_status"
      :text="alertmessage"
      :type="type"
    />
    <div class="grid grid-cols-2">
      <div class="mx-2">
        <jet-bar-table :headers="['Group Name', '', '']">
          <tr class="hover:bg-gray-50" v-for="group in groups.data" :key="group.id">
            <jet-bar-table-data>
              <p class="font-semibold text-black">{{ group.name }}</p>
            </jet-bar-table-data>
            <jet-bar-table-data class="items-center justify-center cursor-pointer">
              <jet-bar-icon
                v-if="
                  $page.props.user.roles.some(function (il) {
                    return il.name === 'super-admin';
                  }) ||
                  $page.props.user.permissions.some(function (il) {
                    return il.name === 'moderate_group';
                  })
                "
                type="add_users"
            /></jet-bar-table-data>
            <jet-bar-table-data
              class="items-center justify-center cursor-pointer"
              v-on:click="getGroupUsers(group.name)"
            >
              <jet-bar-icon
                v-if="
                  $page.props.user.roles.some(function (il) {
                    return il.name === 'super-admin';
                  }) ||
                  $page.props.user.permissions.some(function (il) {
                    return il.name === 'moderate_group';
                  })
                "
                type="users"
                fill
            /></jet-bar-table-data>
          </tr>
        </jet-bar-table>
        <jet-bar-paginate :items="groups" @nextLink="paginate1($event)" />
      </div>
      <div class="mx-2">
        <group-users
          v-if="group_name != null"
          :users="users"
          :group_name="group_name"
          @refreshComponent="getGroupUsers(group_name)"
        />
      </div>
    </div>
  </jet-bar-container>
</template>

<script>
import GroupUsers from "@/MyComponents/GroupComponentUser";
import JetBarContainer from "@/Components/JetBarContainer";
import JetBarAlert from "@/Components/JetBarAlert";
import JetBarStatsContainer from "@/Components/JetBarStatsContainer";
import JetBarStatCard from "@/Components/JetBarStatCard";
import JetBarTable from "@/Components/JetBarTable";
import JetBarTableData from "@/Components/JetBarTableData";
import JetBarBadge from "@/Components/JetBarBadge";
import JetBarIcon from "@/Components/JetBarIcon";
import JetBarPaginate from "@/Components/JetBarSimplePagination";
import JButton from "@/Jetstream/Button";
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
    GroupUsers,
  },
  data() {
    return {
      group_name: null,
      groups: [],
      users: [],
      alert_status: 0,
      alertmessage: "",
      type: "info",
    };
  },
  mounted() {
    this.getGroup();
  },
  methods: {
    getGroupUsers(data) {
      this.group_name = data;
      axios
        .get(route("get.get_groupuser", { group_name: this.group_name }))
        .then((res) => {
          this.users = res.data;
          if (this.users.total == 0) {
            this.alertData(1, "No Users in Group.", "info");
          }
        })
        .catch((e) => {
          this.alertData(
            1,
            "Unable to Retrieve Data: Insufficient Permission/Server Error",
            "danger"
          );
        });
    },
    getGroup() {
      axios
        .get(route("get.group"))
        .then((res) => {
          this.groups = res.data;
          if (this.groups.total == 0) {
            this.alertData(1, "No data fetched.", "danger");
          }
        })
        .catch((e) => {
          this.alertData(
            1,
            "Unable to Retrieve Data: Insufficient Permission/Server Error",
            "danger"
          );
        });
    },
    alertData(status, message, type) {
      this.alert_status = status;
      this.alertmessage = message;
      this.type = type;
      setInterval(
        function () {
          this.removeAlertData();
        }.bind(this),
        2500
      );
    },
    removeAlertData() {
      this.alert_status = 0;
      this.alertmessage = "";
      this.type = "info";
    },
    paginate1(href) {
      axios
        .get(href)
        .then((res) => {
          this.groups = res.data;
          if (this.groups.total == 0) {
            this.alertData(1, "No data fetched.", "danger");
          }
        })
        .catch((e) => {
          this.alertData(
            1,
            "Unable to Retrieve Data: Insufficient Permission/Server Error",
            "danger"
          );
        });
    },
  },
};
</script>
