<template>
  <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
    <!-- Title -->
    <h1
      class="text-lg font-semibold tracking-widest text-gray-900 uppercase dark-mode:text-white"
    >
      Manage Group Users {{ group_name ? "- " + group_name : "" }}
    </h1>
    <!-- End Title -->
  </div>
  <div
    v-if="
      $page.props.role.some(function (il) {
        return il.name === 'super-admin';
      })
    "
    class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8 bg-gray-200"
  >
    <jet-secondary-button
      class="bg-green-400 cursor-pointer"
      v-on:click="(viewer = 'addgroup'), (confirmingUserView = true)"
    >
      <!--    <jet-bar-icon type="add_users" fill /> -->
      Add New Group
    </jet-secondary-button>
    <select class="mx-2" v-model="selected">
      <option value="users">Users</option>
      <option value="settings">Group Settings</option>
    </select>
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
        <jet-bar-table
          class="table-auto"
          :headers="
            $page.props.role.some(function (il) {
              return il.name === 'super-admin';
            })
              ? selected == 'settings'
                ? ['Group Name', 'Update', 'Delete']
                : [
                    'Group Name',

                    'Add Moderator',
                    'Add Users',
                    'View Users',
                    'Download Vouchers',
                  ]
              : ['Group Name', 'Add Users', 'View Users', 'Download Vouchers']
          "
        >
          <tr class="hover:bg-gray-50" v-for="group in groups.data" :key="group.id">
            <jet-bar-table-data>
              <p class="font-semibold text-black">{{ group.name }}</p>
            </jet-bar-table-data>
            <jet-bar-table-data
              class="items-center justify-center cursor-pointer"
              v-on:click="updateGroup(group.id)"
              v-if="
                $page.props.role.some(function (il) {
                  return il.name === 'super-admin';
                }) && selected == 'settings'
              "
            >
              <jet-bar-icon type="pencil" fill />
            </jet-bar-table-data>
            <jet-bar-table-data
              class="items-center justify-center cursor-pointer"
              v-on:click="deleteGroup(group.id)"
              v-if="
                $page.props.role.some(function (il) {
                  return il.name === 'super-admin';
                }) && selected == 'settings'
              "
            >
              <jet-bar-icon type="trash" fill />
            </jet-bar-table-data>
            <jet-bar-table-data
              class="items-center justify-center cursor-pointer"
              v-on:click="getUsersModerator(group.name), getGroupUsers(group.name)"
              v-if="
                $page.props.role.some(function (il) {
                  return il.name === 'super-admin';
                }) && selected == 'users'
              "
            >
              <jet-bar-icon type="add_moderator" fill />
            </jet-bar-table-data>

            <jet-bar-table-data
              v-on:click="getUsers(), getGroupUsers(group.name)"
              class="items-center justify-center cursor-pointer"
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
                type="add_users"
            /></jet-bar-table-data>
            <jet-bar-table-data
              class="items-center justify-center cursor-pointer"
              v-on:click="getGroupUsers(group.name)"
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
                type="users"
                fill
            /></jet-bar-table-data>
            <jet-bar-table-data
              class="items-center justify-center cursor-pointer"
              v-on:click="generateGroup(group.name)"
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
                type="download"
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
          @paginate="paginate2($event)"
          @novoucher="alertData(true, 'No Voucher Code Generated by the User', 'danger')"
        />
      </div>
    </div>
  </jet-bar-container>
  <jet-confirmation-modal :show="confirmingUserView" @close="confirmingUserView = false">
    <template #title v-if="viewer == 'adduser'"> Add User </template>
    <template #title v-else-if="viewer == 'addgroup'"> Add Group </template>
    <template #title v-else-if="viewer == 'updategroup'"> Update Group </template>
    <template #title v-else-if="viewer == 'Delete Group'"> Delete Group </template>
    <template #title v-else> Remove User </template>

    <template #content v-if="viewer == 'adduser'">
      <div v-for="wusers in wousers" v-bind:key="wusers.id">
        <input
          type="checkbox"
          :name="wusers.id"
          :value="wusers.id"
          v-model="selected_users"
        />
        <label :for="wusers.id">&nbsp;{{ wusers.name }}</label>
      </div>
    </template>
    <template #content v-else-if="viewer == 'addgroup' || viewer == 'updategroup'">
      <div class="w-full max-w-xs">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="groupname">
            Group Name
          </label>
          <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            id="groupname"
            v-model="group_name_input"
            type="text"
            placeholder="Group Name"
          />
        </div>
      </div>
    </template>
    <template #content v-else-if="viewer == 'deletegroup'">
      Are you sure you want to remove the group and its associated users?
    </template>
    <template #content v-else>
      Are you sure you want to remove the user from the group?
    </template>

    <template #footer>
      <jet-secondary-button @click="confirmingUserView = false">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        v-if="viewer == 'adduser'"
        class="ml-2"
        @click="addUsers(), (confirmingUserView = false)"
      >
        Add User
      </jet-danger-button>
      <jet-secondary-button
        v-else-if="viewer == 'addgroup'"
        class="ml-2 bg-green-400"
        @click="addGroup(), (confirmingUserView = false)"
      >
        Add Group
      </jet-secondary-button>
      <jet-secondary-button
        v-else-if="viewer == 'updategroup'"
        class="ml-2 bg-blue-400"
        @click="updateGroupPost(), (confirmingUserView = false)"
      >
        Update Group
      </jet-secondary-button>

      <jet-secondary-button
        v-else-if="viewer == 'deletegroup'"
        class="ml-2 bg-red-400"
        @click="deleteGroupPost(), (confirmingUserView = false)"
      >
        Delete Group
      </jet-secondary-button>
      <jet-danger-button
        v-else
        class="ml-2"
        @click="removeUser(), (confirmingUserView = false)"
      >
        Remove User
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
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
import JetDangerButton from "@/Jetstream/DangerButton";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import JetConfirmationModal from "@/Jetstream/DialogModal";
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
    JetDangerButton,
    JetSecondaryButton,
    JetConfirmationModal,
  },
  data() {
    return {
      group_id: null,
      selected: "users",
      group_name_input: null,
      alert_status: false,
      confirmingUserView: false,
      viewer: null,
      group_name: null,
      groups: [],
      users: [],
      wousers: [],
      selected_users: [],
      alertmessage: "",
      type: "info",
    };
  },
  mounted() {
    this.getGroup();
  },
  methods: {
    updateGroup(data) {
      this.group_id = data;
      this.viewer = "updategroup";
      this.confirmingUserView = true;
    },
    updateGroupPost() {
      axios
        .patch(route("admin.update_group"), {
          group_id: this.group_id,
          group_name: this.group_name_input,
        })
        .then((res) => {
          if (res.data == "success") {
            this.alertData(true, "Group has been updated.", "info");
            this.getGroup();
            this.group_name_input = null;
          } else {
            this.alertData(true, "Group Already Exist", "danger");
          }
        })
        .catch((e) => {
          console.log(e);
        });
    },
    deleteGroup(data) {
      this.group_id = data;
      this.viewer = "deletegroup";
      this.confirmingUserView = true;
    },
    deleteGroupPost() {
      axios
        .post(route("admin.delete_group"), {
          group_id: this.group_id,
        })
        .then((res) => {
          if (res.data == "success") {
            this.alertData(true, "Group has been delete.", "info");
            this.getGroup();
            this.group_name_input = null;
          } else {
            this.alertData(true, "Group has not been deleted", "danger");
          }
        })
        .catch((e) => {
          console.log(e);
        });
    },
    addGroup() {
      axios
        .post(route("admin.create_group"), {
          group_name: this.group_name_input,
        })
        .then((res) => {
          if (res.data == "success") {
            this.alertData(true, "Group Has Been created.", "info");
            this.getGroup();
            this.group_name_input = null;
          } else {
            this.alertData(true, "Group Already Exist", "danger");
          }
        });
    },
    generateGroup(data) {
      axios
        .post(
          route("generate.group"),
          {
            group_name: data,
          },
          { responseType: "blob" }
        )
        .then((res) => {
          console.log(res.data);
          var fileURL = window.URL.createObjectURL(new Blob([res.data]));
          var fileLink = document.createElement("a");

          fileLink.href = fileURL;
          fileLink.setAttribute("download", data + " User Vouchers.xlsx");
          document.body.appendChild(fileLink);

          fileLink.click();
        });
    },
    getUsersModerator(data) {
      this.viewer = "adduser";
      this.confirmingUserView = true;
      axios
        .get(route("get.get_user_moderator", data))
        .then((res) => {
          this.wousers = res.data;
        })
        .catch((e) => console.log(e));
    },
    getUsers() {
      this.viewer = "adduser";
      this.confirmingUserView = true;
      axios
        .get(route("get.get_user"))
        .then((res) => {
          this.wousers = res.data;
        })
        .catch((e) => console.log(e));
    },
    addUsers() {
      axios
        .patch(route("patch.users"), {
          users: this.selected_users,
          group_name: this.group_name,
        })
        .then((res) => {
          console.log(res.data);
          this.getGroupUsers1();
        })
        .catch((e) => console.log());
    },
    getGroupUsers1() {
      axios
        .get(route("get.get_groupuser", { group_name: this.group_name }))
        .then((res) => {
          this.users = res.data;
          if (this.users.total == 0) {
            this.alertData(true, "No Users in Group.", "info");
          }
        })
        .catch((e) => {
          this.alertData(
            true,
            "Unable to Retrieve Data: Insufficient Permission/Server Error",
            "danger"
          );
        });
    },
    getGroupUsers(data) {
      this.group_name = data;
      axios
        .get(route("get.get_groupuser", { group_name: this.group_name }))
        .then((res) => {
          this.users = res.data;
          if (this.users.total == 0) {
            this.alertData(true, "No Users in Group.", "info");
          }
        })
        .catch((e) => {
          this.alertData(
            true,
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
            this.alertData(true, "No data fetched.", "danger");
          }
        })
        .catch((e) => {
          this.alertData(
            true,
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
      this.alert_status = false;
      this.alertmessage = "";
      this.type = "info";
    },
    paginate1(href) {
      axios
        .get(href)
        .then((res) => {
          this.groups = res.data;
          if (this.groups.total == 0) {
            this.alertData(true, "No data fetched.", "danger");
          }
        })
        .catch((e) => {
          this.alertData(
            true,
            "Unable to Retrieve Data: Insufficient Permission/Server Error",
            "danger"
          );
        });
    },
    paginate2(href) {
      axios
        .get(href + "&&group_name=" + this.group_name)
        .then((res) => {
          this.users = res.data;
          if (this.users.total == 0) {
            this.alertData(true, "No data fetched.", "danger");
          }
        })
        .catch((e) => {
          this.alertData(
            true,
            "Unable to Retrieve Data: Insufficient Permission/Server Error",
            "danger"
          );
        });
    },
  },
};
</script>
