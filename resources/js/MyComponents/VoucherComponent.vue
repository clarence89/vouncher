<template>
  <div class="max-w-7xl mx-auto pt-3 px-4 sm:px-6 lg:px-8">
    <!-- Title -->
    <h1
      class="text-lg font-semibold tracking-widest text-gray-900 uppercase dark-mode:text-white"
    >
      Vouchers
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
    <div class="flex items-center justify-center">
      <j-button
        v-on:click="generateVoucher()"
        class="justify-items-center bg-green-800 hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-30 py-5 mx-auto my-5"
      >
        Generate Voucher</j-button
      >
    </div>

    <jet-bar-table :headers="['Voucher Codes']">
      <tr
        class="hover:bg-gray-50"
        v-for="voucher_code in voucher_codes.data"
        :key="voucher_code.id"
      >
        <jet-bar-table-data>{{ voucher_code.voucher_code }}</jet-bar-table-data>
        <jet-bar-table-data
          v-on:click="(confirmingUserDeletion = true), (voucher_id = voucher_code.id)"
        >
          <jet-bar-icon type="trash" fill
        /></jet-bar-table-data>
      </tr>
    </jet-bar-table>
    <jet-bar-paginate :items="voucher_codes" @nextLink="paginate1($event)" />
  </jet-bar-container>
  <jet-confirmation-modal
    :show="confirmingUserDeletion"
    @close="confirmingUserDeletion = false"
  >
    <template #title> Delete Voucher </template>

    <template #content> Are you sure you want to delete the voucher? </template>
    <template #footer>
      <jet-secondary-button @click="confirmingUserDeletion = false">
        Cancel
      </jet-secondary-button>

      <jet-danger-button
        class="ml-2"
        @click="removeVoucher(), (confirmingUserDeletion = false)"
      >
        Delete Voucher
      </jet-danger-button>
    </template>
  </jet-confirmation-modal>
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
import JButton from "@/Jetstream/Button";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import axios from "axios";
import JetDangerButton from "@/Jetstream/DangerButton";

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
  },
  data() {
    return {
      confirmingUserDeletion: false,
      voucher_id: null,
      voucher_codes: [],
      alert_status: 0,
      alertmessage: "",
      type: "info",
    };
  },
  mounted() {
    this.getVoucher();
  },
  methods: {
    removeVoucher() {
      axios
        .post(route("delete.voucher"), { voucher_id: this.voucher_id })
        .then((res) => {
          this.getVoucher();
          if (res.data == "Success") {
            this.alertData(true, "Generated Successfull", "success");
          } else {
            this.alertData(true, "Voucher Generation Limit Exceeded!", "danger");
          }
        })
        .catch((e) => console.log(e));
    },
    generateVoucher() {
      axios
        .post(route("create.voucher"))
        .then((res) => {
          this.getVoucher();
          if (res.data == "Success") {
            this.alertData(true, "Generated Successfull", "success");
          } else {
            this.alertData(true, "Voucher Generation Limit Exceeded!", "danger");
          }
        })
        .catch((e) => console.log(e));
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
    getVoucher() {
      axios
        .get(route("get.voucher"))
        .then((res) => {
          this.voucher_codes = res.data;
        })
        .catch((e) => console.log(e));
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
          this.voucher_codes = res.data;
        })
        .catch((e) => console.log(e));
    },
  },
};
</script>
